<?php
use Migrations\AbstractMigration;

class AddForeignKeyRetweetPostId extends AbstractMigration
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
        $table = $this->table('posts');
        $table->addForeignKey('retweet_post_id', 'posts', 'id', [
            'delete' => 'CASCADE',
            'update' => 'NO_ACTION'
        ]);
    }
}
