<?php
/**
 * Plugin Name:       PAMMtech
 * Description:       Custom plugin that integrates an API as a custom post type
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Kaitlyn Breuil 
 */

 /* Start Adding Functions Below this Line */

 defined( 'ABSPATH' ) or die( 'Unauthorized Access!' );

 add_action('init', 'register_activity_cpt');
 
 function register_activity_cpt() {
    
    register_post_type('Activity', [
         'label' => 'Activities',
         'public' => true,
         'show_ui' => true,
         'show_in_menu' => true,
         'show_in_admin_bar' => true,
         'show_in_nav_menus' => true,
         'capability_type' => 'post',
         'menu_icon' => 'dashicons-lightbulb',
     ]);
 }

 add_shortcode( 'external_data', 'get_activity_from_api' );

 function get_activity_from_api() {

    $activities = [];
    $url = 'https://www.boredapi.com/api/activity';
    $arguments = array (
        'method' => 'GET'
    );
    
    $response = wp_remote_get( $url, $arguments );

    $results = json_decode( wp_remote_retrieve_body($response) );
    
    $activities[] = $results;

    // var_dump($activities);

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
    }

    $html = '';
    $html .= '<div>';
    $html .= '<h2>Something to do today:</h2>';


    foreach($activities as $activity) {

        $html .= '<p><strong>Activity:</strong> ' . $activity->activity .  '</p>';
        $html .= '<p><strong>Type:</strong> ' . $activity->type . '</p>';
        $html .= '<p><strong>Participants:</strong> ' . $activity->participants . '</p>';
        $html .= '<p><strong>Price:</strong> ' . $activity->price . '</p>';
        $html .= '<p><strong>Link:</strong> ' . $activity->link . '</p>';
        $html .= '<p><strong>Key:</strong> ' . $activity->key . '</p>';
        $html .= '<p><strong>Accessbility:</strong> ' . $activity->accessibility . ' </p>';    

    }

  
    $html .= '</h1>';
    
    return $html;
    
 }
 /* Stop Adding Functions Below this Line */


?>