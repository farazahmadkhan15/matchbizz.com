<?php


use Phinx\Migration\AbstractMigration;

class InvalidToken extends AbstractMigration
{
    public function change()
    {
        $message = $this->table('invalidToken');
        $message->addColumn('token', 'string', ['limit'=> 255])
            ->addColumn('expiration', 'timestamp', [ "null" => false ])
            ->addIndex('token', ["unique" => true])
            ->save();
    }
}
