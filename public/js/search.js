var initializeSearchEnvironment = function () {
    if ( $('#tmp-search').length < 1 )
        $('body').append('<div id="tmp-search" style="display: none;"></div>');
}; //End initializeSearchEnvironment

var closeSearches = function () {
    if ( $('#tmp-search').length > 0 ) {
        $('#tmp-search').stop(true, true).slideUp(250, function () {
            $(this).remove();
        });
    }
}; //End closeSearches

var loadTheSearch = function (file, $object) {
    if ( $('#tmp-search').length < 1 )
        return false;

    var $tmpSearch = $('#tmp-search');
    $tmpSearch.load(file, function () {
        $tmpSearch.css({
            'top': $object.offset().top + $object.height() + 10 + 'px'
            , 'left': $object.offset().left + 'px'
        }).slideDown(250, function () {
            $tmpSearch.prepend('<input id="search-btn-close" type="button" style="display: none;" value="Close" />');
            var $searchBtnClose = $('#search-btn-close');
            $searchBtnClose.slideDown(250).on('click', function () {
                closeSearches();
            });
        });

        var $searchData = $('.search-data');
        $searchData.on('click', function () {
            var confirmMessage = 'Is this the item you were searching for?';
            if ( confirm(confirmMessage) ) {
                var $this = $(this)
                    , valuableData = $this.find('.valuable-data').val();

                $object
                .val($this.find('.search-result-item-name').html() + ' \( '
                    + $this.find('.search-result-item-serialno').html() + ' | '
                    + $this.find('.search-result-item-modelno').html() + ' \)');

                if ( $object.next('.search-result-data').length < 1 ) {
                    $object.after('<input type="hidden" value="'
                        + valuableData + '" />');
                } else {
                    $object.next('.search-result-data').val(valuableData);
                }

                closeSearches();
            }
        });
    });
}; //End loadTheSearch

/**
 * Search functions.
 */

var searchItem = function () {
    if ( $('.search-item').length < 1 )
        return false;

    var $searchItem = $('.search-item');
    $searchItem.on('keyup focus', function () {
        initializeSearchEnvironment();

        var $this = $(this)
            , searchValue = $this.val();

        loadTheSearch(baseURL + 'public/js/search.php?search=item&query=' + searchValue, $this);
    });
}; //End searchItem

var searchPackage = function () {
    if ( $('.search-package').length < 1 )
        return false;

    var $searchPackage = $('.search-package');
    $searchPackage.on('keyup', function () {
        initializeSearchEnvironment();

        var searchValue = $searchPackage.val();
    });
}; //End searchPackage

var searchPerson = function () {
    if ( $('.search-person').length < 1 )
        return false;

    var $searchPerson = $('.search-person');
    $searchPerson.on('keyup', function () {
        initializeSearchEnvironment();

        var searchValue = $searchPerson.val();
    });
}; //End searchPerson

$(document).ready(function () {
    searchItem();
    searchPackage();
    searchPerson();
});
