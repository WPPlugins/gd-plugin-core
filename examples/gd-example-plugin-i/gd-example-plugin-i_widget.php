<?php

global $pc_GDE;
$options = $pc_GDE->options_widget;

?>

<?php $pc_GDE->widget_render_hidden('wcore', 'wcore'); ?>

<div style="margin-bottom: 10px;">
<label for="gdpnav-title">Title: 
<br /><input id="<?php $pc_GDE->widget_element_id('title'); ?>" name="<?php $pc_GDE->widget_element_name('title'); ?>" type="text" value="<?php echo htmlspecialchars($options['title'], ENT_QUOTES); ?>" />
</label>
<br />
<label for="gdpnav-title">Message: 
<br /><input id="<?php $pc_GDE->widget_element_id('message'); ?>" name="<?php $pc_GDE->widget_element_name('message'); ?>" type="text" value="<?php echo htmlspecialchars($options['message'], ENT_QUOTES); ?>" />
</label>
</div>

<?php $pc_GDE->widget_render_hidden('submit', 'submit'); ?>