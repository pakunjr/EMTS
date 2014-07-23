var initializers = function () {

    if ( $('#item-form').length > 0 ) {

        var $isComponentCheckbx = $('#is-component')
            ,$hasComponentCheckbx = $('#has-component')
            ,$hostItemBox = $('#host-item');

        if ( $isComponentCheckbx.is(':checked') )
            $hostItemBox.prop('disabled', false);
        else
            $hostItemBox.prop('disabled', true);

        $isComponentCheckbx.on('load click change', function () {
            var $this = $(this);

            if ( $this.is(':checked') )
                $hostItemBox.prop('disabled', false);
            else
                $hostItemBox.prop('disabled', true);
        });

        $isComponentCheckbx.formNote({
            'content': 'Check this box if the item is a type of component.<br /><br />Please do specify a host item for this component afterwards.'
        });

        $hasComponentCheckbx.formNote({
            'content': 'Check this box if the item has/have component/s.<br /><br />This will make the item searchable as host item.'
        });

        $hostItemBox.formNote({
            'content': 'You can use the item name, serial no., or model no., in searching.<br /><br />To add host item for this component if the item you\'re searching doesn\'t exists, <a href="'+URLBase+'items/new_item/" target="_blank">click here</a>.<br /><br />Format:<br />Item Name ( Serial No. :: Model No. )'
        });



        var $ownerTypeBox = $('#owner-type')
            ,$empLblBox = $('#employee-block')
            ,$deptLblBox = $('#department-block')
            ,$guestLblBox = $('#guest-block')
            ,$itemDOP = $('#dop-block');

        var ownerTypeBoxMagic = function ($this) {
            var boxVal = $this.find('option[value="'+$this.val()+'"]').html();

            switch ( boxVal ) {
                case 'Employee':
                    $empLblBox.removeClass('hidden');
                    $deptLblBox.addClass('hidden');
                    $guestLblBox.addClass('hidden');
                    $itemDOP.removeClass('hidden');
                    break;

                case 'Department':
                    $empLblBox.addClass('hidden');
                    $deptLblBox.removeClass('hidden');
                    $guestLblBox.addClass('hidden');
                    $itemDOP.removeClass('hidden');
                    break;

                case 'Guest':
                    $empLblBox.addClass('hidden');
                    $deptLblBox.addClass('hidden');
                    $guestLblBox.removeClass('hidden');
                    $itemDOP.removeClass('hidden');
                    break;

                default:
                    $empLblBox.addClass('hidden');
                    $deptLblBox.addClass('hidden');
                    $guestLblBox.addClass('hidden');
                    $itemDOP.addClass('hidden');
            }
        }; //ownerTypeBoxMagic

        ownerTypeBoxMagic($ownerTypeBox);

        $ownerTypeBox.on('change', function () {
            ownerTypeBoxMagic($(this));
        });

    }











    if ( $('#item-log').length > 0 ) {
        var $itemLog = $('#item-log')
            ,$logTrigger = $('#item-log-trigger');

        $itemLog.addClass('hidden');
        $logTrigger.hover(function () {
            $(this).addClass('box highlighted');
        }, function () {
            $(this).removeClass('box highlighted');
        }).css('cursor', 'pointer').on('click', function () {
            $itemLog.toggleClass('hidden');
        });
    }


    if ( $('#ownership-history').length > 0 ) {
        var $oh = $('#ownership-history')
            ,$ohTrigger = $('#ownership-history-trigger');

        $oh.addClass('hidden');
        $ohTrigger.hover(function () {
            $(this).addClass('box highlighted');
        }, function () {
            $(this).removeClass('box highlighted');
        }).css('cursor', 'pointer').on('click', function () {
            $oh.toggleClass('hidden');
        });
    }







    if ( $('.item-block').length > 0 ) {

        $('.item-block').each(function () {

            var $this = $(this)
                ,primeData = $this.find('.prime-data').val();

            $this.click(function () {
                if ( !$this.hasClass('archived-item') )
                window.location = URLBase+'items/view/'+primeData+'/';
            });

        });

    }

}; //initializers






















var searchableElements = function () {

    if ( $('#host-item').length > 0 ) {
        $('#host-item').formSearch({
            'holder': $('#host-item-id')
            ,'url': URLBase+'items/search/'
        });
    }



    if ( $('#package-label').length > 0 ) {
        $('#package-label').formSearch({
            'holder': $('#package-id')
            ,'url': URLBase+'packages/search/'
        }).formNote({
            'content': 'Search for the package by typing it\'s name.<br /><br />To add a package, <a href="'+URLBase+'packages/new/">click here</a>.'
        });
    }



    if ( $('#employee-label').length > 0 ) {
        $('#employee-label').formSearch({
            'holder': $('#employee-id')
            ,'url': URLBase+'persons/search_employee/'
        }).formNote({
            'content': 'You can search by using either the firstname, middlename, or lastname and a dropdown list will show up matching your typed in query.<br /><br />To add an employee, <a href="'+URLBase+'persons/registration/" target="_blank">click here</a>.'
        });
    }




    if ( $('#department-label').length > 0 ) {
        $('#department-label').formSearch({
            'holder': $('#department-id')
            ,'url': URLBase+'departments/search/'
        }).formNote({
            'content': 'Type in either the acronym of the department, or a part of the name of the department and a dropdown list will show up matching your search query of the department.<br /><br />To add a department, <a href="#coming_soon">click here</a>.'
        });
    }




    if ( $('#guest-label').length > 0 ) {
        $('#guest-label').formSearch({
            'holder': $('#guest-id')
            ,'url': URLBase+'persons/search_guest/'
        }).formNote({
            'content': 'You can search by using either the firstname, middlename, or lastname and a dropdown list will show up matching your typed in query.<br /><br />To add a guest, <a href="'+URLBase+'persons/registration/guest/" target="_blank">click here</a>.'
        });
    }

}; //searchableElements





















var formSubmissions = function () {

}; //formSubmissions
















$(document).ready(function () {

    initializers();
    searchableElements();
    formSubmissions();

}); //$(document).ready()
