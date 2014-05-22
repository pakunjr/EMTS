var ExecuteSearch = function (searchType, query, $object) {
    $object.load(baseURL + 'public/js/search.php?search='+ searchType+ '&query='+ query, function () {
        $object.stop(true, true).show(250);
    });
}; //End ExecuteSearch

var DisplaySearchButtons = function ($container) {
    $container.prepend('<div class="search-container-buttons">'
        + '<img class="search-button-search" src="'+ baseURL +'public/img/blank.png" />'
        + '<img class="search-button-new" src="'+ baseURL +'public/img/blank.png" />'
        + '</div>'
        + '<input type="text" class="search-query" placeholder="Search" style="display: none;" />'
        + '<table class="search-results-container" style="display: none;"></table>'
        + '<div class="search-selected-summary"></div>');

    var searchType = 'undefined'
        , $searchBtn = $container.find('.search-button-search')
        , $newBtn = $container.find('.search-button-new')
        , $searchQuery = $container.find('.search-query')
        , $resultsContainer = $container.find('.search-results-container');

    if ( $container.hasClass('search-item-container') )
        searchType = 'item';
    else if ( $container.hasClass('search-person-container') )
        searchType = 'person';
    else if ( $container.hasClass('search-package-container') )
        searchType = 'package';
    else
        searchType = 'undefined';

    $searchBtn.on('click', function () {
        if ( $searchQuery.is(':visible') ) {
            $searchQuery.stop(true, true).hide(250);
            $resultsContainer.hide(250, function () {
                $resultsContainer.html('');
            });
        } else
            $searchQuery.stop(true, true).show(250)
    });

    $searchQuery.on('keyup', function () {
        var $this = $(this)
            , queryValue = $this.val();

        if ( queryValue.length > 1 )
            ExecuteSearch(searchType, queryValue, $resultsContainer);
        else
            $resultsContainer.stop(true, true).hide(250);
    });
}; //End DisplaySearchButtons

var SearchItemFeature = function () {
    if ( $('.search-item-container').length < 1 )
        return false;

    $('.search-item-container').each(function () {
        var $this = $(this);
        DisplaySearchButtons($this);
    });
}; //End SearchItemFeature

var SearchPersonFeature = function () {
    if ( $('.search-person-container').length < 1 )
        return false;

    $('.search-person-container').each(function () {
        var $this = $(this);
        DisplaySearchButtons($this);
    });
}; //End SearchPersonFeature

var SearchPackageFeature = function () {
    if ( $('.search-package-container').length < 1 )
        return false;

    $('.search-package-container').each(function () {
        var $this = $(this);
        DisplaySearchButtons($this);
    });
}; //End SearchPackageFeature

var SearchFeatures = function () {
    SearchItemFeature();
    SearchPersonFeature();
    SearchPackageFeature();
}; //End SearchFeatures

$(document).ready(function () {
    SearchFeatures();
}); //$(document).ready()

/**
 * Temporarily comment-out these old functions

var InitializeSearchEnvironment = function () {
    if ( $('#tmp-search').length < 1 )
        $('body').append('<div id="tmp-search" style="display: none;"></div>');
}; //End InitializeSearchEnvironment

var CloseSearches = function () {
    if ( $('#tmp-search').length > 0 ) {
        $('#tmp-search').stop(true, true).slideUp(250, function () {
            $(this).remove();
        });
    }
}; //End CloseSearches

var LoadTheSearch = function (file, $object) {
    if ( $('#tmp-search').length < 1 )
        return false;

    var $tmpSearch = $('#tmp-search');
    $tmpSearch.load(file, function () {
        $tmpSearch.css({
            'top': $object.offset().top + $object.height() + 10 + 'px'
            , 'left': $object.offset().left + 'px'
        }).slideDown(250, function () {
            $tmpSearch.prepend('<div id="search-results-buttons" style="display: none;">'
                + '<input id="search-btn-close" type="button" value="Close" />'
                + '<input id="search-btn-createNew" type="button" value="Create New" />'
                + '</div>');
            var $searchButtons = $('#search-results-buttons')
                , $searchBtnClose = $('#search-btn-close');
            $searchButtons.stop(true, true).slideDown(250, function () {
                $searchBtnClose.on('click', function () {
                    CloseSearches();
                });
            });
        });

        var $searchData = $('.search-data');
        $searchData.on('click', function () {
            var confirmMessage = 'Is this the item you were searching for?';
            if ( confirm(confirmMessage) ) {

                //Display information of the selected search result.
                if ( $object.next('.search-result-data').length < 1 ) {
                    $object.after('<div class="search-result-data"></div>');
                    var $resultData = $object.next('.search-result-data');
                } else {
                    var $resultData = $object.next('.search-result-data');
                }

                var $this = $(this)
                    , valuableData = $this.find('.valuable-data').val();

                $object.val($this.find('.search-result-display-id').html());

                if ( $object.next('.search-result-data').length < 1 ) {
                    $object.after('<input type="hidden" value="'
                        + valuableData + '" />');
                } else {
                    $object.next('.search-result-data').val(valuableData);
                }

                CloseSearches();
            }
        });
    });
}; //End LoadTheSearch

var SearchItem = function () {
    if ( $('.search-item').length < 1 )
        return false;

    var $searchItem = $('.search-item');
    $searchItem.on('keyup focus', function () {
        InitializeSearchEnvironment();

        var $this = $(this)
            , searchValue = $this.val();

        if ( searchValue.length > 0 )
            LoadTheSearch(baseURL + 'public/js/search.php?search=item&query=' + searchValue, $this);
        else
            CloseSearches();
    });
}; //End SearchItem

var SearchPackage = function () {
    if ( $('.search-package').length < 1 )
        return false;

    var $searchPackage = $('.search-package');
    $searchPackage.on('keyup focus', function () {
        InitializeSearchEnvironment();

        var $this = $(this)
            , searchValue = $this.val();

        if ( searchValue.length > 0 )
            LoadTheSearch(baseURL + 'public/js/search.php?search=package&query=' + searchValue, $this);
        else
            CloseSearches();
    });
}; //End SearchPackage

var SearchPerson = function () {
    if ( $('.search-person').length < 1 )
        return false;

    var $searchPerson = $('.search-person');
    $searchPerson.on('keyup focus', function () {
        InitializeSearchEnvironment();

        var $this = $(this)
            , searchValue = $this.val();

        if ( searchValue.length > 0 )
            LoadTheSearch(baseURL + 'public/js/search.php?search=person&query=' + searchValue, $this);
        else
            CloseSearches();
    });
}; //End SearchPerson

$(document).ready(function () {
    SearchItem();
    SearchPackage();
    SearchPerson();
});
 */