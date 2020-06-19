<?php
/**
 * Plugin Name: Woo Checkout Custom Field
 * Plugin URI: #
 * Description: This plugin adds functionality to woocommerce plugin to add custom checkout field.
 * Version: 1.3.5
 * Author: auratechmind
 * Author URI: #
 * License: GPL2
 * Text Domain: woo-custom-checkout-field
 */
/* * ********** constant ddefine ******************* */
define('CCF_VERSION', '1.3.5');

define('CCF_REQUIRED_WP_VERSION', '3.2');

define('CCF_PLUGIN', __FILE__);

define('CCF_PLUGIN_BASENAME', plugin_basename(CCF_PLUGIN));

define('CCF_PLUGIN_NAME', trim(dirname(CCF_PLUGIN_BASENAME), '/'));

define('CCF_PLUGIN_DIR', untrailingslashit(dirname(CCF_PLUGIN)));

require_once CCF_PLUGIN_DIR . '/include/function.php';
require_once CCF_PLUGIN_DIR . '/include/ccf.php';

function ccf_update_db_check() {

       update_db_13();
       if (get_option('ccf_plugin_version')) {
              update_option('ccf_plugin_version', CCF_VERSION);
       } else {
              add_option('ccf_plugin_version', CCF_VERSION);
       }
}

add_action('plugins_loaded', 'ccf_update_db_check');


/* * *********************** load translatable files ************************ */
//add_action('plugins_loaded', 'sp_wc_donations_language');
//
//function sp_wc_donations_language() {
//    load_plugin_textdomain('woo-custom-checkout-field', false, dirname(plugin_basename(__FILE__)) . '/language/');
//}

/* * *********************** add style ************************ */
add_action('admin_init', 'ccf_enqueued_style');

function ccf_enqueued_style() {
       if (is_admin()) {
              wp_enqueue_style('ccf_custom', ccf_plugin_url('css/ccf_custom.css'), array(), CCF_VERSION, 'all');
       }
}

/* * *********************** add script scripts ************************ */
add_action('admin_init', 'ccf_enqueued_scripts');

function ccf_enqueued_scripts() {
       if (is_admin()) {
              wp_enqueue_script('ccf_script', ccf_plugin_url('js/ccf_scripts.js'), array('jquery', 'jquery-ui-sortable', 'woocommerce_admin', 'jquery-tiptip'), '1.0', true);
//            wp_enqueue_script('thwcfd-admin-script', ccf_plugin_url('js/ccf_admin.js'), array('jquery', 'jquery-ui-dialog', 'jquery-ui-sortable', 'woocommerce_admin', 'jquery-tiptip'), '1.0', true);
       }
}

/* * *********************** add admin menu ************************ */

add_action('admin_menu', 'register_my_custom_submenu_page', 99);

function register_my_custom_submenu_page() {
       $suffix = add_submenu_page('woocommerce', 'Custom Checkout Field', 'Custom Checkout Field', 'manage_options', 'ccf_settings_menu', 'ccf_settings_page');
}

/* * *********************** add admin menu page ************************ */

function ccf_settings_page() {
       create_ccf_enable_status_and_order();
       if (isset($_REQUEST['_wpnonce'])) {
              $nonce = $_REQUEST['_wpnonce'];
              if (wp_verify_nonce($nonce, 'ccf_ends_action')) {
                     if (isset($_REQUEST['en']) || isset($_REQUEST['ds'])) {
                            save_enable_status($_POST);
                            wp_redirect(menu_page_url('ccf_settings_menu', FALSE));
                     }
              } else {
                     wp_redirect(menu_page_url('ccf_settings_menu', FALSE));
              }
       }

       if ($_POST) {
              $retrieved_nonce = $_REQUEST['_wpnonce'];
              if (!wp_verify_nonce($retrieved_nonce, 'ccf_submit'))
                     die('Failed security check');
       }
       if ($_POST && isset($_POST['save_fields'])) {
              ccf_save_order($_POST);
              wp_redirect(menu_page_url('ccf_settings_menu', FALSE));
       }
       if ($_POST && isset($_POST['add_field'])) {
              ccf_insert($_POST);
              wp_redirect(menu_page_url('ccf_settings_menu', FALSE));
       }
       if ($_POST && isset($_REQUEST['edit']) && isset($_POST['edit_field'])) {
              ccf_update($_POST);
              wp_redirect(menu_page_url('ccf_settings_menu', FALSE));
       }
       if (isset($_REQUEST['del']) && $_REQUEST['del'] != '') {
              $d_id = $_REQUEST['del'];
              ccf_field_delete($d_id);
              wp_redirect(menu_page_url('ccf_settings_menu', FALSE));
       }

       do_action('ccf_admin_page');
}

/* * *********************** ccf plugin activation hook ************************ */
register_activation_hook(__FILE__, 'ccf_activation');

function ccf_activation() {
       add_option('ccf_plugin_version', CCF_VERSION);
       add_action('admin_notices', 'my_plugin_activation_notice');
       db_install();
}

/* * *********************** ccf plugin deactivation hook ************************ */
register_deactivation_hook(__FILE__, 'ccf_deactivation');

function ccf_deactivation() {
       add_action('admin_notices', 'my_plugin_deactivation_notice');
       add_option('ccf_plugin_activation', true);
}

/* * *********************** ccf plugin uninstall hook ************************ */
register_uninstall_hook(__FILE__, 'ccf_uninstall');

function ccf_uninstall() {
       add_action('admin_notices', 'my_plugin_uninstall_notice');
       db_uninstall();
}

function ccf_plugin_url($path = '') {
       $url = plugins_url($path, CCF_PLUGIN);

       if (is_ssl() && 'http:' == substr($url, 0, 5)) {
              $url = 'https:' . substr($url, 5);
       }
       return $url;
       delete_option('ccf_plugin_activation');
}

?>
