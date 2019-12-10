<?php
use Migrations\AbstractMigration;

class AddActivationKey extends AbstractMigration
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
        $table->addColumn('activation_key', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->save();
    }
}
