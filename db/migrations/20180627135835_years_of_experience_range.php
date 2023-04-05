<?php


use Phinx\Migration\AbstractMigration;

class YearsOfExperienceRange extends AbstractMigration
{
    public function change()
    {
        $yearsOfExperienceRange = $this->table('yearsOfExperienceRange');
        $yearsOfExperienceRange->addColumn('min', 'integer', ['null' => true])
            ->addColumn('max', 'integer', ['null' => true])
            ->save();
    }
}
