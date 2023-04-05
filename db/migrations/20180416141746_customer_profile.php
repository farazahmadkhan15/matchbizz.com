<?php


use Phinx\Migration\AbstractMigration;

class CustomerProfile extends AbstractMigration
{
    public function change()
    {
        $customerProfile = $this->table('customerProfile');
        $customerProfile->addColumn('firstName', 'string', ['limit'=> 50])
            ->addColumn('lastName', 'string', ['limit'=> 50])
            ->addColumn('gender', 'enum', ['values' => ['male','female', 'other']])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('phone', 'string', ['limit' => 20])
            ->addColumn('address', 'string', ['limit' => 200])
            ->addColumn('languageId', 'integer')
            ->addColumn('imageId', 'string', ['null' => true])
            ->addColumn('latitude', 'decimal', ['precision' => 17,'scale' => 14])
            ->addColumn('longitude', 'decimal', ['precision' => 17,'scale' => 14])
            ->addColumn('userId', 'integer')
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('languageId', 'language', 'id')
            ->addForeignKey('userId', 'user', 'id')
            ->addForeignKey('imageId', 'image', 'name')
            ->save();
    }
}
