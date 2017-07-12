<div class="wrap"><h2>GD Plugin Core: Wizard</h2>

<?php 

function render_user_level_combo() {
?>
<option value="10">10: <?php _e("Admin Only", "gd-plugin-core"); ?></option>
<option value="09">09: <?php _e("Administrator", "gd-plugin-core"); ?></option>
<option value="08">08: <?php _e("Editor", "gd-plugin-core"); ?></option>
<option value="07">07: <?php _e("Editor", "gd-plugin-core"); ?></option>
<option value="06">06: <?php _e("Editor", "gd-plugin-core"); ?></option>
<option value="05">05: <?php _e("Editor", "gd-plugin-core"); ?></option>
<option value="04">04: <?php _e("Editor", "gd-plugin-core"); ?></option>
<option value="03">03: <?php _e("Author", "gd-plugin-core"); ?></option>
<option value="02">02: <?php _e("Contributor", "gd-plugin-core"); ?></option>
<option value="01">01: <?php _e("Contributor", "gd-plugin-core"); ?></option>
<?php
}

if (!$writeable) {
    echo '<div class="updated fade" id="message" style="background-color: rgb(255, 251, 204); width: 980px; margin-top: 10px"><p>';
    _e("Folder <strong>plugins-wizard</strong> needs to be writeable for wizard to automaticlly create all plugin folders and files.", "gd-plugin-core");
    echo '<br />';
    _e("You must fix this before you can use this plugin. Only then <strong>Create Plugin</strong> button will be visible.", "gd-plugin-core");
    echo '</p></div>'; 
}

if ($status != '') {
    echo '<div class="updated fade" id="message" style="background-color: rgb(255, 251, 204); width: 980px; margin-top: 10px"><p>'.$status.'</p></div>';
}

?>

<form method="post">
<input type="hidden" id="gdcc-action" name="gdcc-action" value="save">
<table class="form-table">
<tbody>
<tr valign="top">
<th scope="row"><?php _e("Plugin Details", "gd-plugin-core"); ?></th>
<td>
<table>
    <tr>
        <td width="100" style="padding: 0; border: 0;"><?php _e("Name", "gd-plugin-core"); ?>:</td>
        <td style="padding: 0; border: 0;"><input name="gdcc[name]" type="text" id="gdcc-name" value="GD Example Plugin" size="32" /></td>
    </tr>
    <tr>
        <td width="100" style="padding: 0; border: 0;"><?php _e("Description", "gd-plugin-core"); ?>:</td>
        <td style="padding: 0; border: 0;"><input name="gdcc[description]" type="text" id="gdcc-description" value="This is an plugin example." size="32" /></td>
    </tr>
    <tr>
        <td width="100" style="padding: 0; border: 0;"><?php _e("Plugin URL", "gd-plugin-core"); ?>:</td>
        <td style="padding: 0; border: 0;"><input name="gdcc[url]" type="text" id="gdcc-url" value="<?php bloginfo('home'); ?>/" size="32" /></td>
    </tr>
    <tr>
        <td width="100" style="padding: 0; border: 0;"><?php _e("Author", "gd-plugin-core"); ?>:</td>
        <td style="padding: 0; border: 0;"><input name="gdcc[author]" type="text" id="gdcc-author" value="Your Name" size="32" /></td>
    </tr>
    <tr>
        <td width="100" style="padding: 0; border: 0;"><?php _e("Author URL", "gd-plugin-core"); ?>:</td>
        <td style="padding: 0; border: 0;"><input name="gdcc[author_url]" type="text" id="gdcc-author_url" value="<?php bloginfo('home'); ?>/" size="32" /></td>
    </tr>
</table>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e("Features", "gd-plugin-core"); ?></th>
<td>
<label for="gdcc[widget]">
    <input name="gdcc[widget]" type="checkbox" id="gdcc-widget" value="1"  checked="checked" /> <?php _e("Multi instance Widget", "gd-plugin-core"); ?>
</label><br />
<label for="gdcc[shortcode]">
    <input name="gdcc[shortcode]" type="checkbox" id="gdcc-shortcode" value="1"  checked="checked" /> <?php _e("Shortcode support", "gd-plugin-core"); ?>
</label><br />
<label for="gdcc[i18n]">
    <input name="gdcc[i18n]" type="checkbox" id="gdcc-i18n" value="1"  checked="checked" /> <?php _e("Multi language support", "gd-plugin-core"); ?>
</label>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e("Administration Panels", "gd-plugin-core"); ?></th>
<td>
<table>
    <tr>
        <td width="110" style="padding: 0; border: 0;">
        <label for="gdcc[admin_options]">
            <input name="gdcc[admin_options]" type="checkbox" id="gdcc-admin_options" value="1"  checked="checked" /> <?php _e("Settings Panel", "gd-plugin-core"); ?>
        </label>
        <td width="70" style="padding: 0; border: 0;"><?php _e("User Level", "gd-plugin-core"); ?>:</td>
        </td>
        <td style="padding: 0; border: 0;"><select name="gdcc[admin_options_level]" id="gdcc-admin_options_level"><?php render_user_level_combo(); ?></select></td>
    </tr>
    <tr>
        <td width="110" style="padding: 0; border: 0;">
        <label for="gdcc[admin_plugins]">
            <input name="gdcc[admin_plugins]" type="checkbox" id="gdcc-admin_plugins" value="1" /> <?php _e("Plugins Panel", "gd-plugin-core"); ?>
        </label>
        <td width="70" style="padding: 0; border: 0;"><?php _e("User Level", "gd-plugin-core"); ?>:</td>
        </td>
        <td style="padding: 0; border: 0;"><select name="gdcc[admin_plugins_level]" id="gdcc-admin_plugins_level"><?php render_user_level_combo(); ?></select></td>
    </tr>
    <tr>
        <td width="110" style="padding: 0; border: 0;">
        <label for="gdcc[admin_options]">
            <input name="gdcc[admin_manage]" type="checkbox" id="gdcc-admin_manage" value="1" /> <?php _e("Manage Panel", "gd-plugin-core"); ?>
        </label>
        <td width="70" style="padding: 0; border: 0;"><?php _e("User Level", "gd-plugin-core"); ?>:</td>
        </td>
        <td style="padding: 0; border: 0;"><select name="gdcc[admin_manage_level]" id="gdcc-admin_manage_level"><?php render_user_level_combo(); ?></select></td>
    </tr>
</table>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e("Administration Pages", "gd-plugin-core"); ?></th>
<td>
<label for="gdcc[admin_header]">
    <input name="gdcc[admin_header]" type="checkbox" id="gdcc-admin_header" value="1"  checked="checked" /> <?php _e("Header", "gd-plugin-core"); ?>
</label><br />
<label for="gdcc[footer]">
    <input name="gdcc[admin_footer]" type="checkbox" id="gdcc-admin_footer" value="1"  checked="checked" /> <?php _e("Footer", "gd-plugin-core"); ?>
</label>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e("Blog", "gd-plugin-core"); ?></th>
<td>
<label for="gdcc[wp_content]">
    <input name="gdcc[wp_content]" type="checkbox" id="gdcc-wp_content" value="1"  checked="checked" /> <?php _e("Wordpress Loop content manipulation", "gd-plugin-core"); ?>
</label><br />
<label for="gdcc[wp_header]">
    <input name="gdcc[wp_header]" type="checkbox" id="gdcc-wp_header" value="1"  checked="checked" /> <?php _e("Header", "gd-plugin-core"); ?>
</label><br />
<label for="gdcc[wp_footer]">
    <input name="gdcc[wp_footer]" type="checkbox" id="gdcc-wp_footer" value="1"  checked="checked" /> <?php _e("Footer", "gd-plugin-core"); ?>
</label>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e("Plugin", "gd-plugin-core"); ?></th>
<td>
<table>
    <tr>
        <td width="100" style="padding: 0; border: 0;">Options name:</td>
        <td style="padding: 0; border: 0;"><input name="gdcc[plugin_options]" type="text" id="gdcc-plugin_options" value="" size="32" /></td>
    </tr>
</table>
<?php _e("Leave blank if you want base class to generate this name based on your plugin name. Do not use blank spaces.", "gd-plugin-core"); ?>
</td>
</tr>
<tr valign="top">
<th scope="row">Widget</th>
<td>
<table>
    <tr>
        <td width="100" style="padding: 0; border: 0;"><?php _e("Options name", "gd-plugin-core"); ?>:</td>
        <td style="padding: 0; border: 0;"><input name="gdcc[widget_options]" type="text" id="gdcc-widget_options" value="" size="32" /></td>
    </tr>
    <tr>
        <td width="100" style="padding: 0; border: 0;"><?php _e("Widget Base ID", "gd-plugin-core"); ?>:</td>
        <td style="padding: 0; border: 0;"><input name="gdcc[widget_baseid]" type="text" id="gdcc-widget_baseid" value="" size="32" /></td>
    </tr>
    <tr>
        <td width="100" style="padding: 0; border: 0;"><?php _e("Base Form Name", "gd-plugin-core"); ?>:</td>
        <td style="padding: 0; border: 0;"><input name="gdcc[widget_form]" type="text" id="gdcc-widget_form" value="" size="32" /></td>
    </tr>
</table>
<table>
<tr>
    <td width="100" style="padding: 0; border: 0;"><?php _e("Width", "gd-plugin-core"); ?>:</td>
    <td style="padding: 0; border: 0;"><input name="gdcc[widget_width]" type="text" id="gdcc-widget_width" value="400" size="5" /></td>
    <td width="18" style="padding: 0; border: 0;"></td>
    <td width="100" style="padding: 0; border: 0;"><?php _e("Height", "gd-plugin-core"); ?>:</td>
    <td style="padding: 0; border: 0;"><input name="gdcc[widget_height]" type="text" id="gdcc-widget_height" value="300" size="5" /></td>
</tr>
</table>
<?php _e("Leave blank if you want base class to generate name and id based on your plugin name. Do not use blank spaces.", "gd-plugin-core"); ?>
</td>
</tr>
</tbody>
</table>
<?php if ($writeable) : ?>
<p class="submit">
    <input type="submit" value="<?php _e("Create Plugin", "gd-plugin-core"); ?>" name="Submit"/>
</p>
<?Php endif; ?>
</form>
</div>