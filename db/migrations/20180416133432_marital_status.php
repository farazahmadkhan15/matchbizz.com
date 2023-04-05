<?php

use Phinx\Migration\AbstractMigration;

class MaritalStatus extends AbstractMigration
{
    public function change()
    {
        $maritalStatus = $this->table('maritalStatus');
        $maritalStatus->addColumn('name', 'string', ['limit' => 100])
            ->addIndex(['name'], ['unique' => true])
            ->save();
    }
}
