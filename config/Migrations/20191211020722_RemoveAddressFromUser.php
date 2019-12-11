<?php
use Migrations\AbstractMigration;

class RemoveAddressFromUser extends AbstractMigration
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
        $table->removeColumn('lot');
        $table->removeColumn('block');
        $table->removeColumn('street');
        $table->removeColumn('subdivision');
        $table->removeColumn('city');
        $table->removeColumn('province');
        $table->removeColumn('country');
        $table->removeColumn('zipcode');
        $table->update();
    }
}
