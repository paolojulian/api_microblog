<?php
use Migrations\AbstractMigration;

class PostsBodyLimitChange extends AbstractMigration
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
        $table->changeColumn('body', 'string', [
            'limit' => 140,
            'null' => false,
        ])->save();
    }
}
