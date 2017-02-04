<?php

function ck8_job_taxonomy_list( $atts, $content = null ) {

	$atts = shortcode_atts(   
	    array(
	        'title' => 'Current job openings in...',
	        
	        ), $atts    
    );
    
    $locations = get_terms( 'location' );
    
    if (! empty($locations) && ! is_wp_error( $locations ) ) {
        
        $displayList = '<div id="job-location-list">';
        $displayList .= '<h5>' . esc_html__( $atts[ 'title' ] ). '</h5>';          //this allows you to escape and localize the variable with the same code
        $displayList .= '<ul>';
            
        foreach( $locations as $location ) {
            
            $displayList .= '<li class="job-location">';
            $displayList .= '<a href="' . esc_url(get_term_link( $location ) ) . '">';
            $displayList .= esc_html__( $location->name ) . '</a></li>';
            
        }
    
        $displayList .= '</ul></div>';
        
    }
    
    return $displayList;

}
add_shortcode( 'job_location_list', 'ck8_job_taxonomy_list');


function ck8_list_job_by_location($atts, $content = null) {
    
    if (! isset($atts['location'] ) ) {
        return '<p class="job-error"> You must provide a location for the shortcode.</p>';
    }
    
    $atts = shortcode_atts( array(
        'title'     => 'Current Job Openings in',
        'count'     => 5,
        'location'  => '',
        'pagination'=> 'on'
    ), $atts );
    
    $pagination = $atts[ 'pagination' ] == 'on' ? false : true; 
    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;  //how many pages needed to fit all jobs
    
    $args = array(
        'post_type'     => 'job',
        'post_Status'   => 'publish',
        'no_found_rows' => $pagination,  //when pagination is off, this is set to true, and it doesn't count the rows
        'posts_per_page'=> $atts[ 'count' ],
        'paged'         => $paged,
        'tax_query'     => array(    //we don't want every job, only those with certain a location which the user can set
            array(                   //tax_query is multidimensional, even though we only need one because we look only in 1 taxonomy
                'taxonomy'  => 'location',
                'field'     => 'slug',
                'terms'     => $atts[ 'location' ],
            ),
        )
    );
    $jobs_by_location = new WP_Query( $args );   //remember WP_Query takes a huge amount of params for all you can query, in the codex

    if ($jobs_by_location -> have_posts() ):
        $location = str_replace('-', ' ', $atts['location'] );
        
        $display_by_location = '<div id= "jobs-by-location">';
        $display_by_location .= '<h5>' . esc_html__( $atts[ 'title' ] ) . '&nbsp' . esc_html__( ucwords( $location) ) . '</h4>';
        $display_by_location .= '<ul>';
        
        while ( $jobs_by_location->have_posts() ) : $jobs_by_location->the_post();
        
        global $post;
        
        $deadline = get_post_meta( get_the_ID(), 'application_deadline', true );
        $title = get_the_title();
        $slug = get_permalink();
        
        $display_by_location .= '<li class="job-listing">';
        $display_by_location .= sprintf( '<a href="%s">%s</a>&nbsp&nbsp', esc_url( $slug ), esc_html__( $title ) );
        $display_by_location .= '<span>' . esc_html__( $deadline ) . '</span>';
        $display_by_location .= '</li>';
        
    endwhile;
    
    $display_by_location .= '</ul></div>';
    
    else:
        $display_by_location = sprintf( __('<p class="job-error">Sorry, no jobs listed in %s where found.</p>'), esc_html__( ucwords( str_replace( '-', ' ', $atts[ 'location' ] ) ) ) );
    
    endif;
    
    wp_reset_postdata();
    
    if ( $jobs_by_location->max_num_pages > 1 && is_page() ) {   //when the maximum number of pages for pagination is more than 1, and it's a page not a post.
        $display_by_location .= '<nav class="prev-next-posts">';
        $display_by_location .= '<div call="nav-previous">';
        $display_by_location .= get_next_posts_link( __( '<span class="meta-nav">&larr;</span> Previous' ), $jobs_by_location->max_num_pages );
        $display_by_location .= '</div>';
        $display_by_location .= '<div call="nav-next">';
        $display_by_location .= get_previous_posts_link( __( '<span class="meta-nav">&rarr;</span> Next' ) );
        $display_by_location .= '</div>';
        $display_by_location .= '</nav>';
    }
    
    return $display_by_location;
}
add_shortcode( 'jobs_by_location', 'ck8_list_job_by_location' );