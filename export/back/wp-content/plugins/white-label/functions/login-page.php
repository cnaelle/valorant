<?php
/**
 *  Login screen changes.
 *
 * @package white-label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('login_enqueue_scripts', 'white_label_login_styles');

/**
 * Add White Label CSS to login page.
 *
 * @return void
 */
function white_label_login_styles()
{
    $login_logo_file = white_label_get_option('login_logo_file', 'white_label_login', false);
    $login_logo_width = white_label_get_option('login_logo_width', 'white_label_login', false);
    $login_logo_height = white_label_get_option('login_logo_height', 'white_label_login', false);
    $login_background_file = white_label_get_option('login_background_file', 'white_label_login', false);
    $login_background_color = white_label_get_option('login_background_color', 'white_label_login', '#f1f1f1');

    $login_box_background_color = white_label_get_option('login_box_background_color', 'white_label_login', '#fff');
    $login_box_text_color = white_label_get_option('login_box_text_color', 'white_label_login', '#444');
    $login_text_color = white_label_get_option('login_text_color', 'white_label_login', '#555d66');
    $login_button_background_color = white_label_get_option('login_button_background_color', 'white_label_login', '#007cba');
    $login_button_border_color = white_label_get_option('login_button_border_color', 'white_label_login', '#2271b1');
    $login_button_font_color = white_label_get_option('login_button_font_color', 'white_label_login', '#fff');

    $login_page_template = white_label_get_option('login_page_template', 'white_label_login', false);

    $template_css = "";
    if ($login_page_template === 'left') {
        $template_css.= "/* Login Template */\n";
		$template_css.= "div#login { background: $login_box_background_color; height: 100%; padding-left: 10%; padding-right: 10%; }\n";
		$template_css.= ".interim-login #login { padding: 15px; }\n";
		$template_css.= "@media only screen and (min-width: 650px) {\n";
        $template_css.= "\tbody #login { background: $login_box_background_color; padding: 8% 60px 10px 50px; float: left; height: 100%; position: fixed; -webkit-box-shadow: 0px 0px 10px 10px rgba(0,0,0,0.35); -moz-box-shadow: 0px 0px 10px 10px rgba(0,0,0,0.35); box-shadow: 0px 0px 10px 10px rgba(0,0,0,0.35); }\n";
        $template_css.= "\t.language-switcher { position: fixed; bottom: 0; left: 0; width: 430px; }\n";
        $template_css.= "\t.language-switcher form#language-switcher { margin: auto; }\n";
        $template_css.= "}\n";
    } elseif ($login_page_template === 'right') {
        $template_css.= "/* Login Template */\n";
		$template_css.= "div#login { background: $login_box_background_color; height: 100%; padding-left: 10%; padding-right: 10%; }\n";
		$template_css.= ".interim-login #login { padding: 15px; }\n";
		$template_css.= "@media only screen and (min-width: 650px) {\n";
        $template_css.= "\tbody #login { background: $login_box_background_color; padding: 8% 60px 10px 50px; right: 0; height: 100%; position: fixed; -webkit-box-shadow:0px 0px 10px 10px rgba(0,0,0,0.35); -moz-box-shadow: 0px 0px 10px 10px rgba(0,0,0,0.35); box-shadow: 0px 0px 10px 10px rgba(0,0,0,0.35); }\n";
        $template_css.= "\t.language-switcher { position: fixed; bottom: 0; right: 0; width: 430px; }\n";
        $template_css.= "\t.language-switcher form#language-switcher { margin: auto; }\n";
        $template_css.= "}\n";
    }

    $template_css.= "body.login { background: ".$login_background_color."; background-image: url(".$login_background_file."); background-repeat: no-repeat; background-size: cover; background-position: center; }\n";
    if ($login_logo_file) {
        $template_css.= "#login h1 a, .login h1 a { background: url(".$login_logo_file.") no-repeat top center; background-size: contain; ";
        if ($login_logo_width > 0 && $login_logo_height > 0) {
            $template_css.= "width: ".$login_logo_width."px; ";
            $template_css.= "height: ".$login_logo_height."px; ";
        }
        $template_css.= "}\n";
    }

    $template_css.= "body.login form { background-color: $login_box_background_color; border: $login_box_background_color; }\n";
    $template_css.= "body.login label, body.login h1.admin-email__heading, body.login p.admin-email__details { color: $login_box_text_color; }\n";
    $template_css.= "body.login #backtoblog a, body.login #nav a, body.login p.admin-email__details a, body.login div.admin-email__actions-secondary a { color: $login_text_color; }\n";
    $template_css.= "body.login input[type=submit] { background-color: $login_button_background_color !important; border-color: $login_button_border_color !important; color: $login_button_font_color !important; }\n";

    echo '<style type="text/css">';
    echo esc_html($template_css);
    echo '</style>';
    echo white_label_login_custom_css();
}
add_action('login_enqueue_scripts', 'white_label_login_styles');

/**
 * Replace logo URL on login.
 *
 * @param string $default url.
 * @return string
 */
function white_label_login_styles_url($default)
{
    $company_url = white_label_get_option('business_url', 'white_label_login', false);

    if (!empty($company_url)) {
        return $company_url;
    }

    return $default;
}
add_filter('login_headerurl', 'white_label_login_styles_url', 2, 999);

/**
 * Replace URL title on login logo.
 *
 * @param string $default title.
 * @return string
 */
function white_label_login_styles_url_title($default)
{
    $name = white_label_get_option('business_name', 'white_label_login', false);
    if (!empty($name)) {
        return $name;
    }

    return $default;
}

add_filter('login_headertext', 'white_label_login_styles_url_title');

/**
 * Get custom login page CSS.
 *
 * @return string custom css.
 */
function white_label_login_custom_css()
{
    $css = white_label_get_option('login_custom_css', 'white_label_login', false);

    if (empty($css)) {
        return '';
    }

    return '<style type="text/css">'.esc_html($css).'</style>';
}
