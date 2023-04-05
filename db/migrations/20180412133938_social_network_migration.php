<?php

use Phinx\Migration\AbstractMigration;

class SocialNetworkMigration extends AbstractMigration
{
    public function change()
    {
        $this->table('socialNetwork')
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('baseUrl', 'string', ['limit' => 100])
            ->addColumn('icon', 'string', ['limit' => 150])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->save();
    }
}
