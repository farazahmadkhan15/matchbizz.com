<?php

use Phinx\Migration\AbstractMigration;

class WorkerProfileHobbie extends AbstractMigration
{
    public function change()
    {
        $this->table('workerProfileHobbie')
            ->addColumn('workerProfileId', 'integer')
            ->addColumn('hobbieId', 'integer')
            ->addForeignKey('workerProfileId', 'workerProfile', 'id')
            ->addForeignKey('hobbieId', 'hobbie', 'id')
            ->addIndex(['hobbieId', 'workerProfileId'], ['unique' => true])
            ->save();
    }
}
