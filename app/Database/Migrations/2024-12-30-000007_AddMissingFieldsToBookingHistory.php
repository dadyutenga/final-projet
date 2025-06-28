<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsToBookingHistory extends Migration
{
    public function up()
    {
        // Add the missing fields to booking_history table
        $fields = [
            'check_in_date' => [
                'type' => 'DATE',
                'null' => false,
                'after' => 'person_phone'
            ],
            'check_out_date' => [
                'type' => 'DATE',
                'null' => false,
                'after' => 'check_in_date'
            ],
            'total_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
                'after' => 'check_out_date'
            ],
            'guests_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'default' => 1,
                'after' => 'total_price'
            ],
            'guest_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'guests_count'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['confirmed', 'checked_in', 'completed', 'cancelled'],
                'null' => false,
                'default' => 'confirmed',
                'after' => 'guest_email'
            ],
            'cancellation_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status'
            ],
            'cancelled_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'cancellation_reason'
            ]
        ];
        
        $this->forge->addColumn('booking_history', $fields);
        
        // Add indexes for better performance
        $this->forge->addKey(['check_in_date', 'check_out_date'], false, 'idx_booking_dates');
        $this->forge->addKey(['status', 'check_in_date'], false, 'idx_status_checkin');
        $this->forge->addKey('guest_email', false, 'idx_guest_email');
        $this->forge->addKey('total_price', false, 'idx_total_price');
        $this->forge->addKey(['hotel_id', 'status', 'check_in_date'], false, 'idx_hotel_status_date');
    }

    public function down()
    {
        // Drop indexes first
        $this->forge->dropKey('booking_history', 'idx_booking_dates');
        $this->forge->dropKey('booking_history', 'idx_status_checkin');
        $this->forge->dropKey('booking_history', 'idx_guest_email');
        $this->forge->dropKey('booking_history', 'idx_total_price');
        $this->forge->dropKey('booking_history', 'idx_hotel_status_date');
        
        // Drop the added columns
        $this->forge->dropColumn('booking_history', [
            'check_in_date',
            'check_out_date', 
            'total_price',
            'guests_count',
            'guest_email',
            'status',
            'cancellation_reason',
            'cancelled_at'
        ]);
    }
}