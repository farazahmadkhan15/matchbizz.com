<?php


use Phinx\Migration\AbstractMigration;

class InfluenceArea extends AbstractMigration
{
    public function change()
    {
        $influenceArea = $this->table('influenceArea');
        $influenceArea->addColumn('displayId', 'integer')
            ->addColumn('radius', 'decimal', ['precision' => 17,'scale' => 5])
            ->addColumn('latitude', 'decimal', ['precision' => 17,'scale' => 14])
            ->addColumn('longitude', 'decimal', ['precision' => 17,'scale' => 14])
            ->addColumn('businessProfileId', 'integer')
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->save();
    }
}
