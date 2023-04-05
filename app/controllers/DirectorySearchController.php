<?php

namespace App\Controllers;

use Phalcon\Mvc\Model\Query;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class DirectorySearchController extends BaseController
{
    private $stopWords = [
        "a", "about", "above", "after", "again", "against", "all", "am", "an", 
        "and", "any", "are", "aren't", "as", "at", "be", "because", "been", "before",
        "being", "below", "between", "both", "but", "by", "can't", "cannot", "could",
        "couldn't", "did", "didn't", "do", "does", "doesn't", "doing", "don't", "down",
        "during", "each", "few", "for", "from", "further", "had", "hadn't", "has",
        "hasn't", "have", "haven't", "having", "he", "he'd", "he'll", "he's", "her",
        "here", "here's", "hers", "herself", "him", "himself", "his", "how", "how's",
        "i", "i'd", "i'll", "i'm", "i've", "if", "in", "into", "is", "isn't", "it",
        "it's", "its", "itself", "let's", "me", "more", "most", "mustn't", "my",
        "myself", "no", "nor", "not", "of", "off", "on", "once", "only", "or", "other",
        "ought", "our", "ours", "ourselves", "out", "over", "own", "same", "shan't", "she",
        "she'd", "she'll", "she's", "should", "shouldn't", "so", "some", "such", "than", 
        "that", "that's", "the", "their", "theirs", "them", "themselves", "then", "there", 
        "there's", "these", "they", "they'd", "they'll", "they're", "they've", "this", "those",
        "through", "to", "too", "under", "until", "up", "very", "was", "wasn't", "we", "we'd", 
        "we'll", "we're", "we've", "were", "weren't", "what", "what's", "when", "when's", "where", 
        "where's", "which", "while", "who", "who's", "whom", "why", "why's", "with", "won't", 
        "would", "wouldn't", "you", "you'd", "you'll", "you're", "you've", "your", "yours", 
        "yourself", "yourselves"
    ];

    public function indexAction()
    {
        $request = $this->request->getJsonRawBody();
        $validator = $this->di->get('RequestValidator');

        $validation = $validator->validate(json_decode(json_encode($request), true), [
            'service' => 'required',
            'location' => 'required',
            'location.latitude' => 'required|numeric',
            'location.longitude' => 'required|numeric',
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $location = $request->location;
        $currentPage = $request->page;
        $limit = $request->limit;

        if (isset($request->workerProfile)) {
            $workerProfile = $request->workerProfile;
        } else {
            $workerProfile = (object)[
                "ageRangeId"=>null,
                "yearsOfExperienceRangeId"=>null,
                "ethnicityId"=>null,
                "faithId"=>null,
                "lifeStyleId"=>null,
                "maritalStatusId"=>null,
                "educationId"=>null,
            ];
        }

        if (is_string($request->service)) {
            $service = trim($request->service);

            $words = array_filter(explode(" ", $service), function ($word) {
                return $word != "";
            });

            $words = array_map( function ($word) {
                return preg_replace(["/es\b/","/s\b/"], "", $word);;
            }, $words);

            $condition = "";
            foreach ($words as $idx => $word) {
                if ( !in_array(strtolower($word), $this->stopWords) ){
                    $condition = $condition."category.name LIKE :name{$idx}: OR ";
                    $condition = $condition."BusinessProfile.name LIKE :name{$idx}: OR ";
                    $bindings["name{$idx}"] = "%{$word}%";
                }
            }
            $condition = substr($condition, 0, -3);
        } else if (is_object($request->service)) {
            $condition = "Service.categoryId = :categoryId:";
            $bindings = ["categoryId" => $request->service->id];
        } else {
            $this->setResponse([ "error" => "service must be a string or an object like { id: number }" ], 400);
            return;
        }

        $builder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "BusinessProfile.id",
                "BusinessProfile.name",
                "BusinessProfile.description",
                "BusinessProfile.rating",
                "category.*",
                "thumbnail" => "Image.addressThumbnail"
                ])
            ->from(["BusinessProfile" => "App\Models\BusinessProfile"])
            ->join("App\Models\PlanSubscription", "BusinessProfile.id = PlanSubscription.businessProfileId", "PlanSubscription", "LEFT")
            ->join("App\Models\UserRole", "BusinessProfile.userId = UserRole.userId", "UserRole", "LEFT")
            ->join("App\Models\Service", "BusinessProfile.id = Service.businessProfileId AND Service.deletedAt IS NULL", "Service", "LEFT")
            ->join("App\Models\Category", "category.id = Service.categoryId AND category.deletedAt IS NULL", "category", "LEFT")
            ->join("App\Models\InfluenceArea", "influenceArea.businessProfileId = BusinessProfile.id AND influenceArea.deletedAt IS NULL", "influenceArea", "LEFT")
            ->join("App\Models\WorkerProfile", "workerProfile.businessProfileId = BusinessProfile.id AND workerProfile.deletedAt IS NULL", "workerProfile", "LEFT")
            ->join("App\Models\WorkerProfileLanguage", "workerProfileLanguage.workerProfileId = workerProfile.id", "workerProfileLanguage", "LEFT")
            ->join("App\Models\WorkerProfileHobbie", "workerProfileHobbie.workerProfileId = workerProfile.id", "workerProfileHobbie", "LEFT")
            ->join("App\Models\AgeRange", "", "AgeRange", "LEFT")
            ->join("App\Models\YearsOfExperienceRange", "", "yearsOfExperienceRange", "LEFT")
            ->join("App\Models\Image","BusinessProfile.imageId = Image.name AND Image.deletedAt IS NULL", "Image", "LEFT")
            ->where("PlanSubscription.status = 'active' OR (UserRole.roleId = 1)")
            ->andWhere("BusinessProfile.deletedAt IS NULL")
            ->andWhere("(1000 * 6371 * Acos(Cos(Radians(:latitude_actual:)) * Cos(Radians(influenceArea.latitude)) * Cos(
                                    Radians(influenceArea.longitude) - Radians(:longitude_actual:)) + 
                            Sin(Radians(:latitude_actual:)) * Sin(Radians(influenceArea.latitude)))
                    ) <= influenceArea.radius", ["latitude_actual" => $location->latitude ,"longitude_actual" => $location->longitude ])
            ->andWhere($condition, $bindings)
            ->andWhere(":ageRangeId: IS NULL OR
                        (AgeRange.id = :ageRangeId: AND (workerProfile.age BETWEEN AgeRange.min AND AgeRange.max
                        OR (AgeRange.min IS NULL AND workerProfile.age <= AgeRange.max)
                        OR (AgeRange.max IS NULL AND workerProfile.age >= AgeRange.min)))",
                        ["ageRangeId" => $workerProfile->ageRangeId])
            ->andWhere(":yearsOfExperienceRangeId: IS NULL OR 
                        (yearsOfExperienceRange.id = :yearsOfExperienceRangeId: AND
                        (workerProfile.yearsOfExperience BETWEEN yearsOfExperienceRange.min AND yearsOfExperienceRange.max 
                        OR (yearsOfExperienceRange.min IS NULL AND workerProfile.yearsOfExperience <= yearsOfExperienceRange.max) 
                        OR (yearsOfExperienceRange.max IS NULL AND workerProfile.yearsOfExperience >= yearsOfExperienceRange.min)))",
                        ["yearsOfExperienceRangeId" => $workerProfile->yearsOfExperienceRangeId])
            ->andWhere(":ethnicityId: IS NULL OR
                        workerProfile.ethnicityId = :ethnicityId:",
                        ["ethnicityId" => $workerProfile->ethnicityId] )
            ->andWhere(":faithId: IS NULL OR 
                        workerProfile.faithId = :faithId:",
                        ["faithId" => $workerProfile->faithId] )
            ->andWhere(":lifeStyleId: IS NULL OR 
                        workerProfile.lifeStyleId = :lifeStyleId:",
                        ["lifeStyleId" => $workerProfile->lifeStyleId] )
            ->andWhere(":maritalStatusId: IS NULL OR 
                        workerProfile.maritalStatusId = :maritalStatusId:",
                        ["maritalStatusId" => $workerProfile->maritalStatusId] )
            ->andWhere(":educationId: IS NULL OR 
                        workerProfile.educationId = :educationId:",
                        ["educationId" => $workerProfile->educationId] )
            ->andWhere(":genderId: IS NULL OR
                        workerProfile.genderId = :genderId:",
                        ["genderId" => $workerProfile->genderId] )
            ->andWhere(":hobbieId: IS NULL OR 
                    workerProfileHobbie.hobbieId = :hobbieId:",
                        ["hobbieId" => $workerProfile->hobbieId] )
            ->andWhere(":languageId: IS NULL OR 
                    workerProfileLanguage.languageId = :languageId:",
                        ["languageId" => $workerProfile->languageId] )
            ->andWhere("BusinessProfile.deletedAt IS NULL")
            ->groupBy("BusinessProfile.id,BusinessProfile.description,BusinessProfile.rating,category.id");

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page" => $currentPage,
            ]
        );
        return $paginator->getPaginate();
    }
}