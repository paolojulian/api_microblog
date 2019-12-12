<?php
use Migrations\AbstractMigration;

class ChangeRetweetPostIdToNull extends AbstractMigration
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
        $table->changeColumn('retweet_post_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->save();
    }
}
