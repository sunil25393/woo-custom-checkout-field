<?php
require_once CCF_PLUGIN_DIR . '/include/ccf.php';

$all = ccf_getall();
?>
<div class="div50">
    <div class="ccf">          
        <table class="wp-list-table widefat plugins ">
            <thead>
                <tr>
                    <th scope="row" class="titledesc">
                        <label><?php _e("name:", 'menu-test'); ?> </label>
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
                        <label><?php _e("Action", 'menu-test'); ?> </label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($all)) {
                       foreach ($all as $value) {
                              ?>
                              <tr valign="top">
                                  <td scope="row" class="titledesc">
                                      <label><?php echo $value['field_name']; ?> </label>
                                  </td>
                                  <td class="forminp forminp-select">
                                      <label><?php echo $value['field_class']; ?> </label>
                                  </td>
                                  <td class="forminp forminp-select">
                                      <label><?php echo $value['field_type']; ?> </label>
                                  </td>
                                  <td class="forminp forminp-select">
                                      <label><?php echo ($value['field_required'] == '1') ? 'true' : 'false'; ?> </label>
                                  </td>
                                  <td class="forminp forminp-select">
                                      <label><a href="<?php echo current_page_url() . "&del=" . $value['id']; ?>"><span class="delete_icon"></span></a> </label>
                                  </td>
                              </tr>
                              <?php
                       }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
