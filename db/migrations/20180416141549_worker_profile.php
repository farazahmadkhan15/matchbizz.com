<?php

use Phinx\Migration\AbstractMigration;

class WorkerProfile extends AbstractMigration
{
    public function change()
    {
        $this->table('workerProfile')
            ->addColumn('name', 'string')
            ->addColumn('age', 'integer')
            ->addColumn('yearsOfExperience', 'integer')
            ->addColumn('businessProfileId', 'integer', ['null' => true])
            ->addColumn('ethnicityId', 'integer')
            ->addColumn('faithId', 'integer')
            ->addColumn('lifeStyleId', 'integer')
            ->addColumn('maritalStatusId', 'integer')
            ->addColumn('educationId', 'integer')
            ->addColumn('genderId', 'integer')
            ->addColumn('isOwner', 'boolean', ['default' => false])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->addForeignKey('ethnicityId', 'ethnicity', 'id')
            ->addForeignKey('faithId', 'faith', 'id')
            ->addForeignKey('lifeStyleId', 'lifeStyle', 'id')
            ->addForeignKey('maritalStatusId', 'maritalStatus', 'id')
            ->addForeignKey('educationId', 'education', 'id')
            ->addForeignKey('genderId', 'gender', 'id')
            ->save();
    }
}
