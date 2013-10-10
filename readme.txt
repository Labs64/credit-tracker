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

= Get Involved =

Developers can checkout and contribute to the source code on the [Credit Tracker GitHub Repository](https://github.com/Labs64/credit-tracker).

= Related Links =

* [Plugin Homepage](https://github.com/Labs64/credit-tracker "Plugin Homepage")
* [Report a bug, or suggest an improvement](https://github.com/Labs64/credit-tracker/issues "Plugin Issue Tracking")
* [Labs64 Blog](http://www.labs64.com/blog "Read Labs64 Blog")

== Installation ==

*NOTE:* If you’d like to try this plugin for yourself, please make sure that you use a staging site first. This will allow you to properly test plugin to make sure that it can “get along” well with other plugins that you already have installed. Install plugin on a live site only when you have properly tested and analyzed this plugin.

= Minimum Requirements =

* WordPress 3.5 or greater
* PHP version 5.2.4 or greater

= via Upload (FTP, SCP etc.) =

1. Upload the extracted archive folder 'credit-tracker' to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. *(optional)* Go to "Settings / Credit Tracker" to adjust settings

= via WordPress Admin =

1. Go to "Plugins / Add New"
2. Search for `Credit Tracker`
3. Install the plugin called `Credit Tracker` and activate it
4. Go to "Settings / Credit Tracker" to adjust settings (optional)

== Frequently Asked Questions ==

= Where can I find the settings to adjust the plugin work after activation? =

Go to "Settings / Credit Tracker" to adjust plugin settings.

= How do I include a Credit Tracker on my page? =

`[credit_tracker_table id="11,22,33" size="thumbnail|medium|large|full" style="mercury"]`

* **id** *(optional)* - specify the attachment ID (one or more). The default behavior, if no ID is specified, is to display all images containing author info.
* **size** *(optional)* - specify the image size to use for the thumbnail display. Valid values include "thumbnail", "medium", "large", "full". The default value is "thumbnail".
* **style** *(optional)* - specify the table style. Valid values include "default", "mercury", "mars". The default value is "default".

= Do I always have to update 'credit_tracker_table' if I added/modified a media library entry? =
No, if you add/edit/delete a media library entry, your 'credit_tracker_table' is automatically regenerated.

= Will Credit Tracker work with my theme? =

Yes. Credit Tracker will work with any theme, but may require some styling to make it match nicely.

= Does this plugin work with BuddyPress (bbPress)? =

Yes.

= Which WordPress versions are supported? =

To use all features in the plugin, a minimum of version WordPress 3.5 with PHP 5.2.4 is required.

= Where can I request new features and extensions? =

You can vote on and request new features and extensions in our [Credit Tracker Issue Tracker](https://github.com/Labs64/credit-tracker/issues)

= Where can I report bugs? =

Bugs can be reported on the [Credit Tracker Issue Tracker](https://github.com/Labs64/credit-tracker/issues).

= Credit Tracker is awesome! Can I contribute? =

Yes you can! Join in on our [GitHub repository](https://github.com/Labs64/credit-tracker) :)

== Screenshots ==

1. Credit Tracker settings
2. Edit Media Library entry
3. Media Library view
4. 'credit_tracker_table' shortcode
5. Overriden caption shortcode

== Changelog ==

= 0.9.4 =
* Add 'Get Media Data' button at media library
* Use stock agency credit format for shortcodes
* Added credit formats for: Fotolia, iStockphoto, Shutterstock, Corbis_Images, Getty_Images
* Parse Fotolia media data

= 0.9.3 =
* Update plugin information

= 0.9.2 =
* Override WordPress [caption] shortcode
* Use dropdown for source field
* Add new table CSS style (mercury) for 'credit_tracker_table'
* Add new table CSS style (mars) for 'credit_tracker_table'

= 0.9.1 =
* Add reference section

= 0.9.0 =
* Initial version

== Other Notes ==

= Special Thanks & Credits =

The plugin wouldn't be half of what it is today if it weren't for people like you who take the time to help it grow! Whether it be by submitting bug reports, translations, or maybe even a little development help.

Listed here are credits and special thanks to some of you who have helped us out a great deal:

= Translations =
* English - default, always included
* German - credit goes to [Alexey Averikhin](http://www.labs64.de)
* Russian - credit goes to [Konstantin Korotkov](http://netlicensing.labs64.com)

*NOTE:* Please [contribute](https://github.com/Labs64/credit-tracker/tree/master/languages "contribute your language") your language to the plugin to make it even more useful.
