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
        $('.datepicker').each(function () {
            var $this = $(this);
            $this.datepicker({
                changeMonth: true
                ,changeYear: true
                ,dateFormat: 'yy-mm-dd'
            }).keydown(function () {
                return false;
            });

            if ( $this.val() == '' ) $this.val('0000-00-00');
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

        if ( $searchBox.val() != '' && $result.val() != '' ) {
            searchCancelButton($searchBox, $result);
            $searchBox.prop('disabled', true);
        }

        $searchBox.on('keyup focus click', function (e) {
            /**
             * Clear search box if `esc` key was pressed
             */
            var pressedKey = e.keyCode || e.which;
            if ( pressedKey == 27 ) {
                $searchBox.val('');
                $result.val('');
            }

            $this.css({
                'left': $searchBox.offset().left + 'px'
            });


            clearTimeout(typingTimer);
            typingTimer = setTimeout(function () {

                var searchQuery = $searchBox.val()
                    ,forURL = searchQuery.replace(/[^a-z0-9\s]/gi, '');

                if ( searchQuery.length > 0 ) {
                    $this.load(dataURL+forURL, function () {

                        if ( $this.find('.search-data').length > 0 ) {
                            $this.find('.search-data').each(function () {


                                var $dataThis = $(this)
                                    ,dataIdentifier = $dataThis.find('.search-result-identifier').val()
                                    ,dataLabel = $dataThis.find('.search-result-label').html();

                                dataHighlighter($dataThis);
                                $dataThis.click(function () {
                                    $result.val(dataIdentifier);
                                    $searchBox.prop('disabled', true)
                                        .val(dataLabel)
                                        .trigger('change');
                                    $this.addClass('hidden');
                                    searchCancelButton($searchBox, $result);
                                });

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

            }, typingInterval); //setTimeout - typingTimer
        });
    });
}; //searchFunctions

var searchCancelButton = function ($searchBox, $result) {
    if ( $searchBox.next('.search-result-cancel').length < 1 ) {

        $searchBox.after('<img class="search-result-cancel search-result-cancel-icon" />');
        var $cancelButton = $searchBox.next('.search-result-cancel');
        $cancelButton.css({
            'margin-top': (0 - $cancelButton.height() / 2) + 5 + 'px'
            ,'left': ($searchBox.offset().left - ($cancelButton.width() / 2)) + 5 + 'px'
        }).click(function () {
            $result.val('');
            $searchBox.val('')
                .trigger('change')
                .prop('disabled', false);
            $cancelButton.remove();
        });

    }
}; //searchCancelButton





/**
 * Numeric boxes
 */
var numerics = function () {
    if ( $('.numeric').length < 1 ) return false;

    $('.numeric').keyup(function () {
        var $this = $(this)
            ,thisVal = $this.val();

        if ( thisVal != parseFloat(thisVal) )
            $this.val(parseFloat(thisVal));
    });
}; //numerics




/**
 * Execute all the initializer functions
 */
$(document).ready(function () {
    navigationMenu();
    jQueryUIFx();
    instructionalNotes();
    searchFunctions();
    numerics();
}); //ready


/**
 * ====================================================================
 * End initializer functions
 * ====================================================================
 */












/**
 * Alert functions
 */
var popAlert = function ( alertType, alertOptions ) {

    if ( $('.popup-alert-container').length > 0 )
        popAlertClose();

    /**
     * Set default options and override the defaults
     * with the values given in the parameter
     */
    var defaultOptions = $.extend({
            'message': 'No message set for this popup alert.'
            ,'action': function () {
                alert('There is no set function.');
            }
        }, alertOptions)
        ,popupHTML = null;

    /** 
     * Generate HTML for the popup basing on the
     * parameters given
     */
    if ( alertType == 'alert' ) {
        popupHTML = '<div class="popup-alert-container hidden">'
            +'<div class="popup-alert-content">'
            +'<div class="popup-alert-message">'
            +'<small>Information</small>'
            +'<hr />'
            +defaultOptions['message']
            +'</div>'
            +'<div class="popup-alert-buttons">'
            +'<input class="pa-btn-close btn-blue" type="button" value="Close" />'
            +'</div>'
            +'</div>'
            +'</div>';
    } else if ( alertType == 'confirm' ) {
        popupHTML = '<div class="popup-alert-container hidden">'
            +'<div class="popup-alert-content">'
            +'<div class="popup-alert-message">'
            +'<small>Confirmation</small>'
            +'<hr />'
            +defaultOptions['message']
            +'</div>'
            +'<div class="popup-alert-buttons">'
            +'<input class="pa-btn-approve btn-green" type="button" value="Approve" />'
            +'<input class="pa-btn-deny btn-red" type="button" value="Deny" />'
            +'</div>'
            +'</div>'
            +'</div>';
    } else if ( alertType == 'prompt' ) {
        //No application yet
    } else {
        //Just do nothing for a while
    }

    /**
     * Setup the popup alert
     */
    $('body').append(popupHTML);
    var $pContainer = $('.popup-alert-container')
        ,$pContent = $pContainer.children('.popup-alert-content')
        ,$pMessage = $pContent.children('.popup-alert-message')
        ,$pButtons = $pContent.children('.popup-alert-buttons');



    /**
     * Setup the visuals before displaying
     * the popup to the viewer
     */
    $pContainer.css({
        'width': $(window).width() + 'px'
        ,'height': $(window).height() + 'px'
        ,'position': 'fixed'
        ,'top': '0px'
        ,'left': '0px'
        ,'z-index': '1000'
        ,'background': '#000'
        ,'background': 'rgba(0, 0, 0, 0.75)'
        ,'text-align': 'center'
    });

    $pContent.css({
        'display': 'inline-block'
        ,'min-width': '250px'
        ,'max-width': '400px'
        ,'margin-top':
            ($(window).height() / 2)
            - ($pContent.height() / 2)
            + 'px'
        ,'padding': '15px 20px'
        ,'border-radius': '7px'
        ,'border': '1px solid #ccc'
        ,'background': '#fff'
        ,'text-align': 'left'
        ,'font-size': '0.9em'
    });


    var popupRendering = function () {
        $pContainer.css({
            'width': $(window).width() + 'px'
            ,'height': $(window).height() + 'px'
        });

        $pContent.css({
            'margin-top':
                ($(window).height() / 2)
                - ($pContent.height() / 2)
                + 'px'
        });
    }; //popupRendering


    $(window).on('resize', function () {
        popupRendering();
    });


    $pMessage.css({
        'display': 'block'
        ,'margin': '0px 0px 20px 0px'
    });


    $pButtons.css({
        'display': 'block'
        ,'text-align': 'center'
    });


    $pContainer.fadeIn(250, function () {

        popupRendering();

        /**
         * Button functions or scripts
         */
        if ( alertType == 'alert' ) {
            
            $('.pa-btn-close').click(function () { popAlertClose(); });
            
        } else if ( alertType == 'confirm' ) {

            $('.pa-btn-approve').click(function () {
                defaultOptions['action']();
                popAlertClose();
            });

            $('.pa-btn-deny').click(function () { popAlertClose(); });

        } else {
            //do nothing
        }
    });
    popupRendering();



}; //popAlert




var popAlertClose = function () {
    if ( $('.popup-alert-container').length < 1 ) return false;

    $('.popup-alert-container').each(function () {
        var $this = $(this);
        $this.fadeOut(150, function () {
            $this.remove();
        });
    });
}; //popAlertClose








/**
 * Highlighter
 */
var dataHighlighter = function ($data) {
    $data.hover(function () {
        $data.addClass('highlighted');
    }, function () {
        $data.removeClass('highlighted');
    });
}; //dataHighlighter




