<?php

use Phinx\Migration\AbstractMigration;

class ServiceMigration extends AbstractMigration
{
    public function change()
    {
        $services = $this->table('service');
        $services->addColumn('businessProfileId', 'integer')
            ->addColumn('categoryId', 'integer')
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->addForeignKey('categoryId', 'category', 'id')
            ->save();
    }
}
