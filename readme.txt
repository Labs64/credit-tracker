=== Plugin Name ===
Contributors: labs64
Tags: credit, credits, legal, copyright, owner, author, media library, media, image, royalty-free, RF, stock, attachment, custom fields, fotolia, bildnachweis, impressum, imprint
Requires at least: 3.5.1
Tested up to: 3.6.1
Stable tag: 0.9.1
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple way to show credits for the images used on your website.

== Description ==

Credit Tracker plugin allows to create media credits tables and adds a few additional fields not provided by Media Library. Media credits data can be fetched from different image agencies and shown in required format.

The plugin adds the following fields to all images in WordPress Media Library:

* Ident-Nr.
* Source
* Owner/Author
* Publisher
* License

= Features =
* Append credits to the media library elements
* "credit_tracker_table" shortcode to construct a nicely-styled 'Image Credits' table
* Fully customisable to look just like your own website's style: customise the colours, styles and fonts

== Installation ==

1. Upload the 'credit-tracker' folder to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to "Settings / Credit Tracker" to add custom options (optional)

== Frequently Asked Questions ==

= How do I include a Credit Tracker on my page? =

`[credit_tracker_table id="11,22,33" size="thumbnail|medium|large|full"]`

* id (optional) - specify the attachment ID (one or more). The default behavior, if no ID is specified, is to display all images containing author info.
* size (optional) - specify the image size to use for the thumbnail display. Valid values include "thumbnail", "medium", "large", "full". The default value is "thumbnail".

== Screenshots ==

1. Credit Tracker settings
2. Edit Media Library entry
2. Media Library view
3. Shortcode 'credit_tracker_table'

== Changelog ==

= 0.9.1 =
* German translation
*

= 0.9.0 =
* Initial version

== Translations ==

* English - default, always included
* German

*Note:* Please contribute your language to the plugin to make it even more useful.
