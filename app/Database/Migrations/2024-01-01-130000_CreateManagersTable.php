<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateManagersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'manager_id' => [
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
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'password_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
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

        $this->forge->addKey('manager_id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('managers');
    }

    public function down()
    {
        $this->forge->dropTable('managers');
    }
}
