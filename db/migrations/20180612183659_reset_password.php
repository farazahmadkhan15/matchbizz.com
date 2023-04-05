<?php


use Phinx\Migration\AbstractMigration;

class ResetPassword extends AbstractMigration
{
    public function change()
    {
        $resetPassword = $this->table('resetPassword');
        $resetPassword->addColumn('userId', 'integer')
                    ->addColumn('token', 'string', ["limit"=>"100"])
                    ->addColumn('code', 'string', ["limit"=>"20"])
                    ->save();
        
    }
}
