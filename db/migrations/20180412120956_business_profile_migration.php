<?php
use Phinx\Migration\AbstractMigration;
class BusinessProfileMigration extends AbstractMigration
{
    public function change()
    {
        $businesses = $this->table('businessProfile');
        $businesses->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('phone', 'string', ['limit' => 20])
            ->addColumn('description', 'text')
            ->addColumn('license', 'string', ['limit' => 30])
            ->addColumn('insurance', 'string', ['null' => true, 'limit' => 30])
            ->addColumn('reviewCount', 'integer', ['default'=>0])
            ->addColumn('address', 'string', ['limit' => 200])
            ->addColumn('latitude', 'decimal', ['precision' => 17,'scale' => 14])
            ->addColumn('longitude', 'decimal', ['precision' => 17,'scale' => 14])
            ->addColumn('rating', 'decimal', ['precision' => 9,'scale' => 2])
            ->addColumn('userId', 'integer')
            ->addColumn('imageId', 'string', ['null' => true])
            ->addColumn('type', 'enum', ['values' => ['residential', 'commercial', 'new', 'used', 'rental', 'leasing']])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('userId', 'user', 'id')
            ->addForeignKey('imageId', 'image', 'name')
            ->addColumn('deletedAt', 'datetime', ['null' => true]);
            
        if (!$businesses->hasColumn('name')) {
            $businesses->addColumn('name', 'string', ['limit' => 100]);
       }

   if')) {
            $businesses->addColumn('name', 'string', ['limit' => 100]);
       }
            
        $businesses->save();
    }
}

