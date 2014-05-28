var NavigationMenu = function () {
    $('.menu-items').each(function () {
        var $this = $(this)
            ,$subMenu = $this.find('.submenu');

        $this.hover(function () {
            $subMenu.stop().slideDown('100');
        }, function () {
            $subMenu.stop().slideUp('200');
        });
    });
}; //NavigationMenu



/**
 * A container for jQuery UIs
 */
var jQueryUIFx = function () {
    /**
     * Make the datepicker working
     */
    if ( $('.datepicker').length > 0 ) {
        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        }).val('0000-00-00');
    }
}; //jQueryUIFx


/**
 * Execute all the functions
 */
$(document).ready(function () {
    NavigationMenu();
    jQueryUIFx();
});
