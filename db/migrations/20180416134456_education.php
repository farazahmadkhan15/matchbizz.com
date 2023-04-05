<?php

use Phinx\Migration\AbstractMigration;

class Education extends AbstractMigration
{
    public function change()
    {
        $education = $this->table('education');
        $education->addColumn('name', 'string', ['limit' => 100])
            ->addIndex(['name'], ['unique' => true])
            ->save();
    }
}
