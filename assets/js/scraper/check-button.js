(function($){

    'use strict';

    var CCS = window.CCS || {};

    CCS.CheckButton = {
        buttonSelector: '.ccs-check-button .button',

        /**
         * Initalize the script.
         * 
         * @return {void
         */
        init: function() {
            this.events();
        },

        /**
         * Events.
         * This function/method is responsible for handling the events.
         * 
         * @return {void}
         */
        events: function() {
            const self = this;

            $( this.buttonSelector ).on( 'click', function(e) {
                e.preventDefault();

                const $el = $( this );

                self.loadPage( $el );
            } );
        },

        /**
         * Load the page content.
         * This function/method will perform a AJAX request to the server to get the page content.
         * 
         * @param {jQuery} $el
         * 
         * @return {void}
         */
        loadPage: function( $el ) {
            const postID = $el.data( 'post-id' );
            const pageUrlToLoad = $el.data( 'page-url' );
            const nonce = $el.data( 'nonce' );

            if ( ! pageUrlToLoad ) {
                return false;
            }
            
            $.ajax({
                url: wp.ajax.settings.url,
                type: 'post',
                data: {
                    action: 'ccs_check_button',
                    post_id: postID,
                    page_url: pageUrlToLoad,
                    nonce: nonce
                },
                success: function( response ) {
                    if ( response.success ) {
                        if ( typeof response.data.page_html_content_without_tags !== 'undefined' && typeof tinymce !== 'undefined' ) {
                            tinymce.get('content').setContent( response.data.page_html_content_without_tags );
                        }
                    }
                },
                error: function( response ) {
                    console.log( response );
                }
            });
        },
    }

    $( document ).ready(function(){
        CCS.CheckButton.init();
    })
})(jQuery);


