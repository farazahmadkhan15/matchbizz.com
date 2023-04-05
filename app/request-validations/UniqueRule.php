<?php

use Rakit\Validation\Rule;
use Phalcon\Mvc\Model\Manager;

class UniqueRule extends Rule
{
    protected $message = ":attribute must be unique. :attribute :value exists in :table";

    protected $fillable_params = ['table', 'column'];

    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function check($value)
    {
        // make sure required parameters exists
        $this->requireParameters(['table', 'column']);

        // getting parameters
        $column = $this->parameter('column');
        $table = 'App\\Models\\' . $this->parameter('table');

        // do query
        $query = $this->manager->createQuery("SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value:");
        $data  = $query->execute(
            [
                'value' => $value
            ]
        )->getFirst();

        // true for valid, false for invalid
        return intval($data['count']) == 0;
    }
}