<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRatingConstraintToReviews extends Migration
{
    public function up()
    {
        // Add check constraint for rating (1-5)
        $this->db->query('ALTER TABLE reviews ADD CONSTRAINT chk_rating CHECK (rating BETWEEN 1 AND 5)');
    }

    public function down()
    {
        // Drop the check constraint
        $this->db->query('ALTER TABLE reviews DROP CONSTRAINT chk_rating');
    }
}
