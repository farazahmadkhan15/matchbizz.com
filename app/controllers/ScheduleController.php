<?php

namespace App\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleSpan;
use App\Models\BusinessProfile;

class ScheduleController extends BaseController
{
    public function indexAction()
    {
        return Schedule::find()->toArray();
    }

    public function retrieveAction(int $id)
    {
        $this->featureChecker->check($id,'business-hours');

        $schedule = Schedule::findFirstById($id);

        if (! $schedule) {
            $this->setResponse(['error' => 'Schedules Not Found'], 404);
        } else {
            if ($schedule->getType() == 'specific_hour') {
                $schedule->appends(['intervals']);
            }
            $this->setResponse($schedule->toArray());
        }
    }

    public function createAction(int $id)
    {
        $this->featureChecker->check($id, 'business-hours');
        $this->featureChecker->checkCanEditBusinessProfile($id);

        if (! BusinessProfile::findFirstById($id)) {
            $this->setResponse(['error' => 'Business Profile not found'], 404);
        }

        $request = $this->request->getJsonRawBody();
        $validator = $this->di->get('RequestValidator');

        $validation = $validator->validate(json_decode(json_encode($request), true), [
            'type' => 'required|in:specific_hour,always_open,not_available,closed',
            'intervals' => 'required_only_if:type,specific_hour|array|disjoint_spans',
            'intervals.*.weekDay' => 'required|between:1,7',
            'intervals.*.startTime' => 'required|between:0,1439',
            'intervals.*.endTime' => 'required|between:0,1439',
            'intervals.*' => 'greaterThan:endTime,startTime'
        ]);

        if ($validation->fails()) {
            $this->setResponse(['error' => $validation->errors()->toArray(), 'valid' => false], 400);
            return;
        }

        $schedule = new Schedule();
        $schedule->setId($id);
        $schedule->setType($request->type);

        if (! $schedule->save()) {
            $this->setResponse(['error' => $this->headerCode[400]], 400);
        } else {
            $this->setResponse($schedule->appends(['intervals'])->toArray());
        }

        if (property_exists($request, 'intervals')) {
            $scheduleSpans = [];
            foreach($request->intervals as $span){
                $scheduleSpan = new ScheduleSpan();
                $scheduleSpan->setScheduleId($schedule->getId());
                $scheduleSpan->setWeekDay($span->weekDay);
                $scheduleSpan->setStartTime($span->startTime);
                $scheduleSpan->setEndTime($span->endTime);
                $scheduleSpan->save();
                $scheduleSpans[] = $scheduleSpan;
            }
            $schedule->intervals = $scheduleSpans;
        }
    }

    public function updateAction(int $id)
    {
        $this->featureChecker->check($id,'business-hours');
        $this->featureChecker->checkCanEditBusinessProfile($id);

        $schedule = Schedule::findFirstById($id);

        if (! $schedule) {
            $this->setResponse(['error' => 'Schedule Not Found'], 404);
            return;
        }

        $request = $this->request->getJsonRawBody();
        $validator = $this->di->get('RequestValidator');

        $validation = $validator->validate(json_decode(json_encode($request), true), [
            'type' => 'required|in:specific_hour,always_open,not_available,closed',
            'intervals' => 'required_only_if:type,specific_hour|array|disjoint_spans',
            'intervals.*.weekDay' => 'required|between:1,7',
            'intervals.*.startTime' => 'required|between:0,1439',
            'intervals.*.endTime' => 'required|between:0,1439',
            'intervals.*' => 'greaterThan:endTime,startTime'
        ]);

        if ($validation->fails()) {
            $this->setResponse(['error' => $validation->errors()->toArray()], 400);
            return;
        }

        $schedule->setType($request->type);

        if (! $schedule->save()) {
            $this->setResponse(['error' => $this->headerCode[$this->code]], 400);
        } else {
            foreach (ScheduleSpan::find("scheduleId = {$id}" ) as $span) {
                $span->delete();
            }

            if (property_exists($request, 'intervals')) {
                foreach ($request->intervals as $span) {
                    $scheduleSpan = new ScheduleSpan();
                    $scheduleSpan->setScheduleId($schedule->getId());
                    $scheduleSpan->setWeekDay($span->weekDay);
                    $scheduleSpan->setStartTime($span->startTime);
                    $scheduleSpan->setEndTime($span->endTime);
                    $scheduleSpan->save();
                }
            }
            $this->setResponse($schedule->appends(['intervals'])->toArray());
        }
    }

    public function deleteAction(int $id)
    {
        $this->featureChecker->check($id,'business-hours');
        $this->featureChecker->checkCanEditBusinessProfile($id);

        $schedule = Schedule::findFirstById($id);
        if (! $schedule) {
            $this->setResponse(['error' => 'Schedule Not Found'], 404);
        } else {
            if($schedule->getType() == 'specific_hour') {
                $schedule->getIntervals()->delete();
            }
            $schedule->delete();
            $this->setResponse(['ok' => true]);
        }
    }
}
