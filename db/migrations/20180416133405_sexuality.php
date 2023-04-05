<?php

use Phinx\Migration\AbstractMigration;

class Sexuality extends AbstractMigration
{
    public function change()
    {
        $sexualities = $this->table('sexuality');
        $sexualities->addColumn('name', 'string', ['limit' => 100])
            ->addIndex(['name'], ['unique' => true])
            ->save();
    }
}
