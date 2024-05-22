<?php
//? Fix URL to import a csv file through ftp/ssh
function override_wp_ai_home_url()
{
    wp_add_inline_script('pmxi-ftp-browser-script', 'var wpai_home_url=\'' . get_site_url() . '\'');
}
add_action('admin_init', 'override_wp_ai_home_url');


//? Import Kronos ACF default fields once
function kronos_import_default_acf_fields()
{
    if (is_dir(WP_PLUGIN_DIR . '/kronos/import/fields/') && (!get_option('import_default_kronos_acf_fields') || get_option('import_default_kronos_acf_fields') == '0')) {
        $dir = new DirectoryIterator(WP_PLUGIN_DIR . '/kronos/import/fields/');
        foreach ($dir as $file) {
            if (!$file->isDot() && 'json' == $file->getExtension()) {
                $array = json_decode(file_get_contents($file->getPathname()), true);
                foreach ($array as $field_group) {
                    // Import field group.
                    $field_group = acf_import_field_group($field_group);
                }
            }
        }
        add_option('import_default_kronos_acf_fields', 1);
    }
}
add_action('init', 'kronos_import_default_acf_fields');
