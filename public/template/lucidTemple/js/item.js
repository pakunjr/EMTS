$(document).ready(function () {

    /**
     * Owner type script when choosing owners for the new item
     */
    var $otBox = $('#owner-type')
        ,$otEmpForm = $('#owner-type-employee-form')
        ,$otDeptForm = $('#owner-type-department-form')
        ,$otGuestForm = $('#owner-type-guest-form')
        ,$showForm = null
        ,$visibleForm = null
        ,$dateOfPossessionBox = $('#owner-date-of-possession')
        ,$dateOfPossessionBoxLabel = $dateOfPossessionBox.prev('label');

    $otBox.change(function () {
        var selectedValue = $otBox.val()
            ,selectedLabel = null;
        $otBox.children('option').each(function () {
            var $this = $(this)
                ,state = $this.prop('selected');

            if ( state ) selectedLabel = $this.html();
        });

        $('.owner-type-form').each(function () {
            var $this = $(this);
            if ( $this.is(':visible') ) $visibleForm = $this;
        });

        switch ( selectedLabel ) {
            case 'Employee':
                $showForm = $otEmpForm;
                break;

            case 'Department':
                $showForm = $otDeptForm;
                break;

            case 'Guest':
                $showForm = $otGuestForm;
                break;

            case 'None':
                $showForm = 'None';
                break;

            default:
        }

        if ( $showForm != 'None' ) {
            if ( $showForm != $visibleForm ) {
                $visibleForm.slideUp(150, function () {
                    $showForm.slideDown(250);
                });
            }

            if ( !$dateOfPossessionBox.is(':visible') ) {
                $dateOfPossessionBox.slideDown(250);
                $dateOfPossessionBoxLabel.slideDown(250);
            }

        } else {
            $visibleForm.slideUp(150);
            $dateOfPossessionBox.slideUp(150);
            $dateOfPossessionBoxLabel.slideUp(150);
        }
    });






    /**
     * If package is set, disable the date of purchase
     */
    var $DOPBox = $('#single-item-date-purchase')
        ,$PS = $('#single-item-package-search-id')
        ,$PSBox = $('#single-item-package-search');

    if ( $PS.val() != '' ) $DOPBox.prop('disabled', true);
    else $DOPBox.prop('disabled', false);

    $PSBox.on('change keyup focusout', function () {
        if ( $PS.val() != '' ) $DOPBox.prop('disabled', true);
        else $DOPBox.prop('disabled', false);
    });





    /**
     * Item type script
     */
    var $itBox = $('#single-item-type')
        ,$specForm = $('#single-item-specification-form');

    $itBox.children('option').each(function () {
        var $this = $(this);
        if ( $this.is(':selected')
            && $this.html() == 'Devices'
            && !$specForm.is(':visible') )
            $specForm.slideDown(250);
    });

    $itBox.change(function () {
        var selectedType = null;

        $itBox.children('option').each(function () {
            var $this = $(this);
            if ( $this.is(':selected') ) selectedType = $this.html();
        });

        if ( selectedType == 'Devices' ) {
            if ( !$specForm.is(':visible') )
                $specForm.slideDown(250);
        } else {
            if ( $specForm.is(':visible') )
                $specForm.slideUp(150);
        }
    });





    /**
     * Viewing an item on the item view all
     */
    if ( $('.item-data').length > 0 ) {
        $('.item-data').each(function () {
            var $this = $(this)
                ,itemID = $this.attr('data-id')
                ,itemURL = $this.attr('data-url')
                ,itemName = $this.find('td:first-child').html();

            dataHighlighter($this);
            $this.click(function () {
                window.location = itemURL;
            });
        });
    }






    /**
     * Deleting an item / archiving it in the system
     */
    if ( $('.delete-button').length > 0 ) {
        $('.delete-button').click(function () {
            var $this = $(this)
                ,url = $this.attr('href')
                ,$infoBlock = $this.closest('tr').children('td:first-child');

            popAlert('confirm', {
                'message': 'Are you sure you want to delete this item?'
                +'<span style="display: block; padding: 10px 15px; border-radius: 5px; border: 1px solid #ccc;">'
                +$infoBlock.html()
                +'</span>'
                ,'action': function () {
                    window.location = url;
                }
            });

            return false;
        });
    }







/**
 * ============================================
 * FORM SUBMISSIONS
 * ============================================
 */




    /**
     * Form submission for new item
     */
    var $siForm = $('#new-single-item-form')
        ,$submitBtn = $siForm.find('input[type="submit"]');

    $submitBtn.click(function () {

        var ownerType = null
            ,personSearchID = $.trim($('#person-search-id').val())
            ,departmentSearchID = $.trim($('#department-search-id').val())
            ,guestSearchID = $.trim($('#guest-search-id').val());

        /**
         * Get the label of the currently selected owner type
         */
        $('#owner-type').children('option').each(function () {
            var $this = $(this);
            if ( $this.is(':selected') ) ownerType = $this.html();
        });

        /**
         * Pop an error when the user didn't set an owner for the item
         */
        if (
            (ownerType == 'Employee'
                && (
                        personSearchID == ''
                        || personSearchID == null
                    ))
            || (ownerType == 'Department'
                && (
                        departmentSearchID == ''
                        || departmentSearchID == null
                    ))
            || (ownerType == 'Guest'
                && (
                        guestSearchID == ''
                        || guestSearchID == null
                    ))
            ) {
            popAlert('alert', {
                'message': 'Please choose an owner or set the <b>Owner Type</b> to none.'
            });
            return false;
        }


        popAlert('confirm', {
            'message': 'Save the information of the new item?<br />'
                +'<small>Please be sure that all informations you entered are correct. Thank you.</small>'
            ,'action': function () {
                $siForm.submit();
            }
        });
        return false;
    });


}); //$(document).ready()
