<?php


use Phinx\Migration\AbstractMigration;

class UserRole extends AbstractMigration
{
    public function change()
    {
        $userRole = $this->table('userRole');
        $userRole->addColumn('userId', 'integer')
                ->addColumn('roleId', 'integer')
                ->addForeignKey('roleId', 'role', 'id')
                ->addForeignKey('userId', 'user', 'id')
                ->save();
    }
}
