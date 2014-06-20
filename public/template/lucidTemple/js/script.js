/**
 * For submenus on the navigation menus
 */
var navigationMenu = function () {
    $('.menu-items').each(function () {
        var $this = $(this)
            ,$subMenu = $this.find('.submenu');

        $this.hover(function () {
            $subMenu.stop().slideDown('100');
        }, function () {
            $subMenu.stop().slideUp('200');
        });
    });
}; //navigationMenu





/**
 * A container for jQuery UIs
 */
var jQueryUIFx = function () {
    /**
     * Make the datepicker working
     */
    if ( $('.datepicker').length > 0 ) {
        $('.datepicker').datepicker({
            changeMonth: true
            ,changeYear: true
            ,dateFormat: 'yy-mm-dd'
        }).val('0000-00-00').keydown(function () {
            return false;
        });
    } //if
}; //jQueryUIFx



/**
 * Notes
 */
var instructionalNotes = function () {
    if ( $('.note').length < 1 ) return false;


    $('.note').each(function () {

        var $this = $(this)
            ,attrDataFor = $this.attr('data-for')
            ,thisContent = $this.html()
            ,$master = null;


        if ( $this.siblings('#'+attrDataFor).length > 0 )
            $master = $this.siblings('#'+attrDataFor);
        else if ( $this.siblings('.'+attrDataFor).length > 0 )
            $master = $this.siblings('.'+attrDataFor);


        if ( $master != null ) {
            var duplicatedNote = '<div class="note" data-for="'+attrDataFor+'">'
                +'<span class="icon-note"></span>'
                +'<div class="note-content hidden">'
                +'<div class="note-title">Guideline/s:</div>'
                +thisContent
                +'<div class="note-close">Click to close</div>'
                +'</div>'
                +'</div>';

            $master.after(duplicatedNote);
            $this.remove();

            var $this = $master.next('.note')
                ,$noteIcon = $this.find('.icon-note')
                ,$noteContent = $this.find('.note-content');

            $noteIcon.click(function () {
                if ( $noteContent.hasClass('hidden') )
                    $noteContent.removeClass('hidden');
            });

            $noteContent.css({
                'margin-left': 0 - $noteContent.width() + 'px'
            }).click(function () {
                if ( !$noteContent.hasClass('hidden') )
                    $noteContent.addClass('hidden');
            });
        } else {
            $this.remove();
        }

    });
}; //instructionalNotes





/**
 * Search functions
 */
var searchFunctions = function () {
    if ( $('.search-results').length < 1 ) return false;

    $('.search-results').each(function () {
        /**
         * dataSearch is the search box for holding
         * the query
         *
         * dataResult is the container of the result
         * the unique identifier of the picked result
         */
        var $this = $(this)
            ,dataSearch = $this.attr('data-search')
            ,dataURL = $this.attr('data-url')
            ,dataResult = $this.attr('data-result')
            ,$searchBox = $this.siblings('#'+dataSearch)
            ,$result = $this.siblings('#'+dataResult);

        /**
         * Delay to detect if the user has stopped
         * or finished typing the search query
         */
        var typingTimer = null
            ,typingInterval = 500;

        $searchBox.on('keyup focus click', function (e) {
            /**
             * Clear search box if `esc` key was pressed
             */
            var pressedKey = e.keyCode || e.which;
            if ( pressedKey == 27 ) $searchBox.val('');


            clearTimeout(typingTimer);
            typingTimer = setTimeout(function () {

                var searchQuery = $searchBox.val()
                    ,forURL = searchQuery.replace(/[^a-z0-9\s]/gi, '');

                if ( searchQuery.length > 0 ) {
                    $this.load(dataURL+forURL, function () {

                        if ( $this.find('.search-data').length > 0 ) {
                            $this.find('.search-data').click(function () {
                                var $dataThis = $(this)
                                    ,dataIdentifier = $dataThis.find('.search-result-identifier').val()
                                    ,dataLabel = $dataThis.find('.search-result-label').html();

                                if ( confirm('Set '+dataLabel+'?') ) {
                                    $searchBox.val(dataLabel);
                                    $result.val(dataIdentifier);
                                }
                            });

                            $this.removeClass('hidden');
                        }

                    });
                } else $this.addClass('hidden');


                $searchBox.focusout(function () {
                    if ( $this.is(':hover') ) {
                        $this.mouseleave(function () {
                            $this.addClass('hidden');
                        });
                    } else $this.addClass('hidden');
                });

            }, typingInterval); //setTimeout
        });
    });
}; //searchFunctions








/**
 * Execute all the functions
 */
$(document).ready(function () {
    navigationMenu();
    jQueryUIFx();
    instructionalNotes();
    searchFunctions();
}); //ready
