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
 * Execute all the functions
 */
$(document).ready(function () {
    NavigationMenu();
});
