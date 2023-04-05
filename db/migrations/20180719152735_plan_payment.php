<?php


use Phinx\Migration\AbstractMigration;

class PlanPayment extends AbstractMigration
{
    public function change()
    {
        $planPayment = $this->table('planPayment');
        $planPayment->addColumn('planSubscriptionId', 'integer')
                    ->addColumn('transactionId', 'string')
                    ->addColumn('status', 'string', ['limit' => 50])
                    ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
                    ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
                    ->addColumn('deletedAt', 'datetime', ['null' => true])
                    ->addForeignKey('planSubscriptionId', 'planSubscription', 'id')
                    ->save();
    }
}
