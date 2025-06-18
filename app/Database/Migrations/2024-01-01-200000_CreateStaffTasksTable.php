<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'task_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'staff_id' => [
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
            'hotel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'task_description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'assigned_date' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['assigned', 'in_progress', 'completed', 'overdue'],
                'default' => 'assigned',
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

        $this->forge->addKey('task_id', true);
        $this->forge->addForeignKey('staff_id', 'staff', 'staff_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('manager_id', 'managers', 'manager_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('staff_tasks');
    }

    public function down()
    {
        $this->forge->dropTable('staff_tasks');
    }
}
