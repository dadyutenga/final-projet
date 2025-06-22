<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffTableWithAuthFields extends Migration
{
    public function up()
    {
        // Drop the existing table if it exists (this will delete all data)
        $this->forge->dropTable('staff', true);

        // Create the new table with the updated fields
        $fields = [
            'staff_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'hotel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'manager_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'hire_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'username' => [  // New field for auth
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'password_hash' => [  // New field for secure password storage
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('staff_id');
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('manager_id', 'managers', 'manager_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('staff');
    }

    public function down()
    {
        // Revert the migration by dropping the table
        $this->forge->dropTable('staff');
    }
} 