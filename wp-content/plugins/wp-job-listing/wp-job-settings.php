<?php

function ck8_add_submenu_page() {
    
    add_submenu_page( 
        'edit.php?post_type=job', 
        __( 'Reorder Jobs'), 
        __( 'Reorder Jobs'), 
        'manage_options', 
        'reorder_jobs', 
        'reorder_admin_jobs_callback' 
        );
    
}

add_action( 'admin_menu', 'ck8_add_submenu_page' );

function reorder_admin_jobs_callback() {

    
    $args = array(
        'post_type'         => 'job',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'post_status'       => 'publish',
        'no_found_rows'     => true,
        'update_post_term_cache' => false,
        'post_per_page'     => 50
    );
    
    $job_listing = new WP_Query( $args );
    
    ?>
    
    <div id="job-sort" class="wrap">
        <div id="icon-job-admin" class="icon32"><br></div>
        <h2><?php _e('Sort Job Positions', 'wp-job-listing'); ?> 
        <img src="<?php echo esc_url(admin_url() . '/images/loading.gif' ); ?>" id="loading-animation"> 
        </h2> 
        
        <?php if ( $job_listing->have_posts() ) : ?>
            <p><?php _e('<strong>Note:</strong> this only affects the jobs listed using the shortcode function.'); ?></p>
            <ul id="custom-type-list">
                <?php while ($job_listing-> have_posts() ) : $job_listing->the_post(); ?>
                
                <li id="<?php esc_attr( the_id() ); ?>"> <?php esc_html( the_title() ); ?> </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p> <?php _e( 'You have no Jobs to sort.', 'wp-job-listing' ); ?> </p>
        <?php endif; ?>    
        
    </div>
    
    <?php
    
}

function ck8_save_reorder() {
    
    if( ! check_ajax_referer( 'wp-job-order', 'security' ) ) {
        return wp_send_json_error( 'Invalid nonce' );
    }
    
    if( ! current_user_can( 'manage_options' ) ) {   //manage_options is a WP capability. More in the codex
        return wp_send_json_error( 'You cannot manage options' );
    }
    
    $order = $_POST['order'];        //the order parameter of this super global variable was defined in reorder.js (line 19)
    $counter = 0;
    
    foreach( $order as $item_id ) {
        
        $post = array (
            'ID' => (int)$item_id,              //wp_update_post expects an integer, thus the (int)
            'menu_order' => $counter,           //this counter which is set to 0 in line 70, is assigned to the first item_id
        );
        wp_update_post( $post );                //and saved
        
        $counter++;                             //then the second item_id will get an incremented number, when the loop runs again. This ensure the order is saved.
    }
    wp_send_json_success( 'Order saved' );
    
}

add_action( 'wp_ajax_save_sort', 'ck8_save_reorder' );   //Dynamic hook: in the next part slug wp_ajax_ you use the action you called in reorder.js (line 18)