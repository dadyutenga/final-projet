<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'review_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
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
            'rating' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => true,
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

        $this->forge->addKey('review_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('hotel_id', 'hotels', 'hotel_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('reviews');
    }

    public function down()
    {
        $this->forge->dropTable('reviews');
    }
}
