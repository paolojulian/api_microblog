<?php
use Migrations\AbstractMigration;

class Create extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('is_activated', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('deleted', 'timestamp', [
            'default' => null,
            'null' => true,
        ]);
        $table->save();
    }
}
