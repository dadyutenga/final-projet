<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHotelsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'hotel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'admin_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
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

        $this->forge->addKey('hotel_id', true);
        $this->forge->addForeignKey('admin_id', 'admins', 'admin_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('hotels');
    }

    public function down()
    {
        $this->forge->dropTable('hotels');
    }
}
