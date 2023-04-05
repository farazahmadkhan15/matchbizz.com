<?php

use Phinx\Migration\AbstractMigration;

class UserMigration extends AbstractMigration
{
    public function change()
    {
        $users = $this->table('user');
        $users->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('username', 'string', ['null' => true, 'limit' => 20])
            ->addColumn('password', 'string', ['null' => true, 'limit' => 100])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP','null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addIndex('email', ['unique' => true])
            ->addIndex('username', ['unique' => true])
            ->save();
    }
}
