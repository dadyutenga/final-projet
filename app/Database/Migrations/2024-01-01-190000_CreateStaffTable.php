<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'staff_id' => [
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
            'manager_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'role' => [
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
            'hire_date' => [
                'type' => 'DATE',
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

        $this->forge->addKey('staff_id', true);
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('manager_id', 'managers', 'manager_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('staff');
    }

    public function down()
    {
        $this->forge->dropTable('staff');
    }
}
