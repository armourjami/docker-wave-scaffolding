<?php

use Phinx\Migration\AbstractMigration;

class InitMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('auth_token', ['id' => false, 'primary_key' => ['auth_token_id']])
            ->addColumn('auth_token_id', 'integer', ['limit' => 10, 'signed' => false, 'null' => false, 'identity' => true])
            ->addColumn('user_id', 'integer', ['length' => 10, 'null' => false, 'signed' => false])
            ->addColumn('expires', 'datetime', ['null' => false])
            ->addColumn('revoked', 'boolean', ['default' => false])
            ->addColumn('issued', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('access_token', 'string', ['null' => false])
            ->create();

        $this->table('refresh_token', ['id' => false, 'primary_key' => ['refresh_token_id']])
            ->addColumn('refresh_token_id', 'integer', ['limit' => 10, 'signed' => false, 'null' => false, 'identity' => true])
            ->addColumn('user_id', 'integer', ['length' => 10, 'null' => false, 'signed' => false])
            ->addColumn('expires', 'datetime', ['null' => false])
            ->addColumn('revoked', 'boolean', ['default' => false])
            ->addColumn('issued', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('refresh_token', 'string', ['null' => false])
            ->create();

        $this->table('auth_attempt', ['id' => false, 'primary_key' => ['auth_attempt_id']])
            ->addColumn('auth_attempt_id', 'integer', ['limit' => 10, 'signed' => false, 'null' => false, 'identity' => true])
            ->addColumn('user_id', 'integer', ['length' => 10, 'null' => false])
            ->addColumn('source_ip', 'string', ['null' => false])
            ->addColumn('successful', 'boolean', ['default' => 0, 'null' => false])
            ->addColumn('timestamp', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();

        $this->table('auth_email', ['id' => false, 'primary_key' => ['auth_email_id']])
            ->addColumn('auth_email_id', 'integer', ['limit' => 10, 'signed' => false, 'null' => false, 'identity' => true])
            ->addColumn('user_id', 'integer', ['length' => 10, 'null' => false])
            ->addColumn('password', 'string', ['null' => false])
            ->addColumn('created', 'datetime', ['null' => false, 'default' => null])
            ->addColumn('updated', 'datetime', ['null' => false, 'default' => null])
            ->create();

        $this->table('user', ['id' => false, 'primary_key' => ['user_id']])
            ->addColumn('user_id', 'integer', ['limit' => 10, 'signed' => false, 'null' => false, 'identity' => true])
            ->addColumn('first_name', 'string', ['null' => false])
            ->addColumn('last_name', 'string', ['null' => false])
            ->addColumn('email', 'string', ['null' => false])
            ->addColumn('status', 'enum', ['values' => ['enabled', 'disabled']])
            ->create();
    }
}
