
(function($){
    "use strict";

    
    if ( typeof locationData !== "undefined" ) {

        // All locations data
        var allLocations        = locationData.all_locations;

        // Select boxes names that can be used as ids
        var selectIds           = locationData.select_names;

        // number of select boxes
        var selectCount         = parseInt( locationData.select_count );

        // Any parameter related to location boxes
        var locationsInParams   = locationData.locations_in_params;

        // Any text
        var any                 = locationData.any;

        // number of total locations in all locations array
        var locationsCount      = allLocations.length;

        /**
         * Add child of given term id in target select box
         * @param parentID      parent term id
         * @param targetSelect  target select box
         * @param prefix        prefix to add before child name
         * @param all_child     add all child or only first level child
         * @returns {Array}     return array of child locations
         */
        var addChildLocations = function( parentID, targetSelect, prefix, all_child ) {
            var childLocations = [];
            var childLocationsCounter = 0;

            // add 'Any' option to empty select
            if( targetSelect.has('option').length == 0 ){
                var title = targetSelect.data('title');
                targetSelect.append( '<option value="any" selected="selected">' + title + ' ' + any +'</option>' );
                targetSelect.val( 'any' ).trigger( "change" );
            }

            for( var i=0; i < locationsCount; i++ ) {
                var currentLocation = allLocations[i];
                if( parseInt( currentLocation['parent'] ) == parentID ) {
                    targetSelect.append( '<option value="' + currentLocation['slug'] + '">' + prefix + currentLocation['name'] + '</option>' );
                    childLocations[childLocationsCounter] = currentLocation;
                    childLocationsCounter++;
                    if( all_child ) {
                        var currentLocationID = parseInt( currentLocation['term_id'] );
                        addChildLocations ( currentLocationID, targetSelect, prefix + '- ', all_child );
                    }
                }
            }
            return childLocations;
        };


        /**
         * Get term id related to target location
         * @param selectedLocation  target location
         * @returns {number}    term id
         */
        var getRelatedTermID = function ( selectedLocation ){
            var termID = 0;
            var currentLocation;
            // loop through all locations and match selected slug with each one to find the related term id which will be used as parent id later on
            for( var i=0; i < locationsCount; i++ ){
                currentLocation = allLocations[i];
                if( currentLocation['slug'] == selectedLocation ) {
                    return parseInt( currentLocation['term_id'] );
                }
            }
            return termID;
        };


        /* Reset a Select Box */
        /**
         * Does the following things to a target select box.
         * 1. Make it empty.
         * 2. Add an option with "any" as value and "Any + select box title" as it's title.
         * @param targetSelect
         */
        var resetSelect = function ( targetSelect ){
            targetSelect.empty();
            var title = targetSelect.data('title');
            targetSelect.append( '<option value="any" selected="selected">'+ title  + ' ' + any +'</option>' );
            targetSelect.val( 'any' ).trigger( "change" );
        };


        /**
         * Disable a select box and next select boxes if exists
         * @param targetSelect
         */
        var disableSelect = function ( targetSelect ) {

            resetSelect( targetSelect );
            targetSelect.closest('.option-bar').addClass('disabled');
            targetSelect.prop( 'disabled', 'disabled' );

            var targetSelectID = targetSelect.attr('id');                    // target select box id
            var targetSelectIndex = selectIds.indexOf(targetSelectID);      // target select box index
            var nextSelectBoxesCount = selectCount - ( targetSelectIndex + 1 );

            // disable next select boxes
            if( nextSelectBoxesCount > 0 ) {
                for ( var i = targetSelectIndex + 1; i < selectCount; i++ ) {
                    var tempSelect = $( '#' + selectIds[i] );
                    resetSelect( tempSelect );
                    tempSelect.closest('.option-bar').addClass('disabled');
                    tempSelect.prop( 'disabled', 'disabled' );
                }
            }
        };


        /**
         * Enable a select box
         * @param targetSelect
         */
        var enableSelect = function ( targetSelect ) {
            targetSelect.prop( 'disabled', false )
            var optionWrapper = targetSelect.closest('.option-bar');
            if( optionWrapper.hasClass('disabled') ){
                optionWrapper.removeClass('disabled');
            }
        };


        /**
         * Update next select box/boxes based on change in parent select box
         * @param event
         */
        var updateChildSelect = function ( event ) {

            var selectedLocation = $(this).val();                                               // get selected slug
            var currentSelectIndex = selectIds.indexOf( $(this).attr('id') );                   // current select box index

            /* in case of any selection */
            if ( selectedLocation == 'any' && currentSelectIndex > -1 && currentSelectIndex < ( selectCount - 1 ) ) {  // no need to run this on last select box

                for( var s = currentSelectIndex; s < ( selectCount - 1 ); s++ ) {

                    var childSelectIsLast = ( selectCount == ( s + 2 ) );
                    var childSelect = $( '#'+selectIds[ s + 1 ] );
                    childSelect.empty();                                                   // make it empty

                    /* loop through select options to find and add child locations into next select */
                    var anyChildLocations = [];
                    $( '#' + selectIds[s] + ' > option').each( function() {
                        var currentOptionVal = this.value;
                        if ( currentOptionVal != 'any' ) {
                            var relatedTermID = getRelatedTermID( currentOptionVal );
                            if ( relatedTermID > 0 ){
                                var tempLocations = addChildLocations ( relatedTermID, childSelect, '', childSelectIsLast );
                                if ( tempLocations.length > 0 ){
                                    anyChildLocations = $.merge( anyChildLocations, tempLocations );
                                }
                            }
                        }
                    });

                    /* enable next select if options are added otherwise disable it */
                    if( anyChildLocations.length > 0 ) {
                        enableSelect( childSelect );                                    // enable child select box
                        if( !childSelectIsLast ){
                            childSelect.change( updateChildSelect );
                        }
                    } else {
                        disableSelect( childSelect );
                        break;
                    }

                }

                /* in case of valid location selection */
            } else {
                var parentID = getRelatedTermID( selectedLocation );                        // get related term id that will be used as parent id below
                if( parentID > 0 ) {                                                        // We can only do something if term id is valid
                    var childLocations = [];
                    for( var n = currentSelectIndex + 1; n < selectCount; n++ ) {
                        var childSelect = $( '#'+selectIds[ n ] );                          // selector for next( child locations ) select box
                        var childSelectIsLast = ( selectCount == ( n + 1 ) );
                        childSelect.empty();

                        if( childLocations.length == 0 ){    // 1st iteration
                            childLocations = addChildLocations( parentID, childSelect, '', childSelectIsLast );    // add all children
                        } else if( childLocations.length > 0 ) {  // 2nd and later iterations
                            var currentLocations = [];
                            for( var i = 0; i < childLocations.length; i++ ) {
                                var tempLocations = addChildLocations ( parseInt( childLocations[i]['term_id']), childSelect, '', childSelectIsLast );
                                if( tempLocations.length > 0 ) {
                                    currentLocations = $.merge( currentLocations, tempLocations );
                                }
                            }
                            childLocations = currentLocations;
                        }

                        if( childLocations.length > 0 ) {
                            enableSelect( childSelect );                                    // enable child select box
                            if( !childSelectIsLast ){
                                childSelect.change( updateChildSelect );
                            }
                        } else {
                            disableSelect( childSelect );
                            break;
                        }

                    }
                }
            }

        };


        /**
         * Mark the current value in query params as selected
         * @param targetSelect
         */
        var selectRightOption = function ( targetSelect  ) {
            if( Object.keys(locationsInParams).length > 0 ){
                var selectName = targetSelect.attr('name');
                if ( typeof locationsInParams[ selectName ] != 'undefined' ) {
                    targetSelect.find( 'option[value="'+ locationsInParams[ selectName ] +'"]' ).prop('selected', true);
                }
            }
        }


        /**
         * Initialize location boxes in search form
         */
        var initLocations = function () {

            var parentLocations = [];
            for( var s=0; s < selectCount; s++ ){

                var currentSelect = $( '#'+selectIds[s] );
                var currentIsLast = ( selectCount == (s + 1) );

                // 1st iteration
                if( s == 0 ) {
                    parentLocations = addChildLocations ( 0, currentSelect, '', currentIsLast );

                    // later iterations
                } else {
                    if( parentLocations.length > 0 ) {
                        var currentLocations = [];
                        var previousSelect = $( '#'+selectIds[s-1] );

                        // loop through all if value is any
                        if ( previousSelect.val() == 'any' ) {
                            for (var i = 0; i < parentLocations.length; i++) {
                                var tempLocations = addChildLocations(parseInt(parentLocations[i]['term_id']), currentSelect, '', currentIsLast );
                                if (tempLocations.length > 0) {
                                    currentLocations = $.merge(currentLocations, tempLocations);
                                }
                            }

                            // else display only children of current value
                        } else {
                            var parentID = getRelatedTermID( previousSelect.val() );
                            if( parentID > 0 ) {
                                currentLocations = addChildLocations( parentID, currentSelect, '', currentIsLast );
                            }
                        }
                        previousSelect.change( updateChildSelect );
                        parentLocations = currentLocations;
                    }
                }

                // based on what happens above
                if ( parentLocations.length == 0 ) {
                    disableSelect( currentSelect );
                    break;
                } else {
                    selectRightOption( currentSelect );
                }

            }
        }

        /* Runs on Load */
        initLocations();

    }

    /**
     * Insert key value parameter into query string
     * @param key
     * @param value
     */
    function insertParam(key, value) {
        key = encodeURI(key);
        value = encodeURI(value);

        var kvp = document.location.search.substr(1).split('&');

        var i = kvp.length;
        var x;
        while (i--) {
            x = kvp[i].split('=');

            if (x[0] == key) {
                x[1] = value;
                kvp[i] = x.join('=');
                break;
            }
        }

        if (i < 0) {
            kvp[kvp.length] = [key, value].join('=');
        }

        //this will reload the page, it's likely better to store this until finished
        document.location.search = kvp.join('&');
    }

    /**
     * Properties Sorting
     */
    $('#sort-properties').on('change', function() {
        var key = 'sortby';
        var value = $(this).val();
        insertParam( key, value );
    });


})(jQuery);