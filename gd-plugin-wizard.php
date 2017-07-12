<?php
/*
Plugin Name: GD Plugin Core
Plugin URI: http://www.dev4press.com/plugins/gd-plugin-core/
Description: This is not really a plugin. This is a base class implementation that can be used to create plugins.
Version: 1.1.1
Author: Milan Petrovic
Author URI: http://www.dev4press.com/
 
== Copyright ==

Copyright 2008 Milan Petrovic (email : milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

require_once ('gd-plugin-core.php');

class GDPluginCoreWizard extends GDPluginCore
{
    var $status;
    
    function GDPluginCoreWizard() {
        parent::GDPluginCore("GD Plugin Core Wizard", __FILE__, "admin_page_settings=0&admin_page_plugin=5&widget=0");
        
        if ($_POST["gdcc-action"] == "save") 
            $this->post();
        else
            $this->status = '';
    }
    
    function page_plugins_panel() {
        clearstatcache();
        $writeable = is_writable($this->plugin_path."/plugins-wizard/");
        $status = $this->status;
        
        include ('gd-plugin-form.php');
    }
    
    function post() {
        $this->error = '';
        $posted = $_POST['gdcc'];
        
        if ($posted['name'] == '')
            $this->status = "Plugin must have a name.";
        else {
            $arg_string = '';
            $file_plugin = '';
            $file_form = '';
            $file_options = '<form method="post">';
            $file_options.= "\r\n\r\nHere goes your options form...\r\n\r\n";
            $file_options.= '</form>';
            
            if (!isset($posted['i18n'])) $arg_string.= "i18n=0&";
            if (!isset($posted['shortcode'])) $arg_string.= "shortcode=0&";
            if (!isset($posted['widget'])) $arg_string.= "widget=0&";

            if (isset($posted['admin_options'])) {
                $arg_string.= "admin_page_settings=";
                $arg_string.= $posted['admin_options_level']."&";
            }
            if (isset($posted['admin_plugins'])) {
                $arg_string.= "admin_page_plugin=";
                $arg_string.= $posted['admin_plugins_level']."&";
            }
            if (isset($posted['admin_manage'])) {
                $arg_string.= "admin_page_manage=";
                $arg_string.= $posted['admin_manage_level']."&";
            }
            
            if (!isset($posted['admin_header'])) $arg_string.= "admin_head=0&";
            if (!isset($posted['admin_footer'])) $arg_string.= "admin_footer=0&";

            if (!isset($posted['wp_content'])) $arg_string.= "wp_content=0&";
            if (!isset($posted['wp_header'])) $arg_string.= "wp_head=0&";
            if (!isset($posted['wp_footer'])) $arg_string.= "wp_footer=0&";
            
            if ($arg_string != "") $arg_string = substr($arg_string, 0, strlen($arg_string) - 1);
            
            $fldr = str_replace(" ", "-", strtolower($posted['name']));
            $clss = str_replace(" ", "", $posted['name']);
            $inst = "pc_".substr($clss, 0, 3);
            
            $pl_folder = $this->plugin_path."/plugins-wizard/".$fldr;

            $file_plugin.= "<?php\r\n/*\r\n";
            $file_plugin.= "Plugin Name: ".$posted['name']."\r\n";
            $file_plugin.= "Plugin URI: ".$posted['url']."\r\n";
            $file_plugin.= "Description: ".$posted['description']."\r\n";
            $file_plugin.= "Version: 1.0.0\r\n";
            $file_plugin.= "Author: ".$posted['author']."\r\n";
            $file_plugin.= "Author URI: ".$posted['author_url']."\r\n*/\r\n\r\n";

            $file_plugin.= "require_once (dirname(dirname(__FILE__)).'/gd-plugin-core/gd-plugin-core.php');\r\n";
            $file_plugin.= "require_once (dirname(dirname(__FILE__)).'/gd-plugin-core/gd-plugin-xtra.php');\r\n\r\n";
            $file_plugin.= "class ".$clss." extends GDPluginCore\r\n{\r\n";
            if (isset($posted['shortcode'])) $file_plugin.= "    var $"."shortcodes = array();\r\n";
            if (isset($posted['widget'])) $file_plugin.= "    var $"."default_options_widget = array();\r\n";
            $file_plugin.= "    var $"."default_options_plugin = array();\r\n\r\n    function ".$clss."() {\r\n";
            $file_plugin.= "        parent::GDPluginCore('".$posted['name']."', __FILE__".($arg_string != "" ? ', "'.$arg_string.'"' : "").");\r\n\r\n        // your code here\r\n    }\r\n";
            if (isset($posted['admin_header'])) $file_plugin.= $this->create_override_string("admin_head", true);
            if (isset($posted['admin_footer'])) $file_plugin.= $this->create_override_string("admin_footer", true);
            if (isset($posted['wp_header'])) $file_plugin.= $this->create_override_string("wp_head", true);
            if (isset($posted['wp_footer'])) $file_plugin.= $this->create_override_string("wp_footer", true);
            if (isset($posted['admin_options'])) {
                $file_plugin.= "\r\n    function page_settings_panel() {\r\n";
                $file_plugin.= "        // your code here\r\n\r\n";
                $file_plugin.= "        include (\"".$fldr."_settings.php\");\r\n    }\r\n";
            }
            if (isset($posted['admin_plugins'])) {
                $file_plugin.= "\r\n    function page_plugins_panel() {\r\n";
                $file_plugin.= "        // your code here\r\n\r\n";
                $file_plugin.= "        include (\"".$fldr."_plugins.php\");\r\n    }\r\n";
            }
            if (isset($posted['admin_manage'])) {
                $file_plugin.= "\r\n    function page_manage_panel() {\r\n";
                $file_plugin.= "        // your code here\r\n\r\n";
                $file_plugin.= "        include (\"".$fldr."_manage.php\");\r\n    }\r\n";
            }
            if (isset($posted['widget'])) {
                $file_plugin.= "\r\n    function widget_control($"."widget_args = 1) {\r\n";
                $file_plugin.= "        parent::widget_control($"."widget_args);\r\n";
                $file_plugin.= "        include (\"".$fldr."_widget.php\");\r\n    }\r\n\r\n";
                $file_plugin.= "    function widget_parse_options($"."post_params) {\r\n";
                $file_plugin.= "        $"."options = parent::widget_parse_options($"."post_params);\r\n\r\n";
                $file_plugin.= "        // your code here\r\n\r\n";
                $file_plugin.= "        return $"."options;\r\n    }\r\n\r\n";
                $file_plugin.= "    function widget_display($"."args, $"."widget_args = 1) {\r\n";
                $file_plugin.= "        parent::widget_display($"."args, $"."widget_args);\r\n        extract($"."args);\r\n";
                $file_plugin.= "        $"."options = $"."this->options_widget;\r\n\r\n";
                $file_plugin.= "        // your code here\r\n    }\r\n";
                
                $file_form.= "<?php\r\n\r\nglobal $".$inst.";\r\n$"."options = $".$inst."->options_widget;\r\n\r\n?>\r\n\r\n";
                $file_form.= "<?php $".$inst."->widget_render_hidden('wcore', 'wcore'); ?>";
                $file_form.= "\r\n\r\nHere goes your widget form...\r\n\r\n";
                $file_form.= "<?php $".$inst."->widget_render_hidden('submit', 'submit'); ?>";
            }
            $file_plugin.= "}\r\n\r\n"."$".$inst." = new ".$clss."();\r\n\r\n?>\r\n";
            
            clearstatcache();
            if (!file_exists($pl_folder)) mkdir($pl_folder);
            if (!file_exists($pl_folder.'/languages/') && isset($posted['i18n'])) mkdir($pl_folder.'/languages/');

            $fp = fopen($pl_folder."/".$fldr.".php", 'w+');
            fwrite($fp, $file_plugin); 
            fclose($fp);
            
            if ($file_form != '') {
                $ff = fopen($pl_folder."/".$fldr."_widget.php", 'w+');
                fwrite($ff, $file_form); 
                fclose($ff);
            }

            if (isset($posted['admin_options'])) {
                $fo = fopen($pl_folder."/".$fldr."_settings.php", 'w+');
                fwrite($fo, $file_options); 
                fclose($fo);
            }
            
            if (isset($posted['admin_plugins'])) {
                $fo = fopen($pl_folder."/".$fldr."_plugins.php", 'w+');
                fwrite($fo, $file_options); 
                fclose($fo);
            }

            if (isset($posted['admin_manage'])) {
                $fo = fopen($pl_folder."/".$fldr."_manage.php", 'w+');
                fwrite($fo, $file_options); 
                fclose($fo);
            }
            $this->status = __("Files succesfully created.", "gd-plugin-core");
        }
    }
    
    function create_override_string($func_name, $parent) {
        if ($parent)
            return "\r\n    function ".$func_name."() {\r\n        parent::".$func_name."();\r\n\r\n        // your code here\r\n    }\r\n";
        else
            return "\r\n    function ".$func_name."() {\r\n        // your code here\r\n    }\r\n";
    }
}

$gdcc = new GDPluginCoreWizard();

?>