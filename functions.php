<?php


function click5_wpf_get_available_forms() {
  if ( class_exists('wpforms') ) {
      $args = [
        'post_type' => 'wpforms',
        'post_status' => 'publish',
        'posts_per_page' => -1,
      ];
      $posts = wpforms()->form->get();
      $forms = wp_list_pluck( $posts, 'post_title', 'ID');

      return $forms;
  }
}

function click5_wpf_get_available_forms_id() {
  if ( class_exists('wpforms') ) {
      $args = [
        'post_type' => 'wpforms',
        'post_status' => 'publish',
        'posts_per_page' => -1,
      ];
      $posts = wpforms()->form->get();
      $forms = wp_list_pluck( $posts, 'ID' );

      return $forms;
  }
}

function click5_wpf_is_selected($option_name, $value) {
  return esc_attr(get_option($option_name)) == $value;
}

function click5_wpf_is_mapped($option_name) {
  $str_option = esc_attr(get_option($option_name));
  return $str_option !== '_undefined_' && strlen($str_option);
}

function click5_wpf_get_enabled_forms() {
  $available_forms = click5_wpf_get_available_forms_id();
  if ( class_exists('wpforms') ) {
    $posts = $available_forms;
    $array = array();
    foreach ($posts as $post) {
      if (boolval(get_option('click5_wpf_addon_form_enable_'.$post))) {
        $array[$post] = get_the_title($post);
      }
    }
    return $array;
  }
}

function click5_wpf_get_all_forms() {
  $allForms = click5_wpf_get_available_forms();
  $enabledForms = click5_wpf_get_enabled_forms();

  $result_array = array();

  foreach($allForms as $key => $title) {
    $is_enabled = false;
    foreach($enabledForms as $enabled_key => $enabled_title) {
      if ($key == $enabled_key) {
        $is_enabled = true;
      }
    }

    $result_array[$key] = array('title' => $title, 'is_enabled' => $is_enabled);
  }

  return $result_array;
}

function click5_wpf_get_available_crm_fields() {
  //request
  $array_fields = (array)json_decode(get_option('click5_wpf_addon_crm_fields_stored'));

  $isEmpty = true;

  if ($array_fields) {
    if (count($array_fields)) {
      $isEmpty = false;
    }
  }

  return !$isEmpty ? $array_fields : array(array('parameter' => '_undefined_', 'label' => 'Please enter the Posting URL first', 'is_custom' => false));
}

function click5_wpf_get_form_fields($id) {
  $form = get_post($id);
  if ( class_exists('wpforms') ) {
    if (!empty($form)) {
      $form_data = apply_filters( 'wpforms_frontend_form_data', wpforms_decode( $form->post_content ) );
      $all_fields = $form_data['fields'];
      $result = array();
      foreach ($all_fields as $value) {
        if(isset($value['required'])){
          $required = true;
        } else {
          $required = false;
        }
        $result[] = array(
          'type' => $value['type'],
          'name' => $value['label'],
          'required' => $required
        );
      }
      return $result;
    } else {
      return array();
    }
  }
}

function click5_wpf_get_const_values($form_id) {
  $allConstValues = (array)(json_decode(get_option('click5_wpf_addon_const_values')));
  $resultArray = array();

  foreach($allConstValues as $const_value) {
    $const_value = (array)$const_value;

    if ($const_value['form_id'] == $form_id) {
      $resultArray[] = $const_value;
    }
  }

  return $resultArray;
}


?>