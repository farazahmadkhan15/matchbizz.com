<?php


use Phinx\Migration\AbstractMigration;

class PlanFeature extends AbstractMigration
{
    public function change()
    {
        $planFeature = $this->table('planFeature');
        $planFeature->addColumn('planId', 'integer')
            ->addColumn('featureId', 'integer')
            ->addColumn('special', 'boolean', ['default' => false])
            ->addForeignKey('planId', 'plan', 'id')
            ->addForeignKey('featureId', 'feature', 'id')
            ->save();
    }
}
