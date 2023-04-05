<?php


use Phinx\Migration\AbstractMigration;

class AgeRange extends AbstractMigration
{

    public function change()
    {
        $ageRange = $this->table('ageRange');
        $ageRange->addColumn('min', 'integer',['null' => true])
            ->addColumn('max', 'integer',['null' => true])
            ->save();
    }
}
