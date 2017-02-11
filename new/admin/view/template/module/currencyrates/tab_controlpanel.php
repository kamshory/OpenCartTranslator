<table class="table">
 <tr>
    <td class="col-xs-2"><h5><span class="required">* </span><?php echo $entry_code; ?></h5><span class="help"><?php echo $entry_code_help; ?></span></td>
    <td class="col-xs-10">
        <div class="col-xs-4">
            <select id="Checker" name="<?php echo $moduleName; ?>[Enabled]" class="form-control">
                  <option value="yes" <?php echo (!empty($moduleData['Enabled']) && $moduleData['Enabled'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                  <option value="no"  <?php echo (empty($moduleData['Enabled']) || $moduleData['Enabled']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
            </select>
        </div>
    </td>
    </tr>
</table>
