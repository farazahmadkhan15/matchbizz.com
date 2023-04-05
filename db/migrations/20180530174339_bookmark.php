<?php


use Phinx\Migration\AbstractMigration;

class Bookmark extends AbstractMigration
{
    public function change()
    {
        $bookmark = $this->table('bookmark');
        $bookmark->addColumn('businessProfileId', 'integer')
            ->addColumn('customerProfileId', 'integer')
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->addForeignKey('customerProfileId', 'customerProfile', 'id')
            ->addIndex(['businessProfileId', 'customerProfileId'], ['unique' => true])
            ->save();
    }
}
