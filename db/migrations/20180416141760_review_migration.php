<?php

use Phinx\Migration\AbstractMigration;

class ReviewMigration extends AbstractMigration
{
    public function change()
    {
        $reviews = $this->table('review');
        $reviews->addColumn('title', 'string', ['null' => true, 'limit' => 100])
            ->addColumn('content', 'text', ['null' => true])
            ->addColumn('rating', 'integer')
            ->addColumn('customerProfileId', 'integer')
            ->addColumn('businessProfileId', 'integer')
            ->addColumn('reply', 'string', ['null' => true])
            ->addColumn('offensive', 'boolean', ['default' => false])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('customerProfileId', 'customerProfile', 'id')
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->save();
    }
}
