<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateReservationsTable extends Migration
{
    public function up()
    {
        // Drop foreign key constraints first
        try {
            $this->forge->dropForeignKey('reservations', 'reservations_hotel_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist
        }
        
        try {
            $this->forge->dropForeignKey('reservations', 'reservations_room_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist
        }
        
        // Drop old columns
        $this->forge->dropColumn('reservations', ['hotel_id', 'room_id']);
        
        // Add new column
        $fields = [
            'history_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'reservation_id'
            ]
        ];
        
        $this->forge->addColumn('reservations', $fields);
        
        // Add foreign key constraint
        $this->forge->addForeignKey('history_id', 'booking_history', 'history_id', 'CASCADE', 'SET NULL');
        
        // Add index for better performance
        $this->forge->addKey('history_id');
    }

    public function down()
    {
        // Drop foreign key constraint
        $this->forge->dropForeignKey('reservations', 'reservations_history_id_foreign');
        
        // Drop index
        $this->forge->dropKey('reservations', 'history_id');
        
        // Drop new column
        $this->forge->dropColumn('reservations', 'history_id');
        
        // Add back old columns
        $fields = [
            'hotel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'user_id'
            ],
            'room_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'hotel_id'
            ]
        ];
        
        $this->forge->addColumn('reservations', $fields);
        
        // Add foreign key constraints back
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('room_id', 'rooms', 'room_id', 'CASCADE', 'SET NULL');
    }
}