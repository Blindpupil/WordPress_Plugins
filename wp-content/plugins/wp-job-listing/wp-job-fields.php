<?php

function ck8_add_custom_metabox() {
    add_meta_box(
        'ck8_meta',
        __( 'Job Listing' ),
        'ck8_meta_callback',
        'job',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'ck8_add_custom_metabox');

function ck8_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'ck8_jobs_nonce' );                       //nonce is number used once
	$ck8_stored_meta = get_post_meta( $post->ID ); ?>

	<div>
		<div class="meta-row">
			<div class="meta-th">
				<label for="job-id" class="ck8-row-title"><?php _e( 'Job Id', 'wp-job-listing' ); ?></label>
			</div>
			<div class="meta-td">
				<input type="text" class="ck8-row-content" name="job_id" id="job-id"
				value="<?php if ( ! empty ( $ck8_stored_meta['job_id'] ) ) {
					echo esc_attr( $ck8_stored_meta['job_id'][0] );
				} ?>"/>
			</div>
		</div>
		<div class="meta-row">
			<div class="meta-th">
				<label for="date-listed" class="ck8-row-title"><?php _e( 'Date Listed', 'wp-job-listing' ); ?></label>
			</div>
			<div class="meta-td">
				<input type="text" class="ck8-row-content datepicker" name="date_listed" id="date-listed" value="<?php if ( ! empty ( $ck8_stored_meta['date_listed'] ) ) echo esc_attr( $ck8_stored_meta['date_listed'][0] ); ?>"/>
			</div>
		</div>
		<div class="meta-row">
			<div class="meta-th">
				<label for="application_deadline" class="ck8-row-title"><?php _e( 'Application Deadline', 'wp-job-listing' ) ?></label>
			</div>
			<div class="meta-td">
				<input type="text" class="ck8-row-content datepicker" name="application_deadline" id="application_deadline" value="<?php if ( ! empty ( $ck8_stored_meta['application_deadline'] ) ) echo esc_attr( $ck8_stored_meta['application_deadline'][0] ); ?>"/>
			</div>
		</div>
		<div class="meta">
			<div class="meta-th">
				<span><?php _e( 'Principle Duties', 'wp-job-listing' ) ?></span>
			</div>
		</div>
		<div class="meta-editor"></div>
		<?php
		$content = get_post_meta( $post->ID, 'principle_duties', true );
		$editor = 'principle_duties';
		$settings = array(
			'textarea_rows' => 8,
			'media_buttons' => false,
		);
		wp_editor( $content, $editor, $settings); ?>
		</div>
		<div class="meta-row">
	        <div class="meta-th">
	          <label for="minimum-requirements" class="ck8-row-title"><?php _e( 'Minimum Requirements', 'wp-job-listing' ) ?></label>
	        </div>
	        <div class="meta-td">
	          <textarea name="minimum_requirements" class="ck8-textarea" id="minimum-requirements"><?php
	          if ( ! empty ( $ck8_stored_meta['minimum_requirements'] ) ) {
		          echo esc_attr( $ck8_stored_meta['minimum_requirements'][0] );
	          }
	          ?>
	          </textarea>
	        </div>
	    </div>
	    <div class="meta-row">
        	<div class="meta-th">
	          <label for="preferred-requirements" class="ck8-row-title"><?php _e( 'Preferred Requirements', 'wp-job-listing' ) ?></label>
	        </div>
	        <div class="meta-td">
	          <textarea name="preferred_requirements" class="ck8-textarea" id="preferred-requirements"><?php
			          if ( ! empty ( $ck8_stored_meta['preferred_requirements'] ) ) {
			            echo esc_attr( $ck8_stored_meta['preferred_requirements'][0] );
			          }
		          ?>
	          </textarea>
	        </div>
	    </div>
	    <div class="meta-row">
	        <div class="meta-th">
	          <label for="relocation-assistance" class="ck8-row-title"><?php _e( 'Relocation Assistance', 'wp-job-listing' ) ?></label>
	        </div>
	        <div class="meta-td">
	          <select name="relocation_assistance" id="relocation-assistance">
		          <option value="Yes" <?php if ( ! empty ( $ck8_stored_meta['relocation_assistance'] ) ) selected( $ck8_stored_meta['relocation_assistance'][0], 'Yes' ); ?>><?php _e( 'Yes', 'wp-job-listing' )?></option>';
		          <option value="No" <?php if ( ! empty ( $ck8_stored_meta['relocation_assistance'] ) ) selected( $ck8_stored_meta['relocation_assistance'][0], 'No' ); ?>><?php _e( 'No', 'wp-job-listing' )?></option>';
	          </select>
	    </div> 
	</div>	
	<?php
}


function ck8_meta_save( $post_id ) {
	// Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'ck8_jobs_nonce' ] ) && wp_verify_nonce( $_POST[ 'ck8_jobs_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    if ( isset( $_POST[ 'job_id' ] ) ) {
    	update_post_meta( $post_id, 'job_id', sanitize_text_field( $_POST[ 'job_id' ] ) );
    }
    if ( isset( $_POST[ 'date_listed' ] ) ) {
    	update_post_meta( $post_id, 'date_listed', sanitize_text_field( $_POST[ 'date_listed' ] ) );
    }
    if ( isset( $_POST[ 'application_deadline' ] ) ) {
    	update_post_meta( $post_id, 'application_deadline', sanitize_text_field( $_POST[ 'application_deadline' ] ) );
    }
    if ( isset( $_POST[ 'principle_duties' ] ) ) {
    	update_post_meta( $post_id, 'principle_duties', sanitize_text_field( $_POST[ 'principle_duties' ] ) );
    }
	if ( isset( $_POST[ 'preferred_requirements' ] ) ) {
		update_post_meta( $post_id, 'preferred_requirements', wp_kses_post( $_POST[ 'preferred_requirements' ] ) );
	}
	if ( isset( $_POST[ 'minimum_requirements' ] ) ) {
		update_post_meta( $post_id, 'minimum_requirements', wp_kses_post( $_POST[ 'minimum_requirements' ] ) );
	}
	if ( isset( $_POST[ 'relocation_assistance' ] ) ) {
		update_post_meta( $post_id, 'relocation_assistance', sanitize_text_field( $_POST[ 'relocation_assistance' ] ) );
	}
}
add_action( 'save_post', 'ck8_meta_save' );