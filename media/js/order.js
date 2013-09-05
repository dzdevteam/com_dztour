jQuery(document).ready(function(){
    var alert_tpl = '<div class="alert"></div>',
        displayAlert = function(message, classname) {
            jQuery("#alert-area").html(jQuery(alert_tpl).addClass(classname).append(message));
        };
    
    // Validation for order form
    jQuery('#order-form').validate({
        submitHandler: function(form) {
            jQuery.ajax({
                type: form.method,
                url: form.action,
                data: jQuery(form).serialize(),
                success: function(data) {
                    displayAlert(data.message, 'alert-success');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    displayAlert(errorThrown, 'alert-danger');
                }
            });
        },
        rules: {
            "order[email]": "email",
            "order[adults]": "digits",
            "order[children]": "digits",
            "order[start_date]": "dateISO",
            "order[end_date]": "dateISO"
        },
        errorClass: "alert"
    });
    
    // Set up calendar input
    var $end_date = jQuery('input[name="order[end_date]"]').datepicker({
        format: "yyyy-mm-dd"
    }).off('focus'); // Preven this datepicker to show up
    var $start_date = jQuery('input[name="order[start_date]"]').datepicker({
        format: "yyyy-mm-dd"
    }).on('changeDate', function(e) {
        var date = new Date(e.date);
        date.setDate(date.getDate() + parseInt($end_date.data('duration')))
        $end_date.datepicker('setValue', date);
    });
});