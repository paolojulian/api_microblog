<?php
use Migrations\AbstractMigration;

class AddUserModel extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('username', 'string', [
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('email', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('mobile', 'integer', [
            'limit' => 50,
            'null' => false,
        ]);
        $table->addColumn('password', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('first_name', 'string', [
            'limit' => 70,
            'null' => false,
        ]);
        $table->addColumn('last_name', 'string', [
            'limit' => 35,
            'null' => false,
        ]);
        $table->addColumn('birthdate', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('lot', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => true,
        ]);
        $table->addColumn('block', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => true,
        ]);
        $table->addColumn('street', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        $table->addColumn('subdivision', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        $table->addColumn('city', 'string', [
            'default' => null,
            'limit' => 190,
            'null' => true,
        ]);
        $table->addColumn('province', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('country', 'string', [
            'limit' => 90,
            'null' => false,
        ]);
        $table->addColumn('zipcode', 'string', [
            'limit' => 18,
            'null' => false,
        ]);
        $table->addColumn('created', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => true,
        ]);
        $table->addColumn('modified', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'update' => 'CURRENT_TIMESTAMP',
            'null' => true,
        ]);
        
        $table->addIndex(['username', 'email'], ['unique' => true]);
        $table->save();
    }
}
