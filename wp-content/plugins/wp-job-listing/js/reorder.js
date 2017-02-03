jQuery(document).ready(function($) {
    
        
    var sortList = $( 'ul#custom-type-list' );
    var animation = $( '#loading-animation');
    var pageTitle = $( 'div h2' ); //Remember to give this div h2 a better id later
     
    sortList.sortable({
        
        update: function( event, ui ) {
            animation.show();
            
            $.ajax({                       //jQuery AJAX
               url: ajaxurl,               //url built in WP
               type: 'POST',
               dataType: 'json',           //good to be specific about the dataType
               data: {
                   action: 'save_post',   //this indicates what action happens when the AJAX comes through. We'll have to define the 'save_post' ourselves later.
                   order: sortList.sortable( 'toArray' ).toString(),            //custom variable we create to pass the ID's in the order in which they are
                   security: WP_JOB_LISTING.security
                   
               },
               success: function( response ) {
                   $( 'div#message' ).remove();
                   animation.hide();
                   pageTitle.after( '<div id="message" class="updated"><p> '+ WP_JOB_LISTING.success +' </p> </div>' );
               },
               error: function( error ) {
                   $( 'div#message' ).remove();
                   animation.hide();
                   pageTitle.after( '<div id="message" class="error"><p> '+ WP_JOB_LISTING.failure +' </p> </div>' );
               }
            });
        }
        
    });
    
});