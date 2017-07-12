=== GD Plugin Core ===
Contributors: gdragon
Donate link: http://www.dev4press.com/donate/
Version: 1.1.1
Tags: widget, plugin, multi, options, base, class, core, extend
Requires at least: 2.5
Tested up to: 2.7.1
Stable tag: trunk

This is not really a plugin. This is a base class implementation that can be used to create plugins.

== Description ==

Basic idea was to shorten the plugin development time. When you start working on a new plugin, you need to create a lot of basic code for initialization of options page, widget  initialization and this is even more complicated if you want a plugin to support multi instance widgets. And this you need to repeat for every plugin.

And, repeating this every time gets very tiresome and mistakes are easily made. So, you might want to try using this base class, extending it and setting only the things you need.

= Class Features =

The GD Plugin Core class implements following features:

* Options subpage on a Settings page
* Options subpage on a Plugins page
* Options subpage on a Manage page
* Setting user level for added subpages
* Multi instance widget
* Loading of translations if available
* Adding files (css, js...) into header
* Event log with log file support
* WordPress filters for header and footer
* Unlimited number of Shortcodes
* Additional Helper class with useful functions

== Installation ==

* Upload `gd-plugin-core` folder to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How to use it?
There is an user guide included.

== Website ==

* Homepage: http://www.dev4press.com/
* Plugin: http://www.dev4press.com/plugins/gd-plugin-core/
* Old Blog: http://wp.gdragon.info/

== Support ==

* Ohloh: https://www.ohloh.net/p/gd-plugin-core
* Forum: http://forum.gdragon.info/viewforum.php?f=17
* User Guide: http://wp.gdragon.info/plugins/gd-plugin-core/userguide/
* Email: wordpress@gdragon.info

== WordPress Extend ==

* Plugin: http://wordpress.org/extend/plugins/gd-plugin-core/

== Screenshots ==

1. Plugin Creation Wizard
