<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePaymentsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('payments');
        
        $table->addColumn('user_id', 'integer', ['null' => false])
              ->addColumn('transaction_id', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false])
              ->addColumn('payment_method', 'string', ['limit' => 50, 'null' => false])
              ->addColumn('status', 'string', ['limit' => 50, 'null' => false])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
              ->create();
    }
}
