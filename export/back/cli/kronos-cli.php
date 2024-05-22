<?php

$rootDir = dirname(__DIR__, 2);
$backDir = dirname(__DIR__);
$envPath = $rootDir . '/.env';

// Load autoload
if (file_exists($backDir . '/vendor/autoload.php')) {
  require_once($backDir . '/vendor/autoload.php');
}

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable($rootDir);
if (file_exists($rootDir . '/.env')) {
  $envFile = file_get_contents($rootDir . '/.env');
  $dotenv->safeLoad();
}

$defaultPlugins = [
  'admin-menu-editor',
  'admin-taxonomy-filter',
  'advanced-custom-fields-font-awesome',
  'bdvs-password-reset',
  // 'broken-link-checker',
  'custom-post-type-ui',
  'disable-gutenberg',
  'disable-wp-notification',
  'duplicate-page',
  'easy-svg',
  'wp-mail-logging',
  'filebird',
  'google-sitemap-generator',
  'page-list',
  'user-verification',
  'webp-express',
  'white-label',
  'wordpress-seo',
  'wp-rest-yoast-meta'
];

$ecommercePlugins = [
  'woocommerce',
];

$paidPlugins = [
  'default' => [
    'advanced-custom-fields-pro',
    'capabilities-pro',
    'formidable',
    'formidable-api',
    'formidable-pro',
    'jwt-authentication-for-wp-rest-api', //! please do not update this plugin
    'permalink-manager-pro'
  ],
  'ecommerce' => [
    'cart-rest-api-for-woocommerce',
    'cocart-pro',
    'cocart-acf'
  ]
];

function generateRandomString($length = 10)
{
  return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

function changeEnv($key, $value, $default)
{
  global $envFile;
  if (!str_contains(
    $envFile,
    sprintf($key . '="' . $value . '"', $value),
  )) {
    $envFile = str_replace(
      $key . '="' . $default . '"',
      sprintf($key . '="' . $value . '"', $value),
      $envFile
    );
  }
}

if (class_exists('WP_CLI')) {
  class Kronos_CLI
  {
    /**
     * @when before_wp_load
     */
    public function install($args, $options)
    {
      global $rootDir;
      if ($_ENV['APP_ENV'] == 'dev') {
        //? Install WordPress database
        WP_CLI::line(WP_CLI::colorize('%BInstalling WordPress database%n'));
        exec("wp core install --url=" . $_ENV['API_BASE_URL'] . " --title='" . $_ENV['APP_NAME'] . "' --admin_user=agora --admin_password=Secret,20 --admin_email=kronos@agoravita.com --skip-email");

        //? Shuffle salts
        WP_CLI::runcommand('config shuffle-salts');

        //? Rewrite default permalink structure
        $defaultPermalinkStructure = '/%postname%/';
        WP_CLI::runcommand("rewrite structure $defaultPermalinkStructure");

        //? Activate default theme
        WP_CLI::runcommand("theme activate tailpress");

        if (count($options) && $options['fresh']) {
          //? Update WordPress core + database
          WP_CLI::runcommand("core update");
          WP_CLI::runcommand("core update-db");
          //? Delete default posts & pages
          exec("wp post delete $(wp post list --post_type='page' --format=ids) --force");
          exec("wp post delete $(wp post list --post_type='post' --format=ids) --force");
        }

        //? Create the homepage then set it as home by default
        exec("wp post create --post_type=page --post_status=publish --post_title='Accueil' --page_template='template-home.php'", $homepage);
        $homepage = preg_replace('/[^0-9]/', '', $homepage[0]);
        WP_CLI::runcommand("option update show_on_front page");
        WP_CLI::runcommand("option update page_on_front $homepage");

        //? Clone Kronos plugin
        exec('git clone --depth 1 --branch main git@avonline2.agoravita.com:kronos-framework/back-end/plugin.git wp-content/plugins/kronos', $outputGitClone, $returnGitClone);
        exec('rm -rf wp-content/plugins/kronos/.git');

        //? Download & activate plugins
        global $defaultPlugins;
        global $paidPlugins;
        $plugins = implode(" ", $defaultPlugins);
        WP_CLI::runcommand("plugin install $plugins --activate");
        $plugins = implode(" ", $paidPlugins['default']);
        WP_CLI::runcommand("plugin activate $plugins");

        //? Activate Kronos plugin
        WP_CLI::runcommand("plugin activate kronos");

        //? Configure Permalink Manager
        add_option('permalink-manager', [
          'general' => [
            'yoast_breadcrumbs' => 1,
          ]
        ]);

        //? Configure White Label plugin
        add_option('white_label_general', [
          'enable_white_label' => 'on',
          'wl_administrators' => ''
        ]);
        add_option('white_label_visual_tweaks', [
          'admin_color_scheme_enable' => 'off',
          'admin_color_scheme_menu_background' => '#23282d',
          'admin_color_scheme_menu_text' => '#ffffff',
          'admin_color_scheme_menu_highlight' => '#0073aa',
          'admin_color_scheme_submenu_background' => '#2c3338',
          'admin_color_scheme_submenu_text' => '#e5e5e5',
          'admin_color_scheme_submenu_highlight' => '#0073aa',
          'admin_color_scheme_notifications' => '#d54e21',
          'admin_color_scheme_links' => '#0073aa',
          'admin_color_scheme_buttons' => '#04a4cc',
          'admin_color_scheme_form_fields' => '#2271b1',
          'admin_howdy_replacment' => '',
          'admin_remove_wp_logo' => 'on',
          'admin_replace_wp_logo' => '',
          'admin_footer_credit' => '',
          'admin_javascript' => '',
        ]);
        add_option('white_label_login', [
          'business_name' => '',
          'business_url' => '',
          'login_logo_file' => $_ENV['API_BASE_URL'] . '/images/auth/defaultLogo.png',
          'login_logo_width' => '',
          'login_logo_height' => '',
          'login_background_file' => $_ENV['API_BASE_URL'] . '/images/auth/defaultBackground.jpg',
          'login_background_color' => '#f1f1f1',
          'login_box_background_color' => '#fff',
          'login_box_text_color' => '#444',
          'login_text_color' => '#555d66',
          'login_button_background_color' => '#007cba',
          'login_button_font_color' => '#fff',
          'login_custom_css' => '',
          'login_page_template' => 'left',
        ]);

        //? Configure webp express plugin
        update_option('webp-express-alter-html-options', [
          'replacement' => 'picture',
          'hooks' => 'ob',
          'only-for-webp-enabled-browsers' => false,
          'only-for-webps-that-exists' => false,
          'alter-html-add-picturefill-js' => true,
          'hostname-aliases' => [],
          'destination-folder' => 'mingled',
          'destination-extension' => 'append',
          'destination-structure' => 'image-roots',
          'scope' => ['uploads', 'themes'],
          'image-types' => 3,
          'prevent-using-webps-larger-than-original' => true
        ]);

        //? Switch language to fr_FR
        WP_CLI::runcommand("language core install fr_FR");
        WP_CLI::runcommand("site switch-language fr_FR");
        WP_CLI::runcommand("language plugin update --all");

        if (count($options) && $options['fresh']) {
          //? Update all plugins
          $plugins = implode(',', $paidPlugins['default']) . "," . implode(',', $paidPlugins['ecommerce']);
          WP_CLI::runcommand("plugin update --all --exclude=$plugins");
        }

        WP_CLI::runcommand('kronos configure');

        if (count($options) && $options['fresh']) {
          exec("rm -rf $rootDir/back/wp-config-sample.php");
          exec("rm -rf $rootDir/.git");
        }

        //? Install front dependencies
        if (!count($options)) {
          shell_exec('cd ../front && yarn install');
        }

        WP_CLI::success('Kronos project initialized!');
      } else {
        WP_CLI::error('You are not in dev environment, you must modify your .env file manually.');
      }
    }

    public function configure()
    {
      global $envPath;
      global $envFile;
      if (!$_ENV['JWT_AUTH_SECRET_KEY']) {
        WP_CLI::runcommand('config shuffle-salts');
        changeEnv('JWT_AUTH_SECRET_KEY', generateRandomString(50), '');
        // Save .env
        file_put_contents($envPath, $envFile);
      }

      // //? Set right permissions
      exec('chmod -R 755 ./');
      exec('chmod 644 .htaccess index.php wp-config.php');
      exec('chgrp -R www-data wp-content/uploads');
      exec('chmod -R 775 wp-content/uploads');

      // //? Search replace old vs new URL
      global $wpdb;
      $oldUrl = $wpdb->get_results("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'home'", OBJECT)[0]->option_value;
      $newUrl = $_ENV['API_BASE_URL'];
      WP_CLI::runcommand("search-replace $oldUrl $newUrl");

      //? Change colors of TailPress theme
      $themeFilePath = get_template_directory() . '/theme.json';
      $brandingFrontConfig = dirname(__DIR__, 2) . '/front/config/branding.json';

      if (file_exists($themeFilePath) && file_exists($brandingFrontConfig)) {
        $theme = json_decode(file_get_contents($themeFilePath));
        $branding = json_decode(file_get_contents($brandingFrontConfig));
        //! Set primary color
        $theme->settings->color->palette[0]->color = $branding->colors->primary;
        //! Set secondary color
        $theme->settings->color->palette[1]->color = $branding->colors->secondary;

        $theme = json_encode($theme, JSON_PRETTY_PRINT);
        file_put_contents($themeFilePath, $theme);
      }
    }

    public function update($args, $options)
    {
      if ($options) {
        switch (array_key_first($options)) {
          case 'wordpress':
            // WordPress only
            WP_CLI::line(WP_CLI::colorize('%B🚀 Updating WordPress only%n'));
            WP_CLI::runcommand('core update');
            WP_CLI::runcommand('core update-db');
            WP_CLI::success('✅ WordPress updated!');
            break;
          case 'plugins':
            // All plugins
            global $paidPlugins;
            WP_CLI::line(WP_CLI::colorize('%B🚀 Updating all plugins%n'));
            $plugins = implode(',', $paidPlugins['default']) . "," . implode(',', $paidPlugins['ecommerce']);
            WP_CLI::runcommand("plugin update --all --exclude=$plugins");
            WP_CLI::success('✅ WordPress plugins updated!');
            break;
          case 'cli':
            WP_CLI::line(WP_CLI::colorize('%B🚀 Updating Kronos CLI%n'));
            exec('git clone --depth 1 --branch master git@avonline2.agoravita.com:kronos-framework/back-end/core.git tmp');
            exec('cd tmp && git sparse-checkout set kronos.sh .rsyncignore .gitlab-ci.yml .gitignore back/cli');
            exec('rm -rf ../kronos.sh && cp tmp/kronos.sh ../kronos.sh');
            exec('rm -rf ../.rsyncignore && cp tmp/.rsyncignore ../.rsyncignore');
            exec('rm -rf ../.gitlab-ci.yml && cp tmp/.gitlab-ci.yml ../.gitlab-ci.yml');
            exec('rm -rf ../.gitignore && cp tmp/.gitignore ../.gitignore');
            exec('rm -rf cli && cp -r tmp/back/cli cli');
            exec('rm -rf tmp');
            WP_CLI::success('✅ Kronos CLI updated!');
            break;
          case 'kronos':
            // Kronos plugin only
            WP_CLI::line(WP_CLI::colorize('%B🚀 Updating Kronos plugin only%n'));
            exec("rm -rf wp-content/plugins/kronos");
            exec('git clone --depth 1 --branch main git@avonline2.agoravita.com:kronos-framework/back-end/plugin.git wp-content/plugins/kronos', $outputGitClone, $returnGitClone);
            exec('rm -rf wp-content/plugins/kronos/.git');
            WP_CLI::success('✅ Kronos plugin updated!');
            break;
          case 'front':
            // Front-End only
            WP_CLI::line(WP_CLI::colorize('%B🚀 Updating Front-End only%n'));
            exec("cd ../front && yarn upgrade");
            WP_CLI::success('✅ Front-End updated!');
            break;
          case 'all':
            // Whole Kronos
            global $paidPlugins;
            WP_CLI::line(WP_CLI::colorize('%B🚀 Updating Kronos in its entirety%n'));
            WP_CLI::runcommand('core update');
            WP_CLI::runcommand('core update-db');
            $plugins = implode(',', $paidPlugins['default']) . "," . implode(',', $paidPlugins['ecommerce']);
            WP_CLI::runcommand("plugin update --all --exclude=$plugins");
            exec("rm -rf wp-content/plugins/kronos");
            exec('git clone --depth 1 --branch main git@avonline2.agoravita.com:kronos-framework/back-end/plugin.git wp-content/plugins/kronos', $outputGitClone, $returnGitClone);
            exec('rm -rf wp-content/plugins/kronos/.git');
            exec("cd ../front && yarn upgrade");
            WP_CLI::success('✅ Kronos updated!');
            break;
          default:
            break;
        }
      } else {
        // Default -- Front + Kronos plugin only
        WP_CLI::line(WP_CLI::colorize('%B🚀 Updating Kronos (CLI + front + plugin Kronos)%n'));
        // Kronos plugin
        exec("rm -rf wp-content/plugins/kronos");
        exec('git clone --depth 1 --branch main git@avonline2.agoravita.com:kronos-framework/back-end/plugin.git wp-content/plugins/kronos', $outputGitClone, $returnGitClone);
        exec('rm -rf wp-content/plugins/kronos/.git');

        // CLI
        exec('git clone --depth 1 --branch master git@avonline2.agoravita.com:kronos-framework/back-end/core.git tmp');
        exec('cd tmp && git sparse-checkout set kronos.sh .rsyncignore .gitlab-ci.yml .gitignore back/cli');
        exec('rm -rf ../kronos.sh && cp tmp/kronos.sh ../kronos.sh');
        exec('rm -rf ../.rsyncignore && cp tmp/.rsyncignore ../.rsyncignore');
        exec('rm -rf ../.gitlab-ci.yml && cp tmp/.gitlab-ci.yml ../.gitlab-ci.yml');
        exec('rm -rf ../.gitignore && cp tmp/.gitignore ../.gitignore');
        exec('rm -rf cli && cp -r tmp/back/cli cli');
        exec('rm -rf tmp');

        // Front
        exec("cd ../front && yarn upgrade");
        WP_CLI::success('✅ Kronos (CLI + front + plugin Kronos) updated!');
      }
    }

    public function add($args, $options)
    {
      global $ecommercePlugins;
      global $paidPlugins;
      global $wpdb;

      if ($args) {
        switch (array_key_first($args)) {
          case 'commerce':
            changeEnv('FEATURE_ECOMMERCE', true, false);

            //? Install e-commerce plugins (free & paid)
            $plugins = implode(" ", $ecommercePlugins);
            WP_CLI::runcommand("plugin install $plugins --activate");
            $plugins = implode(" ", $paidPlugins['ecommerce']);
            WP_CLI::runcommand("plugin activate $plugins");

            //? Update WooCommerce
            WP_CLI::runcommand("plugin update woocommerce");
            WP_CLI::runcommand("wc update");

            //? Set default WooCommerce settings
            WP_CLI::runcommand("option update woocommerce_default_country FR");
            WP_CLI::runcommand("option update woocommerce_currency EUR");
            WP_CLI::runcommand("option update woocommerce_currency_pos right");

            //? Set WooCommerce default language to fr_FR
            WP_CLI::runcommand("language plugin install woocommerce fr_FR");

            //? Delete WooCommerce default posts
            $defaultWooCommercePages = ['Cart', 'Checkout', 'My account', 'Refund and Returns policy', 'Shop'];
            $ids = [];
            foreach ($defaultWooCommercePages as $page) {
              array_push($ids, get_page_by_title($page)->ID);
            }
            $ids = implode(' ', $ids);

            WP_CLI::runcommand("post delete $ids --force");
            break;
          default:
            break;
        }
      }
    }

    public function tests($args, $options)
    {
      $results = new stdClass;

      //? Test REST API
      $results->api = shell_exec('curl -s -o /dev/null -w "%{http_code}" ' . $_ENV['API_BASE_URL'] . '/wp-json/wp/v2/pages');
      $results->api == '200' ? WP_CLI::success('✅ API tested successfully.') : WP_CLI::error("⛔️ API returns an error $results->api.");

      //? Test Back-Office
      $results->bo = shell_exec('curl -s -o /dev/null -w "%{http_code}" ' . $_ENV['API_BASE_URL'] . '/wp-login.php');
      $results->bo == '200' ? WP_CLI::success('✅ Back-Office tested successfully.') : WP_CLI::error("⛔️ Back-Office returns an error $results->bo.");

      //? Test Front-Office
      $results->fo = shell_exec('curl -s -o /dev/null -w "%{http_code}" ' . $_ENV['API_BASE_URL']);
      $results->fo == '200' ? WP_CLI::success('✅ Front-Office tested successfully.') : WP_CLI::error("⛔️ Front-Office returns an error $results->fo.");

      //? Test SMTP variables
      if (
        $_ENV['SMTP_HOST'] == 'localhost' &&
        $_ENV['SMTP_PORT'] == '25' &&
        $_ENV['SMTP_AUTH'] == '0'
      ) {
        WP_CLI::success('✅ Email configuration tested successfully.');
      } else {
        WP_CLI::error('⛔️ Email configuration is invalid, please check the docs.');
      }

      //? Test send email
      wp_mail('kronos@agoravita.com', 'Test email CI/CD', 'Email successfully sent') ? WP_CLI::success('✅ Email successfully sent.') : WP_CLI::error('⛔️ Email was not sent.');
    }

    public function dump($args, $options)
    {
      global $backDir;

      date_default_timezone_set('Europe/Paris');
      $date = date('d_m_Y_H\hi');
      WP_CLI::runcommand("db export --all-tablespaces --single-transaction --quick --lock-tables=false - | gzip -9 - > db/" . $_ENV['DB_NAME'] . "_$date.sql.gz");

      $files = scandir('db', SCANDIR_SORT_DESCENDING);
      foreach ($files as $key => $file) {
        if ($key > 1 && $file != '.' && $file != '..' && $file != '.gitkeep') {
          unlink("$backDir/db/$file");
        }
      }
    }
  }

  WP_CLI::add_command('kronos', 'Kronos_CLI');
}
