<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoomsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'room_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'hotel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'room_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'room_number' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false,
            ],
            'floor' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['available', 'occupied', 'maintenance'],
                'default' => 'available',
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

        $this->forge->addKey('room_id', true);
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('room_type_id', 'room_types', 'room_type_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('rooms');
    }

    public function down()
    {
        $this->forge->dropTable('rooms');
    }
}
