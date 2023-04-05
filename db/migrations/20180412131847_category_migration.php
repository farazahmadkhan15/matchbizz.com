<?php

use Phinx\Migration\AbstractMigration;

class CategoryMigration extends AbstractMigration
{
    public function change()
    {
        $category = $this->table('category');
        $category->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('description', 'text')
            ->addColumn('parentCategoryId', 'integer', ['null' => true])
            ->addColumn('iconId', 'integer', ['null' => true])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('parentCategoryId', 'category', 'id')
            ->addForeignKey('iconId', 'icons', 'id')
            ->save();
    }
}
