<?php


use Phinx\Migration\AbstractMigration;

class Image extends AbstractMigration
{
  
    public function change()
<<<<<<< HEAD
    {
        $image = $this->table('image', ['id' => false, 'primary_key' => ['name']]);
        $image->addColumn('name', 'string',['limit'=>36]) //UUID
            ->addColumn('used', 'boolean')
            ->addColumn('extension', 'string',['limit'=>10])
            ->addColumn('address', 'string', ['limit'=>100])
            ->addColumn('addressThumbnail', 'string', ['limit'=>100])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addIndex(['name'], ['unique' => true])
            ->save();
    }
=======
{
    $image = $this->table('image', ['id' => false]);
    $image->addColumn('name', 'string', ['limit' => 36, 'default' => ''])
        ->addColumn('used', 'boolean')
        ->addColumn('extension', 'string', ['limit' => 10])
        ->addColumn('address', 'string', ['limit' => 100])
        ->addColumn('addressThumbnail', 'string', ['limit' => 100])
        ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
        ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
        ->addColumn('deletedAt', 'datetime', ['null' => true])
        ->addIndex(['name'], ['unique' => true])
        ->create();
}
>>>>>>> 024b3a8 (push from server)
}
