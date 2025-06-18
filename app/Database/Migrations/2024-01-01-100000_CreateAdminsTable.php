<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'admin_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
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
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('admin_id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('admins');
    }

    public function down()
    {
        $this->forge->dropTable('admins');
    }
}
