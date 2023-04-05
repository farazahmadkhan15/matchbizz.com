<?php


use Phinx\Migration\AbstractMigration;

class Message extends AbstractMigration
{
    public function change()
    {
        $message = $this->table('message');
        $message->addColumn('conversationId', 'integer')
            ->addColumn('content', 'text')
            ->addColumn('from', 'enum', ['values' => ['business','customer']])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('conversationId', 'conversation', 'id')
            ->save();
    }
}
