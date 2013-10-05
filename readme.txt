=== Plugin Name ===
Contributors: labs64
Tags: plugin, shortcode, credit, credits, legal, copyright, owner, author, media library, media, image, images, photo, photos, royalty-free, RF, stock, attachment, custom fields, fotolia, bildnachweis, impressum, imprint, microdata
Requires at least: 3.5.1
Tested up to: 3.6.1
Stable tag: 0.9.3
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple way to show credits for the images used on your website.

== Description ==

The effective use of stock images and photography on your website and blog can have a massive impact on the legal requirements regarding proper providing attribution credits to photographers and image creators.

Credit Tracker plugin allows to create media credits tables and adds a few additional fields not provided by Media Library. Media credits data can be fetched from different image agencies and shown in required format.

The plugin adds the following fields to all images in WordPress Media Library:

* Ident-Nr.
* Source
* Owner/Author
* Publisher
* License

= Features =

* Append credits to the media library elements
* Configurable copyright format string
* "credit_tracker_table" shortcode to construct a nicely-styled 'Image Credits' table
* Fully customisable to look just like your own website's style: customise the colours, styles and fonts
* Ability to override standard WordPress shortcode [caption]
* Add microdata to the images

= Related Links =
* <a href="https://github.com/Labs64/credit-tracker" title="Plugin Homepage">Plugin Homepage</a>
* <a href="https://github.com/Labs64/credit-tracker/issues" title="Plugin Issues Tracking">Report a bug, or suggest an improvement</a>
* <a href="http://www.labs64.com/blog" title="Read Labs64 Blog">Labs64 Blog</a>

== Installation ==

*Note:* If you’d like to try this plugin for yourself, please make sure that you use a staging site first. This will allow you to properly test plugin to make sure that it can “get along” well with other plugins that you already have installed. Install plugin on a live site only when you have properly tested and analyzed this plugin.

1. Upload the 'credit-tracker' folder to the '/wp-content/plugins/' directory or install via WordPress Plugin Manager
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Settings / Credit Tracker" to add custom options (optional)

== Frequently Asked Questions ==

= How do I include a Credit Tracker on my page? =

`[credit_tracker_table id="11,22,33" size="thumbnail|medium|large|full" style="mercury"]`

* id (optional) - specify the attachment ID (one or more). The default behavior, if no ID is specified, is to display all images containing author info.
* size (optional) - specify the image size to use for the thumbnail display. Valid values include "thumbnail", "medium", "large", "full". The default value is "thumbnail".
* style (optional) - specify the table style. Valid values include "default", "mercury", "mars". The default value is "default".

== Screenshots ==

1. Credit Tracker settings
2. Edit Media Library entry
3. Media Library view
4. 'credit_tracker_table' shortcode
5. Overriden caption shortcode

== Changelog ==

= 0.9.3 =
* TODO...

= 0.9.2 =
* Override WordPress [caption] shortcode
* Use dropdown for source field
* Add new table CSS style (mercury) for 'credit_tracker_table'
* Add new table CSS style (mars) for 'credit_tracker_table'

= 0.9.1 =
* Add reference section

= 0.9.0 =
* Initial version

== Translations ==

* English - default, always included
* German

*Note:* Please contribute your language to the plugin to make it even more useful.
