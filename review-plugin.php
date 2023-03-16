<?php
/**
 * Plugin Name: Raketech Review Plugin Test
 */

// Enqueue plugin styles and scripts
add_action('wp_enqueue_scripts', 'raketech_reviews_plugin_scripts');
function raketech_reviews_plugin_scripts() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
    wp_enqueue_style('raketech-reviews-plugin-styles', plugin_dir_url(__FILE__) . 'style.css');
}

// Generate the stars
function generate_rating_stars($rating) {
    $full_stars = floor($rating);
    $half_stars = ceil($rating - $full_stars);
    $empty_stars = 5 - $full_stars - $half_stars;
    
    $rating_stars = '';
    for ($i = 0; $i < $full_stars; $i++) {
      $rating_stars .= '<i class="fas fa-star"></i>';
    }
    for ($i = 0; $i < $half_stars; $i++) {
      $rating_stars .= '<i class="fas fa-star-half-alt"></i>';
    }
    for ($i = 0; $i < $empty_stars; $i++) {
      $rating_stars .= '<i class="far fa-star"></i>';
    }
    
    return $rating_stars;
}
  

// Add shortcode to display data on front-end
add_shortcode('raketech_reviews', 'raketech_reviews_shortcode');
function raketech_reviews_shortcode() {
    $data = json_decode(file_get_contents(plugin_dir_path(__FILE__) . 'data.json'), true);

    // Filter data to only include reviews under key 575
    $reviews = array_filter($data['toplists']['575'], function ($item) {
        return isset($item['brand_id']) && isset($item['position']);
    });

    // Sort reviews by position
    usort($reviews, function ($a, $b) {
        return $a['position'] <=> $b['position'];
    });

    $output = '<div class="raketech-reviews-wrapper">';
    $output .= '<div class="raketech-reviews-labels">';
    $output .= '<span class = "flow-center">Casino</span>';
    $output .= '<span class = "flow-center">Bonus</span>';
    $output .= '<span class = "flow-center">Features</span>';
    $output .= '<span class = "flow-center">Play</span>';
    $output .= '</div>';
    foreach ($reviews as $review) {
        $output .= '<div class="raketech-reviews-item">';
        $output .= '<div class="raketech-reviews-logo">';
        $output .= '<a href="' . home_url('/' . $review['brand_id']) . '">';
        $output .= '<div class="logo-wrapper">';
        $output .= '<img src="' . $review['logo'] . '" alt="Logo">';
        $output .= '<span class="review-text">Review</span>';
        $output .= '</div>';
        $output .= '</a>';
        $output .= '</div>';
        $output .= '<div class="raketech-reviews-rating">';
        $output .= '<span class = "flow-center">' . generate_rating_stars($review['info']['rating']) . '</span>';
        $output .= '</div>';
        $output .= '<div class="raketech-reviews-content">';
        $output .= '<div class="raketech-reviews-features">';
        $output .= '<ul>';
        foreach ($review['info']['features'] as $feature) {
            $output .= '<li>' . $feature . '</li>';
        }
        $output .= '</ul>';
        $output .= '</div>';
        $output .= '<div class="raketech-reviews-button">';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="raketech-reviews-button-wrapper flow-center">';
        $output .= '<a href="' . $review['play_url'] . '" class="raketech-reviews-play-now-button">PLAY NOW</a>';
        $output .= '</div>';
        $output .= '</div>';
    }
    $output .= '</div>';
    return $output;
    
}


