<?php

use Phinx\Migration\AbstractMigration;

class WorkerProfileLanguage extends AbstractMigration
{
    public function change()
    {
        $this->table('workerProfileLanguage')
            ->addColumn('workerProfileId', 'integer')
            ->addColumn('languageId', 'integer')
            ->addForeignKey('workerProfileId', 'workerProfile', 'id')
            ->addForeignKey('languageId', 'language', 'id')
            ->addIndex(['languageId', 'workerProfileId'], ['unique' => true])
            ->save();
    }
}
