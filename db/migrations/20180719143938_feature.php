<?php


use Phinx\Migration\AbstractMigration;

class Feature extends AbstractMigration
{
    public function change()
    {
        $feature = $this->table('feature');
        $feature->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('description', 'text')
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->save();
    }
}
