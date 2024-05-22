<?php

add_action('rest_api_init', function() {
    $GLOBALS['isAuthenticated'] = get_current_user_id() !== 0 ? true : false;
});

function kronos_improve_user_info_jwt($data, $user)
{
    $user_logged_in = [
        'token' => $data['token'],
        'id' => $user->ID,
        'login' => $user->user_login,
        'email' => $user->user_email,
        'nickname' => $user->nickname,
        'display_name' => $user->display_name,
        'nicename' => $user->user_nicename,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'registered' => $user->user_registered,
        'roles' => $user->roles,
        'status' => intval($user->user_status),
        'acf' => get_fields("user_$user->ID"),
    ];
    return $user_logged_in;
}
add_filter('jwt_auth_token_before_dispatch', 'kronos_improve_user_info_jwt', 10, 2);


function kronos_jwt_expire_token($exp)
{
    $hours = 4;
    $exp = time() + (HOUR_IN_SECONDS * $hours);
    return $exp;
}
add_filter('jwt_auth_expire', 'kronos_jwt_expire_token', 10, 1);


//? Allow subscriber registration
function kronos_auth_register_subscriber()
{
    $users_controller = new WP_REST_Users_Controller();
    register_rest_route('wp/v2', '/users', array(
        array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => array($users_controller, 'create_item'),
            'permission_callback' => function ($request) {
                if (!current_user_can('create_users') && $request['roles'] !== array('subscriber')) {

                    return new WP_Error(
                        'rest_cannot_create_user',

                        __('Sorry, you are only allowed to create new users with the subscriber role.'),

                        array('status' => rest_authorization_required_code())
                    );
                }
                return true;
            },
            'args' => $users_controller->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
        ),
    ));
}
add_action('rest_api_init', 'kronos_auth_register_subscriber');

//? Verify user email with activation key
function wp_kronos_verify_user($request)
{
    global $wpdb;

    $activation_key = $request->get_param('activation_key');
    $meta_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_usermeta WHERE meta_value = %s", $activation_key));
    if ($meta_data) {
        $user_id = $meta_data->user_id;
        update_user_meta($user_id, 'user_activation_status', 1);
        delete_user_meta($user_id, 'user_activation_key');
    } else {
        return new WP_REST_Response(array('message' => 'Invalid activation key'), 401);
    }
}
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', 'users/verify/(?P<activation_key>[a-zA-Z0-9-]+)', array(
        'methods'             => 'GET',
        'callback'            => 'wp_kronos_verify_user',
        'permission_callback' => function () {
            return true;
        }
    ));
});

add_filter('rest_prepare_user', function ($response, $user, $request) {

    if ($request->get_route() === '/wp/v2/users/me') {
        $response->data['email'] = get_userdata($user->ID)->data->user_email;
        $response->data['roles'] = get_userdata($user->ID)->roles;
    }

    return $response;
}, 10, 3);

//? Login from REST API (front & back office)
function wp_kronos_login(WP_REST_Request $request)
{
    $params = $request->get_params();
    if (!isset($params['username'])) {
        wp_send_json(['message' => "Vous devez spécifier un nom d'utilisateur ou une adresse email"], 400);
    }
    if (!isset($params['password'])) {
        wp_send_json(['message' => 'Vous devez spécifier un mot de passe'], 400);
    }
    return wp_signon(['user_login' => $params['username'], 'user_password' => $params['password']]);
}
add_action('rest_api_init', function () {
    register_rest_route(
        'wp/v2',
        '/kronos/login',
        array(
            'methods'  => 'POST',
            'callback' => 'wp_kronos_login'
        )
    );
});

//? Logout from REST API
function wp_kronos_logout()
{
    wp_logout();
}
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', 'users/logout', array(
        'methods'             => 'POST',
        'callback'            => 'wp_kronos_logout',
        'permission_callback' => function () {
            return true;
        }
    ));
});

add_filter('bdpwr_code_email_subject', function ($subject) {
    return get_bloginfo('name') . " - Réinitialisation de votre mot de passe";
}, 10, 1);

add_filter('bdpwr_code_email_text', function ($text, $email, $code, $expiry) {
    return "Une réinitialisation du mot de passe a été demandée pour votre compte. <br><br>

    Votre code de réinitialisation est le <span style='font-size: 25px;'>$code</span> <br><br>

    Veuillez noter que ce code expirera aujourd'hui à " . date('H:i', $expiry);
}, 10, 4);
