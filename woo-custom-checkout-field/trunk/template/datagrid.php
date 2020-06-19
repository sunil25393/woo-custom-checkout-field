<?php
require_once CCF_PLUGIN_DIR . '/include/ccf.php';

$array_fields = array();
if (get_option('cff_field_order')) {
       $array_fields = get_option('cff_field_order');
       $array_fields = array_flip($array_fields);
}

//delete_option('cff_field_order');
//print_r($array_fields);
$all_new = ccf_getall();

foreach ($all_new as $id => $result) {
       $all[$result['id']] = $result;
}

//echo '<pre>';
$ccf_enable = get_option('cff_field_enable');
//print_r($ccf);
//echo '</pre>';
//exit();
$ccf_ends_action_nonce = wp_create_nonce('ccf_ends_action');
?>
<div class="div70">
    <div class="ccf">  
        <form  method="post" role="form">
            <?php wp_nonce_field('ccf_submit'); ?>
            <table class="wp-list-table widefat plugins ccf_checkout_fields ">
                <thead>
                    <tr>
                        <th class="sort"></th>
                        <!--<th class="select_all"><input type="checkbox" style="margin-left:0px;" onclick="thwcfdSelectAllCheckoutFields(this)"/></th>-->
                        <th  class="titledesc">
                            <label><?php _e("Name", 'menu-test'); ?> </label>
                        </th>
                        <th scope="row" class="titledesc">
                            <label><?php _e("Class", 'menu-test'); ?> </label>
                        </th>
                        <th scope="row" class="titledesc">
                            <label><?php _e("type", 'menu-test'); ?> </label>
                        </th>
                        <th scope="row" class="titledesc">
                            <label><?php _e("Required", 'menu-test'); ?> </label>
                        </th>
                        <th scope="row" class="titledesc">
                            <label><?php _e("Add in mail", 'menu-test'); ?> </label>
                        </th>
                        <th scope="row" class="titledesc">
                            <label><?php _e("Enable", 'menu-test'); ?> </label>
                        </th>
                        <th scope="row" class="titledesc">
                            <label><?php _e("Action", 'menu-test'); ?> </label>
                        </th>
                    </tr>
                </thead>
                <tbody class="ui-sortable">
                    <?php
                    if (!empty($array_fields) && !empty($all)) {
                           $i = 0;

                           foreach ($array_fields as $value) {
                                  ?>
                                  <tr valign="top">
                                      <td class="sort ui-sortable-handle">
                                          <input type="hidden" name="f_order[<?php echo esc_attr($all[$value]['id']); ?>]" class="f_order" value="<?php echo $i; ?>">
                                      </td>
                                      <!--<td class="td_select"><input type="checkbox" name="select_field"/></td>-->
                                      <td scope="row" class="titledesc">
                                          <label><?php echo htmlentities($all[$value]['field_name']); ?> </label>
                                      </td>
                                      <td class="forminp forminp-select">
                                          <label><?php echo htmlentities($all[$value]['field_class']); ?> </label>
                                      </td>
                                      <td class="forminp forminp-select">
                                          <label><?php echo htmlentities($all[$value]['field_type']); ?> </label>
                                      </td>
                                      <td class="forminp forminp-select">
                                          <label><?php echo ($all[$value]['field_required'] == '1') ? '<span class="dashicons dashicons-yes"></span>' : ''; ?> </label>
                                      </td>
                                      <td class="forminp forminp-select">
                                          <label><?php echo ($all[$value]['field_includeemail'] == '1') ? '<span class="dashicons dashicons-yes"></span>' : ''; ?> </label>
                                      </td>
                                      <td class="forminp forminp-select  " width="8%">
                                          <label>
                                              <?php echo (in_array($all[$value]['id'], $ccf_enable)) ? '<a  href="' . esc_url(menu_page_url("ccf_settings_menu", false) . '&ds=' . $all[$value]['id']."&_wpnonce=".$ccf_ends_action_nonce) . '" title="Disable it"><span class="dashicons dashicons-yes"></span></a>' : '<a  href="' . menu_page_url("ccf_settings_menu", false) . '&en=' . $all[$value]['id']."&_wpnonce=".$ccf_ends_action_nonce . '"  title="Enable it"><span class="dashicons dashicons-no"></span></a>'; ?></a></label>
                                      </td>
                                      <td class="forminp forminp-select action">
                                          <label><a href="<?php echo esc_url(current_page_url() . "&edit=" . $all[$value]['id']); ?>"><span class="dashicons dashicons-edit"></span></a><a href="<?php echo esc_url(current_page_url() . "&del=" . $all[$value]['id']."&_wpnonce=".$ccf_ends_action_nonce); ?>"><span class="dashicons dashicons-trash"></span></a></label>
                                      </td>
                                  </tr>
                                  <?php
                                  $i++;
                           }
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <div class="action">
                <input type="submit" name="save_fields" class="button-primary" value="<?php _e('Save changes', 'menu-test'); ?>" style="float:right" />
<!--                <button type="button" class="button" onclick="removeSelectedFields()"><?php _e('Remove', 'menu-test'); ?></button>
                <button type="button" class="button" onclick="enableSelectedFields()"><?php _e('Enable', 'menu-test'); ?></button>
                <button type="button" class="button" onclick="disableSelectedFields()"><?php _e('Disable', 'menu-test'); ?></button>-->
            </div>
        </form>
    </div>
</div>
