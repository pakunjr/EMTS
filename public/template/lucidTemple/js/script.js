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
 * Numeric and decimal boxes
 */
var numerics = function () {

    if ( $('.numeric').length > 0 )
        $('.numeric').numeric();

    if ( $('.decimal').length > 0 )
        $('.decimal').numeric({allow: '.'});

}; //numerics










/**
 *
 */
var minimapper = function () {
    $('input[type="text"], textarea, select').each(function () {
        var $this = $(this)
            ,thisID = $this.attr('id');

        if ( !$this.hasClass('datepicker') ) {

            var magnifierHTML = '<div class="minimap hidden" data-for="'+thisID+'"></div>';
            $this.after(magnifierHTML);

            var $minimap = $this.siblings('.minimap[data-for="'+thisID+'"]');

            $minimap.css({
                'left': $this.offset().left + 'px'
            });

            $this.on('mouseover click focus keyup change'
                , function () {

                var content = $this.is('select')
                    ? $this
                        .find('option[value="'+$this.val()+'"]')
                        .html()
                    : nl2br($this.val());

                if ( $.trim($this.val()) != '' )
                    $minimap.html(content).removeClass('hidden');
            }).on('focusout mouseout', function () {
                $minimap.addClass('hidden');
            });

        }
    });
}; //minimapper
















/**
 * Execute all the initializer functions
 */
$(document).ready(function () {
    navigationMenu();
    jQueryUIFx();
    numerics();
    minimapper();

    $('label').addClass('unhighlightable unselectable');
}); //ready
















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


















function nl2br(str, is_xhtml) {

  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined')
        ? '<br ' + '/>'
        : '<br>'
    ,breakTag = '<br />';

  return (str + '')
    .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}



























(function($) {

















$.fn.dataHighlight = function () {
    var $this = $(this);
    $this.hover(function () {
        $this.addClass('highlighted');
    }, function () {
        $this.removeClass('highlighted');
    });

    return this;
}; //dataHighlight














$.fn.formSearch = function (userOptions) {
    var $this = $(this)
        ,thisID = $this.attr('id')
        ,typingTimer = null
        ,query = null;

    var options = $.extend({
        'holder': ''
        ,'url': ''
    }, userOptions);

    if ( $this.siblings('.search-results[data-for="'+thisID+'"]').length < 1 ) {
        var srHTML = '<div '
                +'class="search-results hidden" '
                +'data-for="'+thisID+'"'
            +'>'
            +'</div>';
        $this.after(srHTML);
    }
    var $resultsHolder = $('.search-results[data-for="'+thisID+'"]');

    if ( options['holder'].val() != '' )
        $this.prop('readonly', true).formSearchCanceller(options['holder']);

    $this.on('keyup focus click', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function () {
            query = $this.val().replace(/[^a-z0-9\s]/gi, '');

            if ( query.length > 0 && options['holder'].val() == '' ) {
                $resultsHolder.load(options['url']+query, function () {
                    $resultsHolder.removeClass('hidden');

                    if ( $('.search-data').length > 0 ) {

                        $resultsHolder.append('<hr /><input class="btn-red cancel-search" type="button" value="Cancel" />');


                        $('.search-data').each(function () {
                            var $sd = $(this)
                                ,primeData = $sd
                                    .find('.prime-data')
                                    .val()
                                ,primeLabel = $sd
                                    .find('.prime-label')
                                    .html();

                            $sd.dataHighlight().click(function () {
                                $this.prop('readonly', true)
                                    .val(primeLabel)
                                    .formSearchCanceller(options['holder']);

                                options['holder'].val(primeData);
                                $resultsHolder.addClass('hidden');
                            });
                        });

                    } else $resultsHolder.html('Sorry, no match have been found.<hr /><input class="cancel-search" type="button" value="Ok" />');

                    var $cancelBtn = $resultsHolder.find('.cancel-search');
                    $cancelBtn.click(function () {
                        $resultsHolder.addClass('hidden');
                    });
                });
            } else $resultsHolder.addClass('hidden');
        }, 750);
    });

    return this;
}; //formSearch


$.fn.formSearchCanceller = function (holder) {
    var $this = $(this)
        ,thisID = $this.attr('id');

    var cHTML = '<img class="search-result-cancel-btn" src="'+URLBase+'public/img/blank.png" data-for="'+thisID+'" />';

    if ( $this.siblings('.search-result-cancel-btn[data-for="'+thisID+'"]').length < 1 )
        $this.after(cHTML);

    var $cancelBtn = $('.search-result-cancel-btn[data-for="'+thisID+'"]');

    $cancelBtn.click(function () {
        holder.val('');
        $this.val('').prop('readonly', false);
        $cancelBtn.remove()
    });

    return this;
}; //formSearchCanceller





















$.fn.formNote = function (userOptions) {
    var $this = $(this)
        ,thisID = $this.attr('id');

    var options = $.extend({
        'content': 'No content provided.'
    }, userOptions);

    var fnHTML = '<img class="note-trigger" src="'+URLBase+'public/img/blank.png" data-for="'+thisID+'" />'
        +'<div class="note hidden" data-for="'+thisID+'">'
        +'<span style="color: #053;">Developer\'s Note</span><hr />'
        +options['content']
        +'<hr />'
        +'<input class="note-close-btn" type="button" value="Close Note" />'
        +'</div>';

    if ( $this.siblings('.note[data-for="'+thisID+'"]').length < 1 )
        $this.after(fnHTML);

    var $note = $('.note[data-for="'+thisID+'"]')
        ,$noteCloseBtn = $note.find('.note-close-btn')
        ,$noteTrigger = $note.siblings('.note-trigger[data-for="'+thisID+'"]');

    $note.css({
        'top': $this.offset().top + 'px'
        ,'left': $this.offset().left + 'px'
    });

    $noteTrigger.click(function () {
        if ( !$note.is(':visible') || $note.hasClass('hidden') ) {
            $note.removeClass('hidden').css({
                'top': $this.offset().top + 'px'
                ,'left': $this.offset().left + 'px'
            });;
            $noteTrigger.addClass('hidden');
        }
    });

    $noteCloseBtn.click(function () {
        if ( $note.is(':visible') || !$note.hasClass('hidden') ) {
            $note.addClass('hidden');
            $noteTrigger.removeClass('hidden');
        }
    });

    return this;
}; //formNote















})(jQuery);

