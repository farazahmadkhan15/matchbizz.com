<?php


use Phinx\Migration\AbstractMigration;

class Conversation extends AbstractMigration
{
    public function change()
    {
        $conversation = $this->table('conversation');
        $conversation->addColumn('businessProfileId', 'integer')
            ->addColumn('customerProfileId', 'integer')
            ->addColumn('topic', 'string', ['limit'=>100])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->addForeignKey('customerProfileId', 'customerProfile', 'id')
            ->save();
    }
}
