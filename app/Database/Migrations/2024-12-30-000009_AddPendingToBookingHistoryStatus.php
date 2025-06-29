<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPendingToBookingHistoryStatus extends Migration
{
    public function up()
    {
        // Modify the status ENUM to include 'pending' 
        // MySQL requires us to redefine the entire ENUM with all values
        $this->db->query("ALTER TABLE booking_history MODIFY COLUMN status ENUM('pending', 'confirmed', 'checked_in', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
        
        log_message('info', 'Successfully added pending status to booking_history table');
    }

    public function down()
    {
        // Revert back to original ENUM values without 'pending'
        // First, update any existing 'pending' status to 'confirmed' to avoid constraint violations
        $this->db->query("UPDATE booking_history SET status = 'confirmed' WHERE status = 'pending'");
        
        // Then modify the ENUM back to original values
        $this->db->query("ALTER TABLE booking_history MODIFY COLUMN status ENUM('confirmed', 'checked_in', 'completed', 'cancelled') NOT NULL DEFAULT 'confirmed'");
        
        log_message('info', 'Successfully removed pending status from booking_history table');
    }
}