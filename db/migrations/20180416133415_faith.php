<?php

use Phinx\Migration\AbstractMigration;

class Faith extends AbstractMigration
{
    public function change()
    {
        $faiths = $this->table('faith');
        $faiths->addColumn('name', 'string', ['limit' => 100])
            ->addIndex(['name'], ['unique' => true])
            ->save();
    }
}
