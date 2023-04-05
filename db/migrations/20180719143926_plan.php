<?php


use Phinx\Migration\AbstractMigration;

class Plan extends AbstractMigration
{
    public function change()
    {
        $plan = $this->table('plan');
        $plan->addColumn('name', 'string',['limit' => 100])
            ->addColumn('costAmount', 'decimal', ['precision' => 17,'scale' => 2])
            ->addColumn('costCurrencyCode', 'string', ['limit' => 3, 'default' => 'USD'])
            ->addColumn('billingCycleFrequency', 'enum', ['values' => ['Month', 'Year']])
            ->addColumn('billingCycleFrequencyInterval', 'integer', ['default' => 1])
            ->addColumn('billingCycleNumber', 'integer', ['default' => 0])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->save();
    }
}
