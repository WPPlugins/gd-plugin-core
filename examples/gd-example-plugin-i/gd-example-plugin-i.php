<?php
/*
Plugin Name: GD Example Plugin I
Plugin URI: http://localhost:81/wpt/
Description: This is an plugin example.
Version: 1.0.0
Author: Your Name
Author URI: http://localhost:81/wpt/
*/

require_once (dirname(dirname(__FILE__)).'/gd-plugin-core/gd-plugin-core.php');
require_once (dirname(dirname(__FILE__)).'/gd-plugin-core/gd-plugin-xtra.php');

class GDExamplePluginI extends GDPluginCore
{
    var $shortcodes = array();
    var $default_options_widget = array(
        'title' => 'Example Title',
        'message' => 'This is an example plugin message.'
    );

    function GDExamplePluginI() {
        parent::GDPluginCore('GD Example Plugin I', __FILE__, 'widget_width=250&i18n=0&wp_head=0&wp_footer=0&wp_content=0');
    }

    function widget_control($widget_args = 1) {
        parent::widget_control($widget_args);
        include ("gd-example-plugin-i_widget.php");
    }

    function widget_parse_options($post_params) {
        $options = parent::widget_parse_options($post_params);
        
        $options["title"] = $post_params["title"];
        $options["message"] = $post_params["message"];

        return $options;
    }

    function widget_display($args, $widget_args = 1) {
        parent::widget_display($args, $widget_args);
        extract($args);
        $options = $this->options_widget;
        
        echo $before_widget.$before_title.$options['title'].$after_title;
        echo $options['message'].$after_widget;
    }
}

$pc_GDE = new GDExamplePluginI();

?>
