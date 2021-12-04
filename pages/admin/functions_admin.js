$(document).ready(function(){

    if ($('#user_subscription_status').is(':checked')) {
        $('.expiredate-form').show(); 
    }else{
        $('.expiredate-form').hide(); 
    }

    $('#user_subscription_status').bind('click change load', function () {
        if ($('#user_subscription_status').is(':checked')) {
            $('.expiredate-form').show(); 
        }else{
            $('.expiredate-form').hide(); 
        }
    });

    if($('#distributor').is(':checked')) {
        $('#distributor_list').show();
    }else{
        $('#distributor_list').hide();
    }
    
    $('.user_type').bind('click change load', function () {
        if($('#distributor').is(':checked')) {
            $('#distributor_list').show();
        }else{
            $('#distributor_list').hide();
        }
     });
    
});