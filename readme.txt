=== Plugin Name ===
Contributors: labs64
Tags: credit, attribution, legal, copyright, owner, author, media library, media, image, photo, license, royalty-free, RF, Creative Commons, stock, attachment, flickr, fotolia, bildnachweis, impressum, imprint, microdata, NetLicensing
Requires at least: 3.5.1
Tested up to: 4.3
Stable tag: 1.1.6
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple way to show credits for the images used on your website.

== Description ==

The effective use of stock images and photography on your website and blog can have a massive impact on the number of visits which your site receives, and the positive response which visitors have to your sites content. Whilst a great deal of high quality images are available for free in the public domain, to really make your site stand out you’ll want to use images which are supplied by a top quality photographer or company.

However, the inclusion of images which are not in the public domain means that you must provide proper attribution credits to the photographers and image creators.

The Credit Tracker plugin allows you to create Media Credits Tables, meaning you can easily track which images on your site require accreditation. It also adds a few additional fields to the Media Library which are not included in the original installation. The function of these fields is to help you fetch the credit information from different image agencies and then show it in the required format.

The Plugin is designed to be easy-to-use and to fit easily and neatly within the current design of your website with fully customisable colours, fonts and styles. For the more confident designer you can also override the standard Wordpress Shortcode.

This plugin adds the following fields to all images in WordPress Media Library:

* Ident-Nr.
* Source
* Owner/Author
* Publisher
* License
* Link

= Features =

* Retrieve Media information from the different stock agencies (Flickr, Fotolia, iStockphoto etc.)
* Append credits to the Media Library elements
* Configurable copyright format string
* "credit_tracker_table" shortcode to construct a nicely-styled 'Image Credits' table
* Fully customisable to look just like your own website's style: customise the colours, styles and fonts
* Ability to override standard WordPress shortcode [caption]
* Add microdata to the images

= Showcase =

* [Media Credits page at labs64.com](http://www.labs64.com/legal/media-credits/ "Media Credits")

= Get Involved =

Developers can checkout and contribute to the source code on the Credit Tracker's [GitHub Repository](https://github.com/Labs64/credit-tracker).

= Related Links =

* [Plugin Homepage](https://github.com/Labs64/credit-tracker "Plugin Homepage")
* [Report a bug, or suggest an improvement](https://github.com/Labs64/credit-tracker/issues "Plugin Issue Tracking")
* [Labs64 Blog](http://www.labs64.com/blog "Read Labs64 Blog")

== Installation ==

*NOTE:* We really hope that you find our plugin useful and that it helps you to manage the use of images and relevant accreditation on your website, however, as this plugin makes a number of changes to the structure of the Media Library and other aspects of the base Wordpress code we would recommend that you install in on a test site before going live.

This is only so that you can make sure that the plugin does not have an adverse effect on the custom code which you may have added to the site or any other plugins which you currently use, and vice versa.

We also recommend that you do not test this plugin at the same time as making any other changes to your site. This is because otherwise it is easy to become confused about where a problem is emanating from. Although with smaller plugins and changes you can normally batch test, when a plugin makes such big changes to certain aspects of your sites functionality, solo testing is highly recommended.

= Minimum Requirements =

* WordPress 3.5 or greater
* PHP version 5.2.4 or greater

= via Upload (FTP, SCP etc.) =

1. Upload the extracted archive folder 'credit-tracker' to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. *(optional)* Go to "Settings > Credit Tracker" to adjust settings

= via WordPress Admin =

1. Go to "Plugins > Add New"
2. Search for `Credit Tracker`
3. Install the plugin called `Credit Tracker` and activate it
4. Go to "Settings > Credit Tracker" to adjust settings (optional)

== Frequently Asked Questions ==

= How do I adjust the Plugin Settings? =

Once you have activated the plugin you can tinker with the settings to your heart’s content. If you head to "Settings > Credit Tracker" and you can adjust all settings from there.

= How do I include a Credit Tracker on my page? =

If you want to show the Credit Tracker on a specific page or set of pages on your website then you can use the code below (provided that the plugin has already been installed and activated). The appearance of the Credit Tracker table can be fully customized by making a few simple changes to the embed code, the details of which are listed below. Inclusion of the table is of course entirely optional, have a play with the different sizes and styles to see what works for your site.

`[credit_tracker_table id="11,22,33" size="thumbnail|medium|large|full" style="mercury"]`

* **id** *(optional)* - specify which images you want to list in the table (one or more). If no ID is specified, the default display is to show all images containing author info.
* **size** *(optional)* - specify the image size to use for the thumbnail display within the credit tracker table. The choice of sizes includes "thumbnail", "medium", "large", "full" or numeric values (e.g. "50" or "100x50"). The default value is "thumbnail".
* **style** *(optional)* - Specify the table style to suite your website. Valid values include "default", "mercury", "mars". If no value is set, the "default" style will be used.

This is a brief introductory video for Credit Tracker:

[youtube http://www.youtube.com/watch?v=w13fYXWK-lo]

= How do I use both standard caption and media credit? =

By using an overridden version of the caption shortcode, you have also access to the standard media attributes 'title' and 'caption'. So you can simply add these to the 'Copyright format' definition, e.g.:

`&copy; <a href="%link%" target="__blank">%author%</a> - %caption%`

= Do I have to manually update the Credit Tracker Table if I add/modify a media library entry? =

Nope! If you add/edit/delete a media library entry, your 'credit_tracker_table' is automatically updated to reflect any changes you have made. Obviously, if the new entry is not already included in the images you want to list, it will not appear until you set the code to apply this change.

= Will Credit Tracker work with my theme? =

Yes. Credit Tracker is designed to work with any theme, but it may require some styling to make it match the rest of the theme and achieve that seamless integration.

= Does this plugin work with BuddyPress (bbPress)? =

Yep, it does.

= Which WordPress versions are supported? =

In order to get the most out of the plugin and use all of its features, you will need to be using WordPress 3.5 with PHP 5.2.4 as a minimum. Updates will continue to be released for the plugin as the main WordPress CMS is updated.

= Can I request new features and extensions to be included in future releases of the plugin? =

We always welcome your feedback and would love to know what you would like to see done next with the plugin and what features you would like integrated. You can vote on and request new features and extensions in our Credit Tracker's [Issue Tracker](https://github.com/Labs64/credit-tracker/issues)

= Where can I report bugs? =

If you have discovered a bug, we want to know so that we can get it fixed as soon as possible! We always work to make sure that the plugin is working fully prior to releasing an update but sometimes problems do arise. All bugs and issues can be reported on the Credit Tracker's [Issue Tracker](https://github.com/Labs64/credit-tracker/issues).

= I love Credit Tracker, it’s awesome! Can I contribute? =

Yes you can! Join in on our [GitHub repository](https://github.com/Labs64/credit-tracker) :) You can also leave us a nice review on the WordPress site to let others know what you think of the plugin!

== Screenshots ==

1. Adjust Credit Tracker Settings from within the WordPress CMS
2. Credit Tracker Integration within the WordPress Media Library
3. Media Library Overview with Credit Tracker Details
4. Example layouts and styles for the Credit Tracker Table when integrated within the main site via ShortCode
5. Custom Image Caption Shortcode to reflect Credit Information

== Changelog ==

= 1.1.6 =
* TODO

= 1.1.5 =
* Fix CSS

= 1.1.4 =
* Adapt, test and approve plugin for WordPress 4.3
* Update Labs64 NetLicensing URL - [netlicensing.io](http://netlicensing.io)

= 1.1.3 =
* Restore old plugin options
* Fix table columns names and translations

= 1.1.2 =
* Implement 'Option to enable/disable credit tracker table columns' [#12](https://github.com/Labs64/credit-tracker/pull/12)
* Fix methods namespace conflict with "Anti-spam by CleanTalk" [#13](https://github.com/Labs64/credit-tracker/issues/13)

= 1.1.1 =
* Fix 'caption' shortcode rendering issue [#11](https://github.com/Labs64/credit-tracker/issues/10)

= 1.1.0 =
* Enable Shutterstock parser [#11](https://github.com/Labs64/credit-tracker/pull/11)

= 1.0.3 =
* Fixed [Wordpress 'caption' shortcode rendering problem #10](https://github.com/Labs64/credit-tracker/issues/10) issue
* Test and approve plugin for WordPress 4.2

= 1.0.2 =
* Update credit parsers to use Wordpress HTTP API [#6](https://github.com/Labs64/credit-tracker/pull/6) by [Carl](https://github.com/thybag)

= 1.0.1 =
* Fixed [Syntax error in latest version #5](https://github.com/Labs64/credit-tracker/issues/5) issue (PHP 5.3.10)
* Prevent showing 'Get Media Data' button on unsupported pages/screens

= 1.0.0 =
* Change major version number
* Adapt, test and approve plugin for WordPress 4.0

= 0.9.17 =
* Test and approve plugin for WordPress 3.9.2
* Stability: use CT scope
* Fix Flickr *Link* attribute capture

= 0.9.16 =
* Enable Freeimages parser
* Use original photo URIs for the *Link* attribute
* Add WordPress [caption] shortcode to the 'Shortcodes Reference' section
* Introduce 'text' attribute to the [caption] shortcode; usage - [caption text="image caption"]...[/caption]

= 0.9.15 =
* Introduce additional *Link* attribute
* Allow HTML usage at 'Copyright format' (e.g. &copy; <a href="%link%" target="__blank">%author%</a> - %caption%)
* Use standard attributes 'title', 'caption' at 'Copyright format'

= 0.9.14 =
* Fixed [bug](http://wordpress.org/support/topic/error-after-updating-to-0913) introduced with v0.9.13

= 0.9.13 (not-stable) =
* Test and approve plugin for WordPress 3.9
* Enable Flickr parser

= 0.9.12 =
* Add custom sizes at the credit table
* Adapt NetLicensing authentication using apiKey

= 0.9.11 =
* German translation by Michail Preissnerov

= 0.9.10 =
* French translation by Lilian Ricaud

= 0.9.9 =
* Enable Pixelio parser

= 0.9.8 =
* Change default fields set at Media Library to Ident-Nr., Source, Author
* Enable Fotolia parser

= 0.9.7 =
* Test and approve plugin for WordPress 3.8

= 0.9.6 =
* Integrate NetLicensing to validate activated plugin features

= 0.9.5 =
* Fix reset validation after plugin option save

= 0.9.4 =
* Activated plugin features box
* Add 'Get Media Data' button at media library
* Use stock agency credit format for shortcodes
* Added credit formats for: Fotolia, iStockphoto, Shutterstock, Corbis_Images, Getty_Images

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
* French - by [Lilian Ricaud](http://www.lilianricaud.com "Lilian Ricaud")
* German - by Michail Preissnerov

*NOTE:* Please [contribute](https://github.com/Labs64/credit-tracker/tree/master/languages "contribute your language") your language to the plugin to make it even more useful.

= Legal & Law-Related Decisions =

This is a collection of legal & law regulations and judicial decisions where Credit Tracker may be very useful in order to prevent financial damages for the publishers.

* [Germany] Judicial Decision: Urteil des Amtsgerichts München vom 24.06.15, Aktenzeichen [142 C 11428/15](https://www.justiz.bayern.de/gericht/ag/m/presse/archiv/2015/04984/ "142 C 11428/15")
