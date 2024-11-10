$(document).ready(function() {
    // Validate Username
    $('#username').on('input', function() {
        var username = $(this).val();
        $.ajax({
            url: 'validate.php',
            method: 'POST',
            data: { username: username },
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    if (res.status === 'available') {
                        $('#usernameMessage').text('Username is available').removeClass('error').addClass('success');
                    } else if (res.status === 'taken') {
                        $('#usernameMessage').text('Username is already taken').removeClass('success').addClass('error');
                    }
                } catch (e) {
                    console.error('Invalid JSON response for username validation:', response);
                }
            },
            error: function() {
                console.error('An error occurred while validating the username');
            }
        });
    });

    // Validate Email
    $('#email').on('input', function() {
        var email = $(this).val();
        $.ajax({
            url: 'validate.php',
            method: 'POST',
            data: { email: email },
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    if (res.status === 'valid') {
                        $('#emailMessage').text('Email format is valid').removeClass('error').addClass('success');
                    } else if (res.status === 'invalid') {
                        $('#emailMessage').text('Invalid email format').removeClass('success').addClass('error');
                    }
                } catch (e) {
                    console.error('Invalid JSON response for email validation:', response);
                }
            },
            error: function() {
                console.error('An error occurred while validating the email');
            }
        });
    });

    // Handle form submission
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'validate.php',
            method: 'POST',
            data: $(this).serialize() + '&submit=1',
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    if (res.status === 'success') {
                        alert(res.message);
                        $('#registrationForm')[0].reset();
                    } else {
                        alert(res.message);
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', response);
                }
            },
            error: function() {
                alert('An error occurred while submitting the form');
            }
        });
    });
});