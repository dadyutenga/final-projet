<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InsertTestAdminData extends Migration
{
    public function up()
    {
        // Insert test admin data
        $data = [
            [
                'username' => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'email' => 'admin@hotel.com',
                'full_name' => 'System Administrator',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'superadmin',
                'password_hash' => password_hash('super123', PASSWORD_DEFAULT),
                'email' => 'superadmin@hotel.com',
                'full_name' => 'Super Administrator',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'testadmin',
                'password_hash' => password_hash('test123', PASSWORD_DEFAULT),
                'email' => 'test@hotel.com',
                'full_name' => 'Test Administrator',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert the data
        $this->db->table('admins')->insertBatch($data);
    }

    public function down()
    {
        // Remove test admin data
        $this->db->table('admins')->whereIn('username', ['admin', 'superadmin', 'testadmin'])->delete();
    }
}
