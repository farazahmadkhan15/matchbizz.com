<?php

use Phinx\Migration\AbstractMigration;

class SocialNetworkAccountMigration extends AbstractMigration
{
    public function change()
    {
        $this->table('socialNetworkAccount')
            ->addColumn('socialNetworkId', 'integer')
            ->addColumn('businessProfileId', 'integer')
            ->addColumn('urlSegment', 'string', ['limit' => 100])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addIndex(['socialNetworkId', 'businessProfileId'], ['unique' => true])
            ->addForeignKey('socialNetworkId', 'socialNetwork', 'id')
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->save();
    }
}
