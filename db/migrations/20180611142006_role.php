<?php


use Phinx\Migration\AbstractMigration;

class Role extends AbstractMigration
{
    public function change()
    {
        $role = $this->table('role');
        $role->addColumn('name', 'string', ['limit'=>100])
        ->addColumn('description', 'text')
        ->save();
    }
}
