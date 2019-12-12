<?php
use Migrations\AbstractMigration;

class CreateComments extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('comments');
        $table
            ->addColumn('body', 'string', [
                'limit' => 140,
                'null' => false,
            ])

            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
        
            ->addColumn('post_id', 'integer')
            ->addForeignKey('post_id', 'posts', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])

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
            ->create();
    }
}
