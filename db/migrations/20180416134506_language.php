<?php

use Phinx\Migration\AbstractMigration;

class Language extends AbstractMigration
{
    public function change()
    {
        $languages = $this->table('language');
        $languages->addColumn('name', 'string', ['limit' => 100])
            ->addIndex(['name'], ['unique' => true])
            ->save();
    }
}
