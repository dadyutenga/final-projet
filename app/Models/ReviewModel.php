<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'review_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'hotel_id',
        'rating',
        'comment'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id'  => 'permit_empty|is_natural_no_zero',
        'hotel_id' => 'permit_empty|is_natural_no_zero',
        'rating'   => 'required|integer|greater_than[0]|less_than[6]',
        'comment'  => 'permit_empty|max_length[1000]'
    ];
    protected $validationMessages   = [
        'rating' => [
            'required'     => 'Rating is required',
            'integer'      => 'Rating must be a number',
            'greater_than' => 'Rating must be between 1 and 5',
            'less_than'    => 'Rating must be between 1 and 5'
        ],
        'comment' => [
            'max_length'   => 'Comment cannot exceed 1000 characters'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['validateUserHasReservation'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Validate that user has completed reservation at hotel
     */
    protected function validateUserHasReservation(array $data)
    {
        if (isset($data['data']['user_id']) && isset($data['data']['hotel_id'])) {
            $reservationModel = new \App\Models\ReservationModel();
            $hasReservation = $reservationModel->where('user_id', $data['data']['user_id'])
                                              ->where('hotel_id', $data['data']['hotel_id'])
                                              ->where('status', 'completed')
                                              ->countAllResults() > 0;

            if (!$hasReservation) {
                throw new \RuntimeException('You can only review hotels where you have completed a stay');
            }
        }

        return $data;
    }

    /**
     * Get review with user and hotel details
     */
    public function getReviewWithDetails($reviewId)
    {
        return $this->select('reviews.*,
                            users.full_name as user_name,
                            users.email as user_email,
                            hotels.name as hotel_name,
                            hotels.city as hotel_city,
                            hotels.country as hotel_country')
                    ->join('users', 'users.user_id = reviews.user_id', 'left')
                    ->join('hotels', 'hotels.hotel_id = reviews.hotel_id', 'left')
                    ->where('reviews.review_id', $reviewId)
                    ->first();
    }

    /**
     * Get reviews by hotel
     */
    public function getReviewsByHotel($hotelId, $rating = null, $limit = null, $offset = null)
    {
        $builder = $this->select('reviews.*,
                                users.full_name as user_name')
                        ->join('users', 'users.user_id = reviews.user_id', 'left')
                        ->where('reviews.hotel_id', $hotelId)
                        ->orderBy('reviews.created_at', 'DESC');

        if ($rating) {
            $builder->where('reviews.rating', $rating);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get reviews by user
     */
    public function getReviewsByUser($userId, $limit = null, $offset = null)
    {
        $builder = $this->select('reviews.*,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city,
                                hotels.country as hotel_country')
                        ->join('hotels', 'hotels.hotel_id = reviews.hotel_id', 'left')
                        ->where('reviews.user_id', $userId)
                        ->orderBy('reviews.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get hotel average rating
     */
    public function getHotelAverageRating($hotelId)
    {
        $result = $this->select('AVG(rating) as average_rating, COUNT(*) as total_reviews')
                       ->where('hotel_id', $hotelId)
                       ->first();

        return [
            'average_rating' => $result ? round($result['average_rating'], 2) : 0,
            'total_reviews' => $result ? $result['total_reviews'] : 0
        ];
    }

    /**
     * Get hotel rating distribution
     */
    public function getHotelRatingDistribution($hotelId)
    {
        $results = $this->select('rating, COUNT(*) as count')
                        ->where('hotel_id', $hotelId)
                        ->groupBy('rating')
                        ->orderBy('rating', 'DESC')
                        ->findAll();

        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];

        foreach ($results as $result) {
            $distribution[$result['rating']] = $result['count'];
        }

        return $distribution;
    }

    /**
     * Get recent reviews
     */
    public function getRecentReviews($hotelId = null, $limit = 10)
    {
        $builder = $this->select('reviews.*,
                                users.full_name as user_name,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city')
                        ->join('users', 'users.user_id = reviews.user_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = reviews.hotel_id', 'left')
                        ->orderBy('reviews.created_at', 'DESC')
                        ->limit($limit);

        if ($hotelId) {
            $builder->where('reviews.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Get top rated hotels
     */
    public function getTopRatedHotels($limit = 10, $minReviews = 5)
    {
        return $this->select('hotels.*,
                            AVG(reviews.rating) as average_rating,
                            COUNT(reviews.review_id) as total_reviews')
                    ->join('hotels', 'hotels.hotel_id = reviews.hotel_id')
                    ->groupBy('reviews.hotel_id')
                    ->having('total_reviews >=', $minReviews)
                    ->orderBy('average_rating', 'DESC')
                    ->orderBy('total_reviews', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Check if user can review hotel
     */
    public function canUserReviewHotel($userId, $hotelId)
    {
        // Check if user has completed reservation at hotel
        $reservationModel = new \App\Models\ReservationModel();
        $hasCompletedStay = $reservationModel->where('user_id', $userId)
                                            ->where('hotel_id', $hotelId)
                                            ->where('status', 'completed')
                                            ->countAllResults() > 0;

        if (!$hasCompletedStay) {
            return false;
        }

        // Check if user has already reviewed this hotel
        $existingReview = $this->where('user_id', $userId)
                              ->where('hotel_id', $hotelId)
                              ->first();

        return !$existingReview;
    }

    /**
     * Get user's review for hotel
     */
    public function getUserHotelReview($userId, $hotelId)
    {
        return $this->where('user_id', $userId)
                    ->where('hotel_id', $hotelId)
                    ->first();
    }

    /**
     * Get reviews with rating filter
     */
    public function getReviewsByRating($rating, $hotelId = null, $limit = null, $offset = null)
    {
        $builder = $this->select('reviews.*,
                                users.full_name as user_name,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city')
                        ->join('users', 'users.user_id = reviews.user_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = reviews.hotel_id', 'left')
                        ->where('reviews.rating', $rating)
                        ->orderBy('reviews.created_at', 'DESC');

        if ($hotelId) {
            $builder->where('reviews.hotel_id', $hotelId);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Search reviews
     */
    public function searchReviews($searchTerm, $hotelId = null, $rating = null, $limit = 20, $offset = 0)
    {
        $builder = $this->select('reviews.*,
                                users.full_name as user_name,
                                hotels.name as hotel_name,
                                hotels.city as hotel_city')
                        ->join('users', 'users.user_id = reviews.user_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = reviews.hotel_id', 'left');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('reviews.comment', $searchTerm)
                   ->orLike('users.full_name', $searchTerm)
                   ->orLike('hotels.name', $searchTerm)
                   ->groupEnd();
        }

        if ($hotelId) {
            $builder->where('reviews.hotel_id', $hotelId);
        }

        if ($rating) {
            $builder->where('reviews.rating', $rating);
        }

        return $builder->orderBy('reviews.created_at', 'DESC')
                      ->limit($limit, $offset)
                      ->findAll();
    }

    /**
     * Get review statistics for hotel
     */
    public function getHotelReviewStatistics($hotelId)
    {
        $stats = $this->getHotelAverageRating($hotelId);
        $distribution = $this->getHotelRatingDistribution($hotelId);

        // Get recent reviews count (last 30 days)
        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
        $recentCount = $this->where('hotel_id', $hotelId)
                           ->where('created_at >=', $thirtyDaysAgo)
                           ->countAllResults();

        return [
            'average_rating' => $stats['average_rating'],
            'total_reviews' => $stats['total_reviews'],
            'rating_distribution' => $distribution,
            'recent_reviews_count' => $recentCount
        ];
    }

    /**
     * Get monthly review count
     */
    public function getMonthlyReviewCount($hotelId = null, $year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $builder = $this->select('MONTH(created_at) as month,
                                YEAR(created_at) as year,
                                COUNT(*) as review_count,
                                AVG(rating) as average_rating')
                        ->where('YEAR(created_at)', $year)
                        ->groupBy('YEAR(created_at), MONTH(created_at)')
                        ->orderBy('month', 'ASC');

        if ($hotelId) {
            $builder->where('hotel_id', $hotelId);
        }

        return $builder->findAll();
    }

    /**
     * Delete user review for hotel
     */
    public function deleteUserReview($userId, $hotelId)
    {
        return $this->where('user_id', $userId)
                    ->where('hotel_id', $hotelId)
                    ->delete();
    }

    /**
     * Update user review
     */
    public function updateUserReview($userId, $hotelId, $data)
    {
        return $this->where('user_id', $userId)
                    ->where('hotel_id', $hotelId)
                    ->set($data)
                    ->update();
    }

    /**
     * Get hotels without reviews
     */
    public function getHotelsWithoutReviews()
    {
        return $this->db->table('hotels')
                       ->select('hotels.*')
                       ->join('reviews', 'reviews.hotel_id = hotels.hotel_id', 'left')
                       ->where('reviews.hotel_id IS NULL')
                       ->orderBy('hotels.created_at', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Get most helpful reviews (with most engagement)
     */
    public function getMostHelpfulReviews($hotelId = null, $limit = 10)
    {
        $builder = $this->select('reviews.*,
                                users.full_name as user_name,
                                hotels.name as hotel_name,
                                CHAR_LENGTH(reviews.comment) as comment_length')
                        ->join('users', 'users.user_id = reviews.user_id', 'left')
                        ->join('hotels', 'hotels.hotel_id = reviews.hotel_id', 'left')
                        ->where('reviews.comment IS NOT NULL')
                        ->where('CHAR_LENGTH(reviews.comment) >', 50)
                        ->orderBy('comment_length', 'DESC')
                        ->orderBy('reviews.rating', 'DESC')
                        ->limit($limit);

        if ($hotelId) {
            $builder->where('reviews.hotel_id', $hotelId);
        }

        return $builder->findAll();
    }
}
