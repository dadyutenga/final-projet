<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveUserIdFromPayments extends Migration
{
    public function up()
    {
        // Drop the foreign key constraint for user_id first
        try {
            $this->forge->dropForeignKey('payments', 'payments_user_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist or have a different name
            log_message('info', 'Foreign key constraint for user_id in payments not found or already dropped: ' . $e->getMessage());
        }

        // Drop any index on user_id
        try {
            $this->forge->dropKey('payments', 'user_id');
        } catch (\Exception $e) {
            log_message('info', 'Index for user_id in payments not found: ' . $e->getMessage());
        }

        // Drop the user_id column
        $this->forge->dropColumn('payments', 'user_id');

        log_message('info', 'Successfully removed user_id from payments table');
    }

    public function down()
    {
        // Add back the user_id column
        $fields = [
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'reservation_id'
            ]
        ];

        $this->forge->addColumn('payments', $fields);

        // Add back foreign key constraint for user_id
        try {
            $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'SET NULL');
        } catch (\Exception $e) {
            log_message('info', 'Could not add foreign key constraint for user_id: ' . $e->getMessage());
        }

        // Add back index for better performance
        try {
            $this->forge->addKey('user_id', false, 'idx_payments_user_id');
        } catch (\Exception $e) {
            log_message('info', 'Could not add index for user_id: ' . $e->getMessage());
        }

        log_message('info', 'Successfully added back user_id to payments table');
    }
}