<?php

//? Hide
add_action('rest_api_init', function () {

    register_rest_route(
        'wp/v2/form',
        '/upload',
        array(
            'methods'  => 'POST',
            'callback' => 'upload_files',
            'permission_callback' => function () {
                return true;
            }
        )
    );
});

function create_form_entry($id, $entry, $form)
{
    FrmEntry::create(array(
        'form_id' => $id,
        'item_key' => $entry,
        'item_meta' => $form,
    ));
}

function upload_files($req)
{
    if (!intval($req['id'])) {
        $req['id'] = FrmForm::get_id_by_key($req['id']);
    }
    $remove_params = ['directory', 'extensions', 'fields', 'id', 'entry'];
    $form_data = array_diff_key(array_merge($req->get_params(), $req->get_file_params()), array_flip($remove_params));
    $form = [];
    foreach ($form_data as $key => $value) {
        $form[FrmField::get_id_by_key($key)] = $value;
    }
    $directory = $req['directory'];
    $upload_dir = wp_upload_dir();
    $files_ids = [];
    $skip_meta = true;

    // Upload files
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    foreach ($req->get_file_params() as $potential_key => $file) {
        $regexp_patter_identify_arrays = '/-[\d]{1,2}$/';
        $matches = preg_match($regexp_patter_identify_arrays, $potential_key);
        // Then our key is not this one but the striped thing
        $key = $potential_key;
        if ($matches == 1) {
            $key = substr($potential_key, 0, strrpos($key, '-'));
        }


        $file_field_id = FrmField::get_id_by_key($key);
        if (!isset($files_ids[$file_field_id])) {
            $files_ids[$file_field_id] = [];
        }

        $original_file_name = basename($file['name']);
        $file_ext = pathinfo($original_file_name, PATHINFO_EXTENSION);
        $file_name = wp_generate_password(32, false) . ".$file_ext";
        $file_temp = $file['tmp_name'];
        $image_data = file_get_contents($file_temp);

        if (wp_mkdir_p($upload_dir['basedir'] . "/$directory")) {
            $file = $upload_dir['basedir'] . "/$directory/" . $file_name;
        }

        file_put_contents($file, $image_data);

        $wp_filetype = wp_check_filetype($file_name, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($file_name),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $file);
        if (!$skip_meta) {
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);
        }

        $files_ids[$file_field_id][] = $attach_id;
    }

    foreach ($files_ids as $field_id => $files) {
        $form[$field_id] = $files;
    }
    create_form_entry($req['id'], $req['entry'], $form);
}
