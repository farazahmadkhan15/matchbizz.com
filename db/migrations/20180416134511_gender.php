<?php


use Phinx\Migration\AbstractMigration;

class Gender extends AbstractMigration
{
    public function change()
    {
        $gender = $this->table('gender');
        $gender->addColumn('name', 'string')
            ->save();
    }
}
