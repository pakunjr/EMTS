/**
 * ============================================================================
 * TABLE OF CONTENTS
 * ============================================================================
 *
 * i. Useful Functions
 * 1. Initializer
 * 2. Logged User Options
 * 3. jQuery UIs
 * 4. Forms
 * 5. Error Messages
 */

/* Block i. Useful Functions */

var IsEmailValid = function (emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}; //End IsEmailValid

/* Block 1. Initializer */

var Initializer = function () {

    /* Dummy Links */
    $('a[href="#"]').click(function () { return false; });

    var Expander = function () {
        var $e = $('#navigation, #copyright'),
            $n = $('#navigation');
        var InitResize = function () {
            $e.css('width', $window.width() + 'px');
        }; //End InitResize
        InitResize();
        $window.on('resize', function () { InitResize(); });

        $n.slideDown(250);
    }; //End Expander

    var TimeDate = function () {
        var months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            $sysDate = $('#system-date'),
            $sysTime = $('#system-time');

        var d = new Date(),
            year = d.getFullYear(),
            month = months[d.getMonth()],
            day = d.getDate(),
            date = month + ' ' + day + ', ' + year,
            hours = d.getHours() < 10 ? '0' + d.getHours() : d.getHours(),
            minutes = d.getMinutes() < 10
                ? '0' + d.getMinutes()
                : d.getMinutes(),
            seconds = d.getSeconds() < 10
                ? '0' + d.getSeconds()
                : d.getSeconds(),
            time = hours + ' : ' + minutes + ' : ' + seconds;

        $sysDate.html(date);
        $sysTime.html(time);

        setTimeout(TimeDate, 1000);
    }; //End TimeDate

    var SubMenus = function () {
        var $subMenus = $('#navigation .sub-menu');
        $subMenus.each(function () {
            var $subMenu = $(this),
                $parent = $subMenu.parent();

            $subMenu.prev().append('&nbsp;&raquo;');
            $parent.hover(function () { $subMenu.show(0); },
                function () { $subMenu.hide(0); });

            if ( $(this).find('.sub-menu').length > 0 ) {
                var $childSubMenu = $(this).find('.sub-menu'),
                    $parentSubMenu = $childSubMenu.closest('.sub-menu');
                $childSubMenu.css('margin-left', $parentSubMenu.width() + 'px');
            }
        });
    }; //End SubMenus

    var ScrollToTop = function () {
        if ( $('.icon-insignia-70x29').length <= 0 ) return false;
        $('.icon-insignia-70x29').click(function () {
            $('html, body').animate({scrollTop: 0}, 300);
        }).prop('title', 'Scroll to Top').css('cursor', 'pointer');
    }; //End ScrollToTop

    /**
     * Execute functions inside the Initializer function
     */
    Expander();
    TimeDate();
    SubMenus();
    ScrollToTop();

}; //End Initializer

/* Block 2. Logged User Options */

var LoggedUserOptions = function () {
    if ( $('#user').length < 1
            || $('#user-options').length < 1 )
        return false;

    var $user = $('#user')
        , $userOptions = $user.find('#user-options');

    var PositionUserOptions = function () {
        $userOptions.css({
            'left': $user.offset().left + 'px'
        });
    } //End PositionUserOptions
    PositionUserOptions();

    $user.hover(function () {
        $userOptions.stop(true, true).slideDown(250);
    }, function () {
        $userOptions.stop(true, true).slideUp(250);
    });

    $window.on('resize', function () {
        PositionUserOptions();
    });
}; //End LoggedUserOptions

/* Block 3. jQuery UIs */

var jQueryUIs = function () {
    if ( $('.date-picker').length > 0 ) {
        $('.date-picker').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });
    }
}; //End jQueryUIs

/* Block 4. Forms */

var FormFx = function () {

    /**
     * Hide one form while showing the necessary
     * form. It's like a form accordion.
     */
    var TheSearchVia = function () {
        if ( $('.search-via').length <= 0 ) return false;
        var $searchVia = $('.search-via');
        $searchVia.each(function () {
            var $this = $(this),
                $thisParent = $this.parent();
            $this.css('float', 'left');

            var ResetForm = function () {
                $thisParent.find('input[type="text"], input[type="password"], textarea').val('');
            }; //End ResetForm

            var RenderSearchVia = function () {
                var $selectedOption = $thisParent.find('.search-via-option.' + $this.val()),
                    $extraOption = $thisParent.find('.search-via-option:not(.'+ $this.val() +')');
                $extraOption.stop(true, true).fadeOut(50, function () {
                    $selectedOption.stop(true, true).fadeIn(350);
                });
            }; //End RenderSearchVia
            RenderSearchVia();

            $searchVia.on('change', function () { RenderSearchVia(); });
        });
    }; //End TheSearchVia

    /**
     * Changes the usual appearance of select fields with
     * only two choices wherein two buttons will represent
     * the choices.
     */
    var TheSelect = function () {
        if ( $('select').length <= 0 ) return false;

        $('select').each(function () {
            var $this = $(this);
            if ( $this.find('option').length == 2 ) {
                $this.hide(0, function () {
                    $this.after('<ul type="none" class="select-alt">'
                        + '<li title="'
                        + $this.find('option:first-child').html()
                        + '">'
                        + '<input type="hidden" value="' + $this.find('option:first-child').prop('value') + '" />'
                        + $this.find('option:first-child').html()
                        + '</li>'
                        + '<li title="'
                        + $this.find('option:last-child').html()
                        + '">'
                        + '<input type="hidden" value="' + $this.find('option:last-child').prop('value') + '" />'
                        + $this.find('option:last-child').html()
                        + '</li>'
                        + '</ul>');
                });

                var $alt = $this.next('.select-alt')
                    , $altOpts = $alt.children('li');
                if ( $this.hasClass('search-via') ) $alt.css('float', 'left');

                var SetColor = function () {
                    var theValue = $this.val();
                    $alt.children('li.selected').removeClass('selected');
                    $alt.find('input[value='+ theValue +']').parent('li').addClass('selected');
                }; //End SetColor
                SetColor();

                $altOpts.click(function () {
                    if ( $(this).hasClass('selected') ) {
                        var $targetOption = $(this).parent()
                                            .find('li:not(.selected)')
                            , theValue = $targetOption.children('input').val();
                        $this.val(theValue).trigger('change');
                        SetColor();
                    } else {
                        var theValue = $(this).children('input').val();
                        $this.val(theValue).trigger('change');
                        SetColor();
                    }
                    return false;
                });

                $this.on('change', function () { SetColor(); });
            } //End if
        });
    }; //End TheSelect

    /**
     * Properly reset the form so the alternative select tag 
     * will reset to its default value and to avoid errors.
     */
    var FormReset = function () {
        $('input[type="reset"]').on('click', function (e) {
            e.preventDefault();
            var $parentForm = $(this).closest('form');
            $parentForm.get(0).reset();
            $parentForm.find('select').trigger('change');
        });
    }; //End FormReset

    /**
     * Execute the functions
     */
    TheSearchVia();
    TheSelect();
    FormReset();

}; //End FormFx

/* Block 5. Error Messages */

var ErrorMessages = function () {
    var mergedContent = '';
    if ( $('.msg-error').length > 1 ) {
        $('.msg-error').each(function () {
            var $this = $(this);
            mergedContent = mergedContent
                    + $this.html()
                    + '<br />';
            $this.remove();
        });

        $('body').append('<div class="msg-error">'
                        + mergedContent
                        + '</div>');
    } else { $('.msg-error').append('<br />'); }

    var $msgError = $('.msg-error');
    $msgError.append('<input class="btn-close-error" type="button" value="Hide this error message" style="margin-top: 10px;" />');
    $msgError.find('.btn-close-error').click(function () {
        $msgError.fadeOut(350, function () { $msgError.remove(); });
    });
}; //End ErrorMessages

var DirectError = function (faultyElementClassification, errorMessage) {
    if ( $('.msg-error-direct').length > 0 ) return false;

    var HTMLoutput = '<div class="msg-error-direct" title="'
                    + 'Click to hide'
                    + '">'
                    + errorMessage
                    + '</div>';
    $('body').append(HTMLoutput);

    var $directErrorMessage = $('.msg-error-direct')
        , $faultyElement = $(faultyElementClassification);

    $directErrorMessage.css({
        'top': $faultyElement.offset().top
                + $faultyElement.height()
                + 17
                + 'px'
        , 'left': $faultyElement.offset().left + 'px'
    });

    /* RemoveDEM = Remove Direct Error Message */
    var RemoveDEM = function () {
        $directErrorMessage.fadeOut(150, function () {
            $directErrorMessage.remove();
        });
    }; //End RemoveDEM

    $directErrorMessage.on('click', function () { RemoveDEM(); });
    $faultyElement.on('click', function () { RemoveDEM(); });

}; //End DirectError

/**
 * Execute the codes
 */
$document.ready(function () {
    Initializer();
    LoggedUserOptions();
    jQueryUIs();
    FormFx();
    ErrorMessages();
}); //End $document.ready()
