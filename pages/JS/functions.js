$(document).ready(function() {
    $('#registerForm').hide();

    $('#loginButton').click(function(){ 
        $('#loginForm').show(); 
        $('#registerForm').hide();
    });
    $('#registerButton').click(function(){ 
        $('#registerForm').show(); 
        $('#loginForm').hide();
    });
});