<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'payment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'reservation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['credit_card', 'debit_card', 'cash', 'online'],
                'null' => false,
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed', 'failed'],
                'default' => 'pending',
            ],
            'payment_date' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('payment_id', true);
        $this->forge->addForeignKey('reservation_id', 'reservations', 'reservation_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}
