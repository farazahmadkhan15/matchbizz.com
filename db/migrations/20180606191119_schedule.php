<?php


use Phinx\Migration\AbstractMigration;

class Schedule extends AbstractMigration
{
    public function change()
    {
        $this->table('schedule', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'integer')
            ->addColumn('type', 'enum', ['values' => ['specific_hour', 'always_open', 'not_available', 'closed']])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('id', 'businessProfile', 'id')
            ->save();
    }
}
