<?php

/*
Plugin Name: Example Plugin
Plugin URI:
Description: Declutters dashboard, adds google analytics link.
Author: Cesar Martinez
Author URI: http://cesarsresume.net
Version: 0.1
License: GPLv2
*/


function ck8_remove_dashboard_widget() 
{
    remove_meta_box('dashboard_primary', 'dashboard', 'postbox-container-1');
}
add_action('wp_dashboard_setup', 'ck8_remove_dashboard_widget');


function ck8_add_google_link()
{
    global $wp_admin_bar;
    
    $wp_admin_bar->add_menu( array(
        'id' => 'google_analytics',
        'title' => 'Google Analytics',
        'href' => 'http://google.com/analytics'
        ) );
}
add_action('wp_before_admin_bar_render', 'ck8_add_google_link');

