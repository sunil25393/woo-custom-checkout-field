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
           UNIQUE KEY id (id)
	) $charset_collate;";

       require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
       dbDelta($sql);
}

/* * *********************** remove database table on plugin uninstall ************************ */

function db_uninstall() {
       global $wpdb;
       global $db_version;

       $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ccf");
}

/* * *********************** Insert data in table ************************ */

function ccf_insert($postfield) {

       global $wpdb;
       $table_name = $wpdb->prefix . 'ccf';

       if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
              db_install();
       }

       $field_name = $postfield['txt_field_name'];
       $field_id = ccf_field_random_id();
       $field_class = $postfield['txt_field_class'];
       $field_type = $postfield['txt_field_type'];
       $field_options = $postfield['txt_field_options'];
       $field_required = isset($postfield['txt_field_required']) ? '1' : '0';
       $field_includemail = isset($postfield['txt_field_required']) ? '1' : '0';

       $wpdb->insert(
               $table_name, array(
           'field_name' => $field_name,
           'field_id' => $field_id,
           'field_class' => $field_class,
           'field_type' => $field_type,
           'field_options' => $field_options,
           'field_required' => $field_required,
           'field_includeemail' => $field_includemail,
               )
       );
//       $lastInsertId = $wpdb->insert_id;
       ccf_insert_success_msg();
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
       return $ccf_list;
}

/* * *********************** Delete ccf field  by id ************************ */

function ccf_field_delete($id) {
       global $wpdb;
       $table = $wpdb->prefix . 'ccf';
       $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}ccf WHERE id = %d", $id));
       header('Location: ' . esc_url(remove_query_arg(array('del'), current_page_url())));
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

       if (!empty($fields)) {
              foreach ($fields as $field) {


                     $field_required = ($field['field_required'] == '1') ? true : false;

                     echo '<div id="my_custom_checkout_field">';

                     if ($field['field_type'] == 'text') {
                            //for text field

                            woocommerce_form_field($field['field_id'], array(
                                'type' => $field['field_type'],
                                'class' => array($field['field_class']),
                                'label' => $field['field_name'],
                                'required' => false,
                                'placeholder' => __('Enter ' . $field['field_name']),
                                    ), $checkout->get_value($field['field_id']));
                     } elseif ($field['field_type'] == 'select') {
                            //for select field
                            $field_options = array_combine(explode(',', $field['field_options']), explode(',', $field['field_options']));
                            $field_options_first_option[''] = 'Select ' . $field['field_name'];
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
                     $f_id = $field['field_id'];
                     if (!empty($_POST[$f_id])) {
                            update_post_meta($order_id, $field['field_name'], sanitize_text_field($_POST[$f_id]));
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
