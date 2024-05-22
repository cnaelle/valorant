jQuery(document).ready(function ($) {


    //$("label[for='user_login'").text("Username or Email Address or Phone Number");

    $(document).on('click', '#resnd-otp', function () {

        $('#send-otp').fadeIn();

    })


    $(document).on('click', '#send-otp', function () {

        user_login = $('#user_login').val();
        nonce = $(this).attr("data-nonce")


        //console.log(grecaptcha.getResponse() );

        // 'g-recaptcha-response': grecaptcha.getResponse()

        if (user_login.length <= 0 || user_login == null) {
            error = $('<div id="login_error">\t<strong>Error</strong>: Username or email should not empty.<br></div>').insertBefore("#loginform");

            setTimeout(function () {

                error.fadeOut(2000).remove();

            }, 3000);

            return;
        }


        $(this).addClass('loading');

        $.ajax(
            {
                type: 'POST',
                context: this,
                url: user_verification_ajax.user_verification_ajaxurl,
                data: { "action": "user_verification_send_otp", 'user_login': user_login, 'nonce': nonce, },
                success: function (response) {
                    var data = JSON.parse(response);
                    otp_via_mail = data['otp_via_mail'];
                    otp_via_sms = data['otp_via_sms'];
                    error = data['error'];
                    success_message = data['success_message'];

                    $(this).removeClass('loading');


                    if (error) {
                        error = $(error).insertBefore('#loginform');
                        setTimeout(function () { error.fadeOut(500).remove(); }, 3000);

                    }

                    else {


                        if (otp_via_sms || otp_via_mail) {
                            message = $(success_message).insertBefore('#loginform');
                            setTimeout(function () { message.fadeOut(500).remove(); }, 3000);

                            //$(this).text('OTP has been sent');


                        }


                        $('.user-pass-wrap, .forgetmenot, .submit').fadeIn('slow');
                        $(this).fadeOut();
                        $('#resnd-otp').fadeIn();

                        $('#user_pass').removeAttr('disabled');
                        $("label[for='user_pass'").text("Enter OTP");

                    }

                    //location.reload();
                }
            });



    })










})
