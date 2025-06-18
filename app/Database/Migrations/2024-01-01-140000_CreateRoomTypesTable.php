<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoomTypesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'room_type_id' => [
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
            'type_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'base_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'capacity' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
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

        $this->forge->addKey('room_type_id', true);
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('room_types');
    }

    public function down()
    {
        $this->forge->dropTable('room_types');
    }
}
