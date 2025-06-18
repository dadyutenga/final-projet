<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'reservation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
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
            'room_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'check_in_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'check_out_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'total_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'cancelled', 'completed'],
                'default' => 'pending',
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

        $this->forge->addKey('reservation_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('room_id', 'rooms', 'room_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('reservations');
    }

    public function down()
    {
        $this->forge->dropTable('reservations');
    }
}
