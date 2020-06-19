<?php
/* * *********************** Hook to add before page ************************ */
add_action('ccf_admin_page', 'ccf_before_page', 0);

if (!function_exists('ccf_before_page')) {

       function ccf_before_page() {
              echo '<div class="wrap">';
       }

}

/* * *********************** Hook to add ccf page title ************************ */
add_action('ccf_admin_page', 'ccf_admin_page_title', 10);

if (!function_exists('ccf_admin_page_title')) {

       function ccf_admin_page_title() {
              echo '<h2 class="ccf_title">Custom Checkout Field ';
              if (isset($_REQUEST['edit'])) {
                     echo '<a  class="btn button-primary" href="' . menu_page_url("ccf_settings_menu", false) . '">Add new field</a>';
              }
              echo ' </h2>';
       }

}

/* * *********************** Hook to add ccf form ************************ */
add_action('ccf_admin_page', 'ccf_admin_form', 20);

if (!function_exists('ccf_admin_form')) {

       function ccf_admin_form() {
              load_template(CCF_PLUGIN_DIR . '/template/form.php');
       }

}

/* * *********************** Hook to add ccf field grid ************************ */
add_action('ccf_admin_page', 'ccf_admin_datagrid', 30);

if (!function_exists('ccf_admin_datagrid')) {

       function ccf_admin_datagrid() {
              load_template(CCF_PLUGIN_DIR . '/template/datagrid.php');
       }

}


/* * *********************** Hook to add close tag after page  ************************ */
add_action('ccf_admin_page', 'ccf_after_page', 100);

if (!function_exists('ccf_after_page')) {

       function ccf_after_page() {
              echo '</div>';
       }

}

/* * *********************** Success message on new field successful addition. ************************ */

function ccf_insert_success_msg() {
       ?>
       <div class="updated"><p><strong><?php _e('New field inserted.', 'menu-test'); ?></strong></p></div>
       <?php
       return;
}

/* * *********************** Error message to show when woocommerce is not installed in your website ************************ */

function my_error_notice() {
       ?>
       <div class="error notice">
           <p><?php _e(' In order to use <b>Custom Checkout Plugin</b>, You need to install <a href="https://wordpress.org/plugins/woocommerce/" alt="wocommerce">woocommerce</a> plugin.', 'my_plugin_textdomain'); ?></p>
       </div>
       <?php
}

/* * *********************** Message will display on uninstallation of your plugin ************************ */

function my_plugin_uninstall_notice() {
       ?>
       <div class="success notice">
           <p><?php _e(' Custom checkout Field plugin uninstalled successfully.', 'my_plugin_textdomain'); ?></p>
       </div>
       <?php
}

/* * *********************** Message will display on activation of your plugin ************************ */

function my_plugin_activation_notice() {
       ?>
       <div class="success notice">
           <p><?php _e(' Custom checkout Field plugin activated successfully.', 'my_plugin_textdomain'); ?></p>
       </div>
       <?php
}

/* * *********************** Message will display on deactivation of your plugin ************************ */

function my_plugin_deactivation_notice() {
       ?>
       <div class="success notice">
           <p><?php _e(' Custom checkout Field plugin deactivated successfully.', 'my_plugin_textdomain'); ?></p>
       </div>
       <?php
}

/* * *********************** TO check woocommerce plugin existance ************************ */
add_action('admin_init', 'check_woocommerce_activation');

function check_woocommerce_activation() {
       if (!class_exists('WooCommerce')) {
              add_action('admin_notices', 'my_error_notice');
       }
}

/* * *********************** get  plugin current page url ************************ */

function current_page_url() {
       $pageURL = 'http';
       if (isset($_SERVER["HTTPS"])) {
              if ($_SERVER["HTTPS"] == "on") {
                     $pageURL .= "s";
              }
       }
       $pageURL .= "://";
       if ($_SERVER["SERVER_PORT"] != "80") {
              $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
       } else {
              $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
       }
       return $pageURL;
}
?>
