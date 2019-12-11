<?php
use Migrations\AbstractMigration;

class CreatePostTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $table = $this->table('posts');
        $table->addColumn('title', 'string', [
            'default' => '',
            'limit' => 30,
            'null' => false,
        ]);
        $table->addColumn('body', 'string', [
            'limit' => 30,
            'null' => false,
        ]);
        $table->addColumn('img_path', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('retweet_post_id', 'integer');
        $table
        ->addColumn('user_id', 'integer')
        ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION']);
        $table->addColumn('created', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => true,
        ]);
        $table->addColumn('modified', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'update' => 'CURRENT_TIMESTAMP',
            'null' => true,
        ]);
        $table->addColumn('deleted', 'timestamp', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }

    public function down()
    {

    }
}
