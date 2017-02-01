<?php

function ck8_add_submenu_page() {
    
    add_submenu_page( 
        'edit.php?post_type=job', 
        'Reorder Jobs', 
        'Reorder Jobs', 
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
            <p> <?php _e( 'You hae no Jobs to sort.', 'wp-job-listing' ); ?> </p>
        <?php endif; ?>    
        
    </div>
    
    <?php
    
    
}