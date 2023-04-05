<?php

use Phinx\Migration\AbstractMigration;

class LifeStyle extends AbstractMigration
{
    public function change()
    {
        $lifeStyles = $this->table('lifeStyle');
        $lifeStyles->addColumn('name', 'string', ['limit' => 100])
            ->addIndex(['name'], ['unique' => true])
            ->save();
    }
}
