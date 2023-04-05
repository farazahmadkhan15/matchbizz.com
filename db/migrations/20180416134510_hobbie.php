<?php

use Phinx\Migration\AbstractMigration;

class Hobbie extends AbstractMigration
{
    public function change()
    {
        $hobbies = $this->table('hobbie');
        $hobbies->addColumn('name', 'string', ['limit' => 100])
            ->addIndex(['name'], ['unique' => true])
            ->save();
    }
}
