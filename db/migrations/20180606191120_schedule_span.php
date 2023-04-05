<?php

use Phinx\Migration\AbstractMigration;

class ScheduleSpan extends AbstractMigration
{
    public function change()
    {
        $this->table('scheduleSpan')
            ->addColumn('weekDay', 'integer')
            ->addColumn('startTime', 'integer')
            ->addColumn('endTime', 'integer')
            ->addColumn('scheduleId', 'integer')
            ->addForeignKey('scheduleId', 'schedule', 'id')
            ->save();
    }
}
