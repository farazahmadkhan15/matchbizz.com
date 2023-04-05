<?php


use Phinx\Migration\AbstractMigration;

class PlanSubscription extends AbstractMigration
{
    public function change()
    {
        $planSubscription = $this->table('planSubscription');
        $planSubscription->addColumn('businessProfileId', 'integer')
            ->addColumn('planId', 'integer')
            ->addColumn('agreementId', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('status', 'enum', ['values' => ['pendingApproval', 'cancelled', 'active', 'suspended']])
            ->addColumn('startDate', 'timestamp')
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('planId', 'plan', 'id')
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->save();
    }
}
