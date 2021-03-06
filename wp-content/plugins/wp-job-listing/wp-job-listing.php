<?php

/*
Plugin Name: WP Job Listing
Plugin URI:
Description: Add simple job listing section.
Author: Cesar Martinez
Author URI: http://cesarsresume.net
Version: 0.1
License: GPLv2
*/

//Exit if accessed directly
if ( ! defined('ABSPATH') ) 
{
    exit;
}

require_once (plugin_dir_path(__FILE__) . 'wp-job-cpt.php');
require_once (plugin_dir_path(__FILE__) . 'wp-job-fields.php');
require_once (plugin_dir_path(__FILE__) . 'wp-job-settings.php');
require_once (plugin_dir_path(__FILE__) . 'wp-job-shortcode.php');


function ck8_admin_enqueue_scripts() {
    global $pagenow, $typenow;
    
    if ( $typenow == 'job' ) {
        
        wp_enqueue_style( 'ck8-admin-css', plugins_url( 'css/admin-jobs.css', __FILE__ ) );
        
    }
    
    if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'job' ) {
        
        
        wp_enqueue_script( 'ck8-job-js', plugins_url( 'js/admin-jobs.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), '20170124', true );
        wp_enqueue_script( 'ck8-custom-quicktags', plugins_url( 'js/ck8-quicktags.js', __FILE__ ), array( 'quicktags' ), '20170105', true );
		wp_enqueue_style( 'jquery-style', plugins_url( 'css/jquery-ui.css', __FILE__ ) );
        
    }
    
    if ( $pagenow == 'edit.php' && $typenow == 'job' ) {
    
    wp_enqueue_script( 'reorder-js', plugins_url( 'js/reorder.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), '20170128', true );
    wp_localize_script( 'reorder-js', 'WP_JOB_LISTING', array(
            'security' => wp_create_nonce( 'wp-job-order' ),
            'success' => __( 'Order saved.' ),
            'failure' => __( 'Could not save the sort order, or you lack proper permission.' )
        
        ) );
    
    }
}

add_action( 'admin_enqueue_scripts', 'ck8_admin_enqueue_scripts' );
