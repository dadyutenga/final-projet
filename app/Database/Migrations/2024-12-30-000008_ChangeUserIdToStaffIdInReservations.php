<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeUserIdToStaffIdInReservations extends Migration
{
    public function up()
    {
        // Drop the existing foreign key constraint for user_id
        try {
            $this->forge->dropForeignKey('reservations', 'reservations_user_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist or have a different name
            log_message('info', 'Foreign key constraint for user_id not found or already dropped: ' . $e->getMessage());
        }

        // Drop the user_id column
        $this->forge->dropColumn('reservations', 'user_id');

        // Add the staff_id column
        $fields = [
            'staff_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'reservation_id'
            ]
        ];

        $this->forge->addColumn('reservations', $fields);

        // Add foreign key constraint for staff_id
        $this->forge->addForeignKey('staff_id', 'staff', 'staff_id', 'CASCADE', 'SET NULL');

        // Add index for better performance
        $this->forge->addKey('staff_id', false, 'idx_reservations_staff_id');

        // Update any existing data if needed (this is optional and depends on your requirements)
        // You might want to set default values or migrate existing user data to staff data
        // For now, we'll leave staff_id as null for existing records
        
        log_message('info', 'Successfully changed user_id to staff_id in reservations table');
    }

    public function down()
    {
        // Drop the foreign key constraint for staff_id
        try {
            $this->forge->dropForeignKey('reservations', 'reservations_staff_id_foreign');
        } catch (\Exception $e) {
            log_message('info', 'Foreign key constraint for staff_id not found: ' . $e->getMessage());
        }

        // Drop the index
        try {
            $this->forge->dropKey('reservations', 'idx_reservations_staff_id');
        } catch (\Exception $e) {
            log_message('info', 'Index for staff_id not found: ' . $e->getMessage());
        }

        // Drop the staff_id column
        $this->forge->dropColumn('reservations', 'staff_id');

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

        $this->forge->addColumn('reservations', $fields);

        // Add back foreign key constraint for user_id
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'SET NULL');

        log_message('info', 'Successfully reverted staff_id back to user_id in reservations table');
    }
}