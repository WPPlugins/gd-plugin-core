<?php
/*
Name: GD Plugin Core: Base
Description: Base class
*/

class GDPluginCore
{
    var $version = "1.1.1";
    
    var $log_file = "c:/log.txt";
    var $events;

    var $wp_version;
    var $plugin_arguments;
    var $plugin_name;
    var $plugin_id;
    var $plugin_url;
    var $plugin_path;
    var $file_path;
	
    var $extra_files_admin = array();
    var $extra_files_wp = array();
    var $shortcodes = array();
    
    var $global_options_widget;
    
    var $options_plugin;
    var $options_widget;
    var $options_widget_number;
    
    var $default_options_plugin;
    var $default_options_widget;
    
    function GDPluginCore($pl_name, $file, $arguments = "") {
        global $wp_version;
        $this->events = Array();

        // contruction //
        $this->plugin_name = $pl_name;
        $this->file_path = $file;
        $this->plugin_path = dirname($this->file_path);
        $this->plugin_id = basename(dirname($this->file_path));
        $this->set_params($arguments);
        
        $this->wp_version = substr(str_replace('.', '', $wp_version), 0, 2);
        if ($this->wp_version < 26) $this->plugin_url = get_option('home').'/'.PLUGINDIR.'/'.$this->plugin_id.'/';
        else $this->plugin_url = WP_PLUGIN_URL.'/'.$this->plugin_id.'/';

        // others //
        $this->global_options_widget = get_option($this->plugin_arguments["widgetoptions"]);

        // add actions & filters //
        if ($this->plugin_arguments["i18n"] == 1) add_action('init', array(&$this, 'lang_init'));
        if ($this->plugin_arguments["widget"] == 1) add_action('widgets_init', array(&$this, 'widget_init'));

        if ($this->plugin_arguments["admin_page_settings"] > 0) add_action('admin_menu', array(&$this, 'settings_menu')); 
        if ($this->plugin_arguments["admin_page_plugin"] > 0) add_action('admin_menu', array(&$this, 'plugin_menu')); 
        if ($this->plugin_arguments["admin_page_manage"] > 0) add_action('admin_menu', array(&$this, 'manage_menu')); 
        if ($this->plugin_arguments["admin_head"] == 1) add_action('admin_head', array(&$this, 'admin_head')); 
        if ($this->plugin_arguments["admin_footer"] == 1) add_action('admin_footer', array(&$this, 'admin_footer')); 
        if ($this->plugin_arguments["wp_head"] == 1) add_action('wp_head', array(&$this, 'wp_head'));
        if ($this->plugin_arguments["wp_head"] == 1) add_action('wp_footer', array(&$this, 'wp_footer'));
        if ($this->plugin_arguments["wp_content"] == 1) add_filter('the_content', array(&$this, 'wp_content'));
        
        if ($this->plugin_arguments["shortcodes"] == 1) {
            foreach ($this->shortcodes as $code) {
                $this->shortcode_action($code);
            }
        }
    }

    // helpers
    function set_params($arguments) {
        $p_name_1 = str_replace(" ", "-", strtolower($this->plugin_name));
        $p_name_2 = str_replace(" ", "", strtolower($this->plugin_name));
        
        $defaults = array(
            "widget" => 1,
            "i18n" => 1,
            "shortcodes" => 1,
            "admin_page_settings" => 10,
            "admin_page_plugin" => 0,
            "admin_page_manage" => 0,
            "admin_head" => 1,
            "admin_footer" => 1,
            "wp_head" => 1,
            "wp_footer" => 1,
            "wp_content" => 1,
            "plugin_options" => $p_name_1."_plugin",
            "widget_options" => $p_name_1."_widget",
            "widget_idbase" => $p_name_2."wimu",
            "widget_form" => $p_name_2."_wifo",
            "widget_width" => 400,
            "widget_height" => 300
        );

        $this->plugin_arguments = wp_parse_args($arguments, $defaults);
        extract($this->plugin_arguments, EXTR_SKIP);
    }
    
    function shortcode_action($scode) {
        if (is_array($scode)) {
            $sc_name = $scode["name"];
            $sc_method = $scode["function"];
        }
        else {
            $sc_name = $scode;
            $sc_method = "shortcode_".$scode;
        }
        add_shortcode($sc_name, array(&$this, $sc_method));
    }
    
    function create_file_tag($file) {
        if (is_array($file)) {
            $ffile = $file["file"];
            $fext = $file["ext"];
            if (isset($file["media"])) $fmedia = $file["media"];
            if (isset($file["type"])) $ftype = $file["type"];
            if (isset($frel["rel"])) $frel = $file["rel"];
        }
        else {
            $f = pathinfo($file);
            $ffile = $f["basename"];
            $fext = $f["extension"];
        }
        if (substr($ffile, 0, 1) == "/") $ffile = substr($ffile, 1);
        $ffile = $this->plugin_url.$ffile;
        $output = "";
        switch($fext) {
            case "js":
                $output = sprintf('<script type="%s" src="%s"></script>', 
                    isset($ftype) ? $ftype : 'text/javascript',
                    $ffile
                );
                break;
            default:
                $output = sprintf('<link rel="%s" href="%s" type="%s" media="%s" />', 
                    isset($frel) ? $frel : 'stylesheet',
                    $ffile,
                    isset($ftype) ? $ftype : 'text/css',
                    isset($fmedia) ? $fmedia : 'screen'
                );
                break;
        }
        return $output;
    }
    
    function upgrade_settings($old, $new) {
        foreach ($new as $key => $value) {
            if (!isset($old[$key])) $old[$key] = $value;
        }
        
        $unset = Array();
        foreach ($old as $key => $value) {
            if (!isset($new[$key])) $unset[] = $key;
        }
        
        foreach ($unset as $key) {
            unset($old[$key]);
        }
        
        return $old;
    }
    // helpers
    
	// initialization
    function lang_init() {
        $currentLocale = get_locale();
        if(!empty($currentLocale)) {
            $moFile = $this->plugin_path."/languages/".$this->plugin_name."-" . $currentLocale . ".mo";
            if (@file_exists($moFile) && is_readable($moFile)) load_textdomain($this->plugin_name, $moFile);
        }
    }
    
    function settings_menu() {
        add_submenu_page('options-general.php', $this->plugin_name, $this->plugin_name, $this->plugin_arguments["admin_page_settings"], $this->file_path, array(&$this, 'page_settings_panel'));
    }
    
    function plugin_menu() {
        add_submenu_page('plugins.php', $this->plugin_name, $this->plugin_name, $this->plugin_arguments["admin_page_plugin"], $this->file_path, array(&$this, 'page_plugins_panel'));
    }

    function manage_menu() {
        add_submenu_page('edit.php', $this->plugin_name, $this->plugin_name, $this->plugin_arguments["admin_page_manage"], $this->file_path, array(&$this, 'page_plugins_manage'));
    }
    
    function admin_head() {
        foreach ($this->extra_files_admin as $file) {
            echo $this->create_file_tag($file);
        }
    }
    
    function admin_footer() {
    }

    function wp_content($content) {
        return $content;
    }

    function wp_head() {
        foreach ($this->extra_files_wp as $file) {
            echo $this->create_file_tag($file);
        }
    }

    function wp_footer() {
    }
    // initialization
    
    // events & log
    function add_event($msg, $event = "init") {
        $this->events[] = Array(
            "time" => current_time('mysql'),
            "event" => $event,
            "plugin" => $this->plugin_name,
            "message" => $msg
        );
    }
    
    function dump_object($msg, $object) {
        $obj = print_r($object, true);
        $f = fopen($this->log_file, 'a+');
        fwrite ($f, sprintf("[%s] : %s\r\n", current_time('mysql'), $msg));
        fwrite ($f, "$obj");
        fwrite ($f, "\r\n");
        fclose($f);
    }
    
    function dump_string($msg, $event = "init") {
        $f = fopen($this->log_file, 'a+');
        fwrite ($f, sprintf("[%s] : %s : %s\r\n", current_time('mysql'), $event, $msg));
        fclose($f);
    }

    function dump_events() {
        $f = fopen($this->log_file, 'a+');
        foreach ($events as $e)
            fwrite ($f, sprintf("[%s] : %s : [%s] : %s<br />", $e["time"], $e["event"], $e["plugin"], $e["message"]));
        fclose($f);
    }
    
    function print_events($events) {
        foreach ($events as $e)
            echo sprintf("[%s] : %s : [%s] : %s<br />", $e["time"], $e["event"], $e["plugin"], $e["message"]);
    }
    // events & log
    
    // panels
    function page_settings_panel() {
    }

    function page_plugins_panel() {
    }

    function page_manage_panel() {
    }
    // panels
    
    // widget
    function widget_init() {
        $this->default_options_widget['wcore'] = 'wcore';

        if (!$options = get_option($this->plugin_arguments["widget_options"]))
            $options = array();
            
        $name = $this->plugin_name.' Widget';
        $widget_ops = array('classname' => $this->plugin_arguments["widget_options"], 'description' => $name);
        $control_ops = array('width' => $this->plugin_arguments["widget_width"], 'height' => $this->plugin_arguments["widget_height"], 'id_base' => $this->plugin_arguments["widget_idbase"]);
        
        $registered = false;
        foreach (array_keys($options) as $o) {
            if (!isset($options[$o]['wcore'])) continue;
                
            $id = $this->plugin_arguments["widget_idbase"].'-'.$o;
            $registered = true;
            wp_register_sidebar_widget($id, $name, array(&$this, 'widget_display'), $widget_ops, array( 'number' => $o ) );
            wp_register_widget_control($id, $name, array(&$this, 'widget_control'), $control_ops, array( 'number' => $o ) );
        }
        if (!$registered) {
            wp_register_sidebar_widget($this->plugin_arguments["widget_idbase"].'-1', $name, array(&$this, 'widget_display'), $widget_ops, array( 'number' => -1 ) );
            wp_register_widget_control($this->plugin_arguments["widget_idbase"].'-1', $name, array(&$this, 'widget_control'), $control_ops, array( 'number' => -1 ) );
        }
    }
    
    function widget_control($widget_args = 1){
        global $wp_registered_widgets;
        static $updated = false;

        if ( is_numeric($widget_args) )
            $widget_args = array('number' => $widget_args);
        $widget_args = wp_parse_args($widget_args, array('number' => -1));
        extract($widget_args, EXTR_SKIP);
        $options_all = get_option($this->plugin_arguments["widget_options"]);
        if (!is_array($options_all))
            $options_all = array();

        if (!$updated && !empty($_POST['sidebar'])) {
            $sidebar = (string)$_POST['sidebar'];
            $this->dump_object("POST", $_POST);
            
            $sidebars_widgets = wp_get_sidebars_widgets();
            if (isset($sidebars_widgets[$sidebar]))
                $this_sidebar =& $sidebars_widgets[$sidebar];
            else
                $this_sidebar = array();
            
            foreach ($this_sidebar as $_widget_id) {
                if ($this->plugin_arguments["widget_options"] == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) {
                    $widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
                    if (!in_array($this->plugin_arguments["widget_idbase"].'-'.$widget_number, $_POST['widget-id']))
                        unset($options_all[$widget_number]);
                }
            }
            foreach ((array)$_POST[$this->plugin_arguments["widget_form"]] as $widget_number => $posted) {
                if (!isset($posted['wcore']) && isset($options_all[$widget_number])) continue;
                $options = $this->widget_parse_options($posted);
                $options_all[$widget_number] = $options;
            }
            update_option($this->plugin_arguments["widget_options"], $options_all);
            $updated = true;
        }

        if (-1 == $number) {
            $this->options_widget_number = '%i%';
            $this->options_widget = $this->default_options_widget;
        }
        else {
            $o = $this->upgrade_settings($options_all[$number], $this->default_options_widget);
            $options_all[$number] = $o;
            update_option($this->plugin_arguments["widgetoptions"], $options_all);

            $this->options_widget_number = $number;
            $this->options_widget = $o;
        }
    }

    function widget_parse_options($post_params) {
        $options = array();
        $options['wcore'] = $post_params['wcore'];
        return $options;
    }

    function widget_display($args, $widget_args = 1) {
        extract($args);
        if (is_numeric($widget_args)) $widget_args = array('number' => $widget_args);
        $widget_args = wp_parse_args($widget_args, array( 'number' => -1 ));
        extract($widget_args, EXTR_SKIP);
        $options_all = get_option($this->plugin_arguments["widget_options"]);
        if (!isset($options_all[$number])) return;
        $this->options_widget = $options_all[$number];
    }
    // widget

    // widget form elements
    function get_widget_element_name($name) {
    	$el = sprintf("%s[%s][%s]", $this->plugin_arguments["widget_form"], $this->options_widget_number, $name);
    	return $el;
    }

    function get_widget_element_id($name) {
    	$el = sprintf("%s-%s", $this->plugin_arguments["widget_form"], $name);
    	return $el;
    }

    function get_widget_render_hidden($name, $value) {
    	$el = sprintf('<input type="hidden" id="%s" name="%s" value="%s" />',
    			$this->get_widget_element_id($name),
    			$this->get_widget_element_name($name),
    			$value
    		);
    	return $el;
    }

    function widget_element_name($name) {
    	echo $this->get_widget_element_name($name);
    }

    function widget_element_id($name) {
    	echo $this->get_widget_element_id($name);
    }

    function widget_render_hidden($name, $value) {
    	echo $this->get_widget_render_hidden($name, $value);
    }
    // widget form elements
}