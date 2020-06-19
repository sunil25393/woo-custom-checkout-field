<div class="div40"><?php
    if (isset($_REQUEST['edit']) && $_REQUEST['edit'] != '') {
           $edit_field_data = ccf_field_by_id($_REQUEST['edit']);
           ?>
           <div class="col-sm-offset-4 col-sm-10">
               <br>
               <a  class="btn button-primary" href="<?php menu_page_url('ccf_settings_menu'); ?>"><?php esc_attr_e('Add new field') ?></a>
           </div>
           <form  method="post" class="ccf" role="form">
               <input type="hidden" name="field_id" value="<?php echo $_REQUEST['edit']; ?>">
               <div class="form-group">
                   <label class="control-label col-sm-4" for="email"><?php _e("Custom Field Name:", 'menu-test'); ?></label>
                   <div class="col-sm-8">
                       <input type="text" value="<?php echo $edit_field_data['field_name']; ?>" required="true" name="txt_field_name" class="form-control" id="txt_field_name" placeholder="Enter name of field" size="20">
                   </div>
                   <small id="ccf_name_error"></small>
               </div>
               <div class="form-group">
                   <label class="control-label col-sm-4" for="pwd"><?php _e("Custom Field Class:", 'menu-test'); ?></label>
                   <div class="col-sm-8">          
                       <input type="text" name="txt_field_class"  value="<?php echo $edit_field_data['field_class']; ?>" class="form-control" id="txt_field_class" placeholder="Enter field class">
                   </div>
       <!--            <small><b>Note: </b>&nbsp;Id should one unique word. It does not contain any soacial chare except "_". <br>Fox Example: my_custom_field_id</small>-->
               </div>
               <div class="form-group">
                   <label class="control-label col-sm-4" for="pwd"><?php _e("Custom Field Type:", 'menu-test'); ?> </label>
                   <div class="col-sm-8">          
                       <select class="form-control" name="txt_field_type" id="txt_field_type" > 
                           <option value="text">Select field Type</option>
                           <option value="text" <?php
                           if ($edit_field_data['field_type'] == 'text') {
                                  echo 'selected';
                           }
                           ?>>Textbox</option>
                           <option value="checkbox" <?php
                           if ($edit_field_data['field_type'] == 'checkbox') {
                                  echo 'selected';
                           }
                           ?>>Checkbox</option>
                           <option value="select" <?php
                           if ($edit_field_data['field_type'] == 'select') {
                                  echo 'selected';
                           }
                           ?>>Dropdown</option>
                           <option value="radio" <?php
                           if ($edit_field_data['field_type'] == 'radio') {
                                  echo 'selected';
                           }
                           ?>>Radio</option>
                           <option value="textarea" <?php
                           if ($edit_field_data['field_type'] == 'textarea') {
                                  echo 'selected';
                           }
                           ?>>Textarea</option>
                       </select>
                   </div>
               </div>
               <div class="form-group <?php
               if ($edit_field_data['field_type'] != 'select' && $edit_field_data['field_type'] != 'radio') {
                      echo 'option_field_hidden';
               }
               ?> ">
                   <label class="control-label col-sm-4" for="pwd"><?php _e("Custom Field Options:", 'menu-test'); ?></label>
                   <div class="col-sm-8">          
                       <textarea type="text" name="txt_field_options" class="form-control" id="txt_field_options" rows="5" placeholder="Enter Dropdown/radio option as CSV" ><?php
                           if ($edit_field_data['field_type'] == 'select' || $edit_field_data['field_type'] == 'radio') {
                                  echo $edit_field_data['field_options'];
                           }
                           ?></textarea>
                   </div>
                   <small><b>For Example: </b>&nbsp; my_option_01,my_option_02 </small>
               </div>
               <div class="form-group">        
                   <div class="col-sm-offset-4 col-sm-10">
                       <div class="checkbox">
                           <label><input type="checkbox" name="txt_field_required" value="yes"  <?php
                               if ($edit_field_data['field_required'] == '1') {
                                      echo 'checked';
                               }
                               ?>> Required field</label>
                       </div>
                   </div>
               </div>
               <div class="form-group">        
                   <div class="col-sm-offset-4 col-sm-10">
                       <div class="checkbox">
                           <label><input type="checkbox" name="txt_field_add_email" value="yes" <?php
                               if ($edit_field_data['field_includeemail'] == '1') {
                                      echo 'checked';
                               }
                               ?>> <?php _e("Include in order invoice", 'menu-test'); ?> </label>
                       </div>
                   </div>
               </div>
               <div class="form-group">        
                   <div class="col-sm-offset-4 col-sm-10">
                       <button type="submit" class="btn button-primary"><?php esc_attr_e('Save Changes') ?></button>
                   </div>
               </div>
           </form>
           <?php
    } else {
           ?>
           <form  method="post" class="ccf" role="form">
               <div class="form-group">
                   <label class="control-label col-sm-4" for="email"><?php _e("Custom Field Name:", 'menu-test'); ?></label>
                   <div class="col-sm-8">
                       <input type="text" required="true" name="txt_field_name" class="form-control" id="txt_field_name" placeholder="Enter name of field" size="20">
                   </div>
                   <small id="ccf_name_error"></small>
               </div>
               <div class="form-group">
                   <label class="control-label col-sm-4" for="pwd"><?php _e("Custom Field Class:", 'menu-test'); ?></label>
                   <div class="col-sm-8">          
                       <input type="text" name="txt_field_class" class="form-control" id="txt_field_class" placeholder="Enter field class">
                   </div>
       <!--            <small><b>Note: </b>&nbsp;Id should one unique word. It does not contain any soacial chare except "_". <br>Fox Example: my_custom_field_id</small>-->
               </div>
               <div class="form-group">
                   <label class="control-label col-sm-4" for="pwd"><?php _e("Custom Field Type:", 'menu-test'); ?> </label>
                   <div class="col-sm-8">          
                       <select class="form-control" name="txt_field_type" id="txt_field_type" > 
                           <option value="text">Select field Type</option>
                           <option value="text">Textbox</option>
                           <option value="checkbox">Checkbox</option>
                           <option value="select">Dropdown</option>
                           <option value="radio">Radio</option>
                           <option value="textarea">Textarea</option>
                       </select>
                   </div>
               </div>
               <div class="form-group option_field_hidden">
                   <label class="control-label col-sm-4" for="pwd"><?php _e("Custom Field Options:", 'menu-test'); ?></label>
                   <div class="col-sm-8">          
                       <textarea type="text" name="txt_field_options" class="form-control" id="txt_field_options" rows="5" placeholder="Enter Dropdown/radio option as CSV" ></textarea>
                   </div>
                   <small><b>For Example: </b>&nbsp; my_option_01,my_option_02 </small>
               </div>
               <div class="form-group">        
                   <div class="col-sm-offset-4 col-sm-10">
                       <div class="checkbox">
                           <label><input type="checkbox" name="txt_field_required" value="yes"> Required field</label>
                       </div>
                   </div>
               </div>
               <div class="form-group">        
                   <div class="col-sm-offset-4 col-sm-10">
                       <div class="checkbox">
                           <label><input type="checkbox" name="txt_field_add_email" value="yes"> Include in order invoice </label>
                       </div>
                   </div>
               </div>
               <div class="form-group">        
                   <div class="col-sm-offset-4 col-sm-10">
                       <button type="submit" class="btn button-primary"><?php esc_attr_e('Add New Field') ?></button>
                   </div>
               </div>
           </form>
    <?php }
    ?>
</div>