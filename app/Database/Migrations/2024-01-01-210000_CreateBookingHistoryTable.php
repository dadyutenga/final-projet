<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingHistoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'history_id' => [
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
            'hotel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['created', 'updated', 'cancelled', 'completed'],
                'null' => false,
            ],
            'action_date' => [
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

        $this->forge->addKey('history_id', true);
        $this->forge->addForeignKey('reservation_id', 'reservations', 'reservation_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('booking_history');
    }

    public function down()
    {
        $this->forge->dropTable('booking_history');
    }
}
