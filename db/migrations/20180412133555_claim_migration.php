<?php

use Phinx\Migration\AbstractMigration;

class ClaimMigration extends AbstractMigration
{
    public function change()
    {
        $claims = $this->table('claim');
        $claims->addColumn('status', 'enum', ['values' => ['pending', 'approved', 'rejected']])
            ->addColumn('businessProfileId', 'integer')
            ->addColumn('userId', 'integer')
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->addForeignKey('userId', 'user', 'id')
            ->save();
    }
}
