<?php

use Phinx\Migration\AbstractMigration;

class Icons extends AbstractMigration
{
    public function change()
    {
        $planPayment = $this->table('icons');
        $planPayment->addColumn('code', 'string', ['limit' => 25])
                    ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
                    ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
                    ->addColumn('deletedAt', 'datetime', ['null' => true])
                    ->save();
    }
}
