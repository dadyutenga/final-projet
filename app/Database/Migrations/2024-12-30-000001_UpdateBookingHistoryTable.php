<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateBookingHistoryTable extends Migration
{
    public function up()
    {
        // Drop foreign key constraints first
        try {
            $this->forge->dropForeignKey('booking_history', 'booking_history_reservation_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist
        }
        
        try {
            $this->forge->dropForeignKey('booking_history', 'booking_history_user_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist
        }
        
        // Drop old columns
        $this->forge->dropColumn('booking_history', ['reservation_id', 'user_id', 'action']);
        
        // Add new columns
        $fields = [
            'booking_ticket_no' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'after' => 'history_id'
            ],
            'room_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'after' => 'booking_ticket_no'
            ],
            'person_full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'after' => 'hotel_id'
            ],
            'person_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'after' => 'person_full_name'
            ]
        ];
        
        $this->forge->addColumn('booking_history', $fields);
        
        // Add foreign key for room_id
        $this->forge->addForeignKey('room_id', 'rooms', 'room_id', 'CASCADE', 'CASCADE');
        
        // Add indexes for better performance
        $this->forge->addKey('booking_ticket_no');
        $this->forge->addKey('person_phone');
        $this->forge->addKey(['hotel_id', 'action_date']);
    }

    public function down()
    {
        // Drop foreign key and indexes
        $this->forge->dropForeignKey('booking_history', 'booking_history_room_id_foreign');
        $this->forge->dropKey('booking_history', 'booking_ticket_no');
        $this->forge->dropKey('booking_history', 'person_phone');
        $this->forge->dropKey('booking_history', 'booking_history_hotel_id_action_date');
        
        // Drop new columns
        $this->forge->dropColumn('booking_history', ['booking_ticket_no', 'room_id', 'person_full_name', 'person_phone']);
        
        // Add back old columns
        $fields = [
            'reservation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'history_id'
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'reservation_id'
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['created', 'updated', 'cancelled', 'completed'],
                'null' => false,
                'after' => 'hotel_id'
            ]
        ];
        
        $this->forge->addColumn('booking_history', $fields);
        
        // Add back foreign keys
        $this->forge->addForeignKey('reservation_id', 'reservations', 'reservation_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'SET NULL');
    }
}