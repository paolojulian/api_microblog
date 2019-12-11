<?php
use Migrations\AbstractMigration;

class RenewAddress extends AbstractMigration
{
  /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('address');
        $table->rename('addresses')
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('addresses');
        $table->rename('address')
              ->save();
    }
}
