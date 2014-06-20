$(document).ready(function () {

    /**
     * Owner type script
     */
    var $otBox = $('#owner-type')
        ,$otEmpForm = $('#owner-type-employee-form')
        ,$otDeptForm = $('#owner-type-department-form')
        ,$otGuestForm = $('#owner-type-guest-form');

    /**
     * Manage owner type forms
     */
    $otBox.change(function () {
        var $this = $(this)
            ,thisVal = $this.val();

        if ( !$('#owner-type-'+thisVal+'-form').is(':visible') ) {
            $('.owner-type-form').each(function () {
                var $this = $(this)
                    ,thisID = $this.attr('id')
                    ,$selectedForm = $('#owner-type-'+thisVal+'-form');

                if ( thisID != 'owner-type-'+thisVal+'-form'
                        && $this.is(':visible') )
                    $this.slideUp(150);

                if ( thisID == 'owner-type-'+thisVal+'-form'
                        && !$this.is(':visible') )
                    $this.slideDown(250);
            });
        }
    });

});