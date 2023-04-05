<?php

use Phinx\Migration\AbstractMigration;

class Ethnicity extends AbstractMigration
{
    public function change()
    {
        $ethnicities = $this->table('ethnicity');
        $ethnicities->addColumn('name', 'string', ['limit' => 100])
            ->addIndex(['name'], ['unique' => true])
            ->save();
    }
}
