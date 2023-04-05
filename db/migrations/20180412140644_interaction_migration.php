<?php

use Phinx\Migration\AbstractMigration;

class InteractionMigration extends AbstractMigration
{
    public function change()
    {
        $interactions = $this->table('interaction');
        $interactions->addColumn('interactionTypeId', 'integer')
            ->addColumn('userId', 'integer')
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addForeignKey('interactionTypeId', 'interactionType', 'id')
            ->addForeignKey('userId', 'user', 'id')
            ->save();
    }
}
