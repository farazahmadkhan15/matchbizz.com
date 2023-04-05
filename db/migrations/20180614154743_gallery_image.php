<?php

use Phinx\Migration\AbstractMigration;

class GalleryImage extends AbstractMigration
{
    public function change()
    {
        $galleryImage = $this->table('galleryImage');
        $galleryImage->addColumn('imageId', 'string')
            ->addColumn('description', 'text')
            ->addColumn('businessProfileId', 'integer')
            //->addForeignKey('serviceId', 'service', 'id') waiting for confirmation
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false, 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deletedAt', 'datetime', ['null' => true])
            ->addForeignKey('businessProfileId', 'businessProfile', 'id')
            ->addForeignKey('imageId', 'image', 'name')
            ->save();
    }
}
