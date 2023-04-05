<?php

use Phinx\Migration\AbstractMigration;

class InteractionTypeMigration extends AbstractMigration
{
    public function change()
    {
        $interactionTypes = $this->table('interactionType');
        $interactionTypes->addColumn('targetTableName', 'string', ['limit' => 100])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->save();
    }
}
