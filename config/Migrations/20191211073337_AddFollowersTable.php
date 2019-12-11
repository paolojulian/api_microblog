<?php
use Migrations\AbstractMigration;

class AddFollowersTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('followers');

        $table
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
        
            ->addColumn('following_id', 'integer')
            ->addForeignKey('following_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])

            ->addColumn('created', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
                'null' => true,
            ])
            ->addColumn('deleted', 'timestamp', [
                'default' => null,
                'null' => true,
            ])
            ->save();
    }
}
