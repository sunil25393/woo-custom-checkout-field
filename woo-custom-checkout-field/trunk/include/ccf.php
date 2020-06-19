<?php

global $db_version;
$db_version = '1.0';

/* * *********************** Create database table ************************ */

function db_install() {
       global $wpdb;
       global $db_version;

       $table_name = $wpdb->prefix . 'ccf';

       $charset_collate = $wpdb->get_charset_collate();

       $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		field_name tinytext NOT NULL,
		field_id tinytext NOT NULL,
              field_class tinytext NOT NULL,
              field_options text NOT NULL,
		field_type enum('text','checkbox','select','radio','textarea') default 'text' '' NOT NULL,
              field_required enum('0','1') default '0' '' NOT NULL,
              field_includeemail enum('0','1') default '0' '' NOT NULL,
              field_placeholder tinytext NOT NULL,
           UNIQUE KEY id (id)
	) $charset_collate;";

       require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
       dbDelta($sql);
}

function update_db_13() {
       global $wpdb;
       global $db_version;

       $table_name = $wpdb->prefix . 'ccf';

       $ccf_list = $wpdb->get_results("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$wpdb->dbname}' AND TABLE_NAME = '{$wpdb->prefix}ccf' AND COLUMN_NAME = 'field_placeholder' ", ARRAY_A);

       if (empty($ccf_list) && count($ccf_list) == 0) {

              $sql = "ALTER TABLE  `$table_name` ADD `field_placeholder` tinytext;";
              $wpdb->query($sql);
       }
       return 1;
}

/* * *********************** remove database table on plugin uninstall ************************ */

function db_uninstall() {
       global $wpdb;
       global $db_version;
       if (get_option('cff_field_order')) {
              delete_option('cff_field_order');
       }
       $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ccf");
}

/* * *********************** Save Enable status of cff field ************************ */

function save_enable_status() {

       if (!get_option('cff_field_enable')) {
              if (isset($_REQUEST['en']) && $_REQUEST['en'] != '') {
                     $ccf = array();
                     array_push($ccf, $_REQUEST['en']);
                     add_option('cff_field_enable', $ccf);
              }
       } else {
              if (isset($_REQUEST['en']) && $_REQUEST['en'] != '') {
                     $ccf = get_option('cff_field_enable');
                     array_push($ccf, $_REQUEST['en']);
                     update_option('cff_field_enable', $ccf);
              } else if (isset($_REQUEST['ds']) && $_REQUEST['ds'] != '') {
                     $id = $_REQUEST['ds'];
                     $ccf = get_option('cff_field_enable');
                     $f = array_flip($ccf);
                     $cid_k = $f[$id];
                     unset($ccf[$cid_k]);
                     update_option('cff_field_enable', $ccf);
              }
       }
}

/* * *********************** Save Order of data in table ************************ */

function ccf_save_order($postfield) {

       if (!get_option('cff_field_order')) {
              add_option('cff_field_order', $postfield['f_order']);
       } else {
              update_option('cff_field_order', $postfield['f_order']);
       }
}

/* * *********************** Save Order of data in table ************************ */

function create_ccf_enable_status_and_order() {
       $all_new = ccf_getall();
       $order = array();
       $enable = array();

       if (!empty($all_new) && !get_option('cff_field_order')) {
              $i = 0;
              foreach ($all_new as $field) {
                     $order[$field['id']] = $i;
                     array_push($enable, $field['id']);
                     $i++;
              }
              add_option('cff_field_order', $order);
              add_option('cff_field_enable', $enable);
       }
}

/* * *********************** Insert data in table ************************ */

function ccf_insert($postfield) {

       global $wpdb;
       $table_name = $wpdb->prefix . 'ccf';

       if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
              db_install();
       }

       $field_name = sanitize_text_field($postfield['txt_field_name']);
       $field_id = ccf_field_random_id();
       $field_class = sanitize_text_field($postfield['txt_field_class']);
       $field_type = sanitize_text_field($postfield['txt_field_type']);
       $field_options = sanitize_text_field($postfield['txt_field_options']);
       $field_required = isset($postfield['txt_field_required']) ? '1' : '0';
       $field_includemail = isset($postfield['txt_field_add_email']) ? '1' : '0';
       $field_placeholder = sanitize_text_field($postfield['txt_field_placeholder']);

       $wpdb->insert(
               $table_name, array(
           'field_name' => $field_name,
           'field_id' => $field_id,
           'field_class' => $field_class,
           'field_type' => $field_type,
           'field_options' => $field_options,
           'field_required' => $field_required,
           'field_includeemail' => $field_includemail,
           'field_placeholder' => $field_placeholder,
               )
       );
       $lastInsertId = $wpdb->insert_id;
       $order = array();

       if (!get_option('cff_field_order')) {
              $ccf = array();
              $ccf[$lastInsertId] = 0;
              add_option('cff_field_order', $ccf);
       } else {
              $order = get_option('cff_field_order');
              $key = count($order) + 1;
              $order[$lastInsertId] = $key;
              update_option('cff_field_order', $order);
       }

       if (!get_option('cff_field_enable')) {
              $ccf = array();
              array_push($ccf, $lastInsertId);
              add_option('cff_field_enable', $ccf);
       } else {
              $ccf = get_option('cff_field_enable');
              array_push($ccf, $lastInsertId);
              update_option('cff_field_enable', $ccf);
       }



       ccf_insert_success_msg();
}

/* * *********************** Update data in table ************************ */

function ccf_update($postfield) {

       global $wpdb;
       $table_name = $wpdb->prefix . 'ccf';

       if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
              db_install();
       }

       $id = sanitize_text_field($postfield['field_id']);
       $field_name = sanitize_text_field($postfield['txt_field_name']);
       $field_class = sanitize_text_field($postfield['txt_field_class']);
       $field_type = sanitize_text_field($postfield['txt_field_type']);
       $field_options =sanitize_text_field($postfield['txt_field_options']);
       $field_required = isset($postfield['txt_field_required']) ? '1' : '0';
       $field_includemail = isset($postfield['txt_field_add_email']) ? '1' : '0';
       $field_placeholder = sanitize_text_field($postfield['txt_field_placeholder']);

       $wpdb->update(
               $table_name, array(
           'field_name' => $field_name,
           'field_class' => $field_class,
           'field_type' => $field_type,
           'field_options' => $field_options,
           'field_required' => $field_required,
           'field_includeemail' => $field_includemail,
           'field_placeholder' => $field_placeholder,
               ), array('id' => $id)
       );
//       $lastInsertId = $wpdb->insert_id;
       ccf_insert_success_msg();
       return;
}

/* * *********************** Generate random id for ccf field ************************ */

function ccf_field_random_id() {

       $random = uniqid();
       return 'ccf_' . $random;
}

/* * *********************** Get all ccf field ************************ */

function ccf_getall() {
       global $wpdb;

       $ccf_list = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ccf ", ARRAY_A);
       return $ccf_list;
}

/* * *********************** Get ccf field  by id ************************ */

function ccf_field_by_id($id) {

       global $wpdb;
       $table = $wpdb->prefix . 'ccf';

       $ccf_list = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ccf where id=" . $id, ARRAY_A);
       return $ccf_list[0];
}

/* * *********************** Delete ccf field  by id ************************ */

function ccf_field_delete($id) {
       global $wpdb;
       $table = $wpdb->prefix . 'ccf';

       $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}ccf WHERE id = %d", $id));

       if (get_option('cff_field_order')) {
              $order = get_option('cff_field_order');
              unset($order[$id]);
              update_option('cff_field_order', $order);
       }
//       header('Location: ' . esc_url(remove_query_arg(array('del'), current_page_url())));
}

/* * *********************** execute function to add field to checkout and proccess.  ************************ */
add_action('init', 'ccf_add_on_checkout');

function ccf_add_on_checkout() {
       add_action('woocommerce_after_order_notes', 'ccf_field_display');

       add_action('woocommerce_checkout_process', 'ccf_process');

       add_action('woocommerce_checkout_update_order_meta', 'ccf_update_order_meta');

       add_action('woocommerce_admin_order_data_after_billing_address', 'ccf_display_admin_order_meta', 10, 1);

       add_filter('woocommerce_email_order_meta_keys', 'ccf_order_meta_keys');
}

/* * *********************** Display ccf field to checkout form.  ************************ */

function ccf_field_display($checkout) {
       $fields = ccf_getall();
       $ccf_enable = get_option('cff_field_enable');
       foreach ($fields as $id => $result) {
              $fields[$result['id']] = $result;
       }

       if (!empty($fields)) {

              if (get_option('cff_field_order')) {
                     $field_order = get_option('cff_field_order');
                     $field_order = array_flip($field_order);

                     foreach ($field_order as $fo) {
                            if (in_array($fo, $ccf_enable)) {

                                   $field = $fields[$fo];

                                   $field_required = ($field['field_required'] == '1') ? true : false;

                                   echo '<div id="my_custom_checkout_field">';

                                   if ($field['field_type'] == 'text' || $field['field_type'] == 'textarea') {
                                          //for text field

                                          woocommerce_form_field($field['field_id'], array(
                                              'type' => $field['field_type'],
                                              'class' => array($field['field_class']),
                                              'label' => $field['field_name'],
                                              'required' => $field_required,
                                              'placeholder' => __($field['field_placeholder']),
                                                  ), $checkout->get_value($field['field_id']));
                                   } elseif ($field['field_type'] == 'select') {
                                          //for select field
                                          $field_options = array_combine(explode(',', $field['field_options']), explode(',', $field['field_options']));
                                          $field_options_first_option[''] = __($field['field_placeholder']);
                                          $field_options = array_merge($field_options_first_option, $field_options);

                                          woocommerce_form_field($field['field_id'], array(
                                              'type' => $field['field_type'],
                                              'class' => array($field['field_class']),
                                              'label' => $field['field_name'],
                                              'required' => $field_required,
                                              'options' => $field_options,
                                                  ), $checkout->get_value($field['field_id']));
                                   } elseif ($field['field_type'] == 'checkbox') {
                                          //for checkbox field
                                          woocommerce_form_field($field['field_id'], array(
                                              'type' => $field['field_type'],
                                              'class' => array($field['field_class']),
                                              'label' => $field['field_name'],
                                              'required' => $field_required,
                                                  ), $checkout->get_value($field['field_id']));
                                   } elseif ($field['field_type'] == 'radio') {
                                          //for radio field
                                          $field_options = array_combine(explode(',', $field['field_options']), explode(',', $field['field_options']));
                                          woocommerce_form_field($field['field_id'], array(
                                              'type' => $field['field_type'],
                                              'class' => array($field['field_class']),
                                              'label' => $field['field_name'],
                                              'required' => $field_required,
                                              'options' => $field_options,
                                                  ), $checkout->get_value($field['field_id']));
                                   }

                                   echo '</div>';
                            }
                     }
              }
       }
}

/* * *********************** Display ccf field to checkout form.  ************************ */

function ccf_process() {
       $fields = ccf_getall();

       if (!empty($fields)) {
              foreach ($fields as $field) {
                     $id = $field['field_id'];
                     if ($field['field_required'] == '1') {
                            if (!$_POST[$id])
                                   wc_add_notice(__('Please enter something into ' . $field['field_name'] . '.'), 'error');
                     }
              }
       }
}

/* * *********************** Save ccf field to database.  ************************ */

function ccf_update_order_meta($order_id) {
       $fields = ccf_getall();

       if (!empty($fields)) {
              foreach ($fields as $field) {
                     if ($field['field_type'] == 'checkbox') {
                            $f_id = $field['field_id'];
                            if ($_POST[$f_id] == 1) {
                                   update_post_meta($order_id, $field['field_name'], 'yes');
                            } else {
                                   update_post_meta($order_id, $field['field_name'], 'no');
                            }
                     } else {
                            $f_id = $field['field_id'];
                            if (!empty($_POST[$f_id])) {
                                   update_post_meta($order_id, $field['field_name'], sanitize_text_field($_POST[$f_id]));
                            }
                     }
              }
       }
}

/* * *********************** Display ccf field value to  customer order detail in admin panel.  ************************ */

function ccf_display_admin_order_meta($order) {
       $fields = ccf_getall();
       if (!empty($fields)) {
              foreach ($fields as $field) {
                     echo '<p><strong>' . __($field['field_name']) . ':</strong> ' . get_post_meta($order->id, $field['field_name'], true) . '</p>';
              }
       }
}

/* * *********************** Display ccf field value to customer order invoice.  ************************ */

function ccf_order_meta_keys($keys) {
       $fields = ccf_getall();
       if (!empty($fields)) {
              foreach ($fields as $field) {
                     if ($field['field_includeemail'] == '1') {
                            $keys[] = $field['field_name'];
                     }
              }
       }
       return $keys;
}

?>
