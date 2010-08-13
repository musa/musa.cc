=== SimplePie Core ===
Contributors: skyzyx, gsnedders
Donate link: http://simplepie.org/wiki/plugins/wordpress/simplepie_core
Tags: rss, atom, feed, feeds, syndication, simplepie, magpie, sidebar, sideblog
Requires at least: 2.0
Tested up to: 2.3.3
Stable tag: 1.1.1

Does little else but load the core SimplePie API library for any extension that wants to utilize it.

== Description ==

= About this Plugin =
This plugin does absolutely nothing except load the core SimplePie library so that all other plugins that utilize SimplePie can all share the same up-to-date version. It also helps minimize potential conflicts between SimplePie-powered plugins. 

Read the <a href="http://simplepie.org/wiki/misc/release_notes/simplepie_1.1">Release Notes</a> for details on what's new.

== Installation ==

= Fresh Install =
Upload the simplepie-core plugin folder to the WordPress plugins directory (wp-content/plugins).

= Upgrading? =
Disable the old version in the WordPress control panel, delete the entire folder for the old version (wp-content/plugins/simplepie-core), then upload the simplepie-core plugin folder to the WordPress plugins directory (wp-content/plugins).

== Frequently Asked Questions ==

To learn more about the core SimplePie library, check out the <a href="http://simplepie.org/wiki/faq/start">SimplePie FAQ</a>.

== Usage ==

This extension is intended to do little more than make the core SimplePie API library available to other extensions that want to use it.

However, if you also install an extension like ExecPHP or PHP-Exec (clever names, I know), you can write pure PHP code in your posts. If you are comfortable writing PHP code, and you want to take a look at the <a href="http://simplepie.org/wiki/reference/start">SimplePie API Reference</a>, you can use native SimplePie code within WordPress.

If you want to add SimplePie to your templates (such as a sidebar), you an do that without the need for the "PHP execution" extensions that were previously noted.

== About SimplePie ==

The core SimplePie library has support for the following basic features:

* RSS 0.9x, 1.0, and 2.0
* Atom 0.3 and 1.0
* A handful of namespaces including Dublin Core, Media RSS, and iTunes RSS.
* Function names that focus on the data you want to grab, regardless of the feed type.
* Integrated support for mashing multiple feeds together and sorting them by date.
* Easy access to ALL tags/attributes in a feed.
* Extensibility allows for the addition of extensions/add-ons.
* Serialized caching system
* One-click subscribing to several online aggregators
* One-click bookmarking to several social bookmarking sites
* Embedded (or non-embedded -- your choice) feed enclosures (Podcasting, Videocasting)
* Ultra-liberal Feed Locator (satisfies points 1-6 from <a href="http://diveintomark.org/archives/2002/08/15/ultraliberal_rss_locator">Mark Pilgrim's feed usability rant</a>)
* Image and favicon detection and caching
* Support for over 800 of character sets
* Support for PHP 4.3.2+, 5.0.0+
* Reasonable support for non-well-formed feeds
* Automatically strips potentially dangerous tags and attributes
* Supports parsing from a string
* Swap-in custom classes to replace built-in classes
* Supports gzip-compressed feeds
* Supports HTTP Conditional Get for more efficient caching
* Support for RFC 822, RFC 2822, RFC 3339, and ISO 8601 datestamps
* Plugins for a variety of blogging systems, wikis, forums, and frameworks.
* Comprehensive test suite (http://php5.simplepie.org/trunk/test/test.php)
* Well documented!

== Related ==

Besides this plugin for WordPress, the SimplePie team also develops the following feed-related stuff:

* **<a href="http://wordpress.org/extend/plugins/simplepie-plugin-for-wordpress/">SimplePie Plugin for WordPress</a>** -- The SimplePie plugin that makes it easy to integrate feeds into your WordPress blog!
* **<a href="http://simplepie.org">SimplePie</a>** -- This is the core PHP library that powers everything we do. Super-fast, easy-to-use RSS and Atom parsing in PHP.
* **<a href="http://live.simplepie.org">SimplePie Live! (Beta)</a>** -- For the AJAX/JavaScript developers out there, this is a service that provides an AJAX-friendly API for feeds. A must-have for AJAX developers wishing to implement feeds.
* **<a href="http://mobile.simplereader.com">SimpleReader Mobile</a>** -- An online news feed reader designed for mobile devices (iPhone, iPod touch, Blackberry, Palm, PSP, Windows Mobile, Opera Mini, etc.). Perfect for keeping up with feeds on the go.

