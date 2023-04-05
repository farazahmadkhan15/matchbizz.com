<?php

use Rakit\Validation\Rule;

class DisjointSpansRule extends Rule
{

    protected $message = "The :attribute contains overlapping spans";

    protected $fillable_params = [];

    public function check($ranges)
    {
        for ($i = 0; $i < count($ranges); $i++) {
            for ($j = $i + 1; $j < count($ranges); $j++) {
                if ($this->rangesOverlap($ranges[$i], $ranges[$j])) {
                    return false;
                }
            }
        }
        return true;
    }

    private function rangesOverlap($a, $b)
    {
        return $a['weekDay'] == $b['weekDay']
            && (
                   ($a['startTime'] <= $b['startTime'] && $a['endTime']   >= $b['startTime'])
                || ($a['endTime']   >= $b['endTime']   && $a['startTime'] <= $b['endTime'])
                || ($a['startTime'] >= $b['startTime'] && $a['endTime']   <= $b['endTime'])
                || ($b['startTime'] >= $a['startTime'] && $b['endTime']   <= $a['endTime'])
            );
    }

}