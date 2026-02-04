=== WP URL Update ===
Contributors: andrewdrake
Tags: url, migration, domain, permalink, database
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Update permalinks and URLs from one domain to another across your WordPress site with ease.

== Description ==

WP URL Update is a powerful yet simple plugin that helps you update all URLs in your WordPress database when migrating your site to a new domain or changing from HTTP to HTTPS.

= Features =

* **Easy to Use**: Simple interface to specify old and new URLs
* **Comprehensive Updates**: Updates URLs in multiple locations:
  * Post content
  * Post excerpts
  * Post meta data
  * Site options (siteurl, home)
  * GUIDs (optional)
* **Safe**: Built-in confirmations and warnings before making changes
* **Detailed Reports**: See exactly what was updated after the process completes
* **Flexible**: Choose which elements to update with checkboxes

= Use Cases =

* Migrating from one domain to another
* Changing from HTTP to HTTPS
* Moving from a subdomain to main domain
* Updating staging site URLs to production

= Important Notes =

**Always backup your database before using this plugin!** URL updates are permanent and cannot be undone automatically.

It's recommended NOT to update GUIDs unless absolutely necessary, as this can cause issues with RSS feeds and other services that rely on GUIDs.

== Installation ==

1. Upload the `wp-url-update` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Tools > URL Update
4. Enter your old and new URLs
5. Select which elements you want to update
6. Click 'Update URLs'

== Frequently Asked Questions ==

= Will this plugin update URLs in my theme files? =

No, this plugin only updates URLs stored in the WordPress database. Theme files and other server-side files are not modified.

= Should I update GUIDs? =

Generally, no. GUIDs should remain constant as they're used by RSS readers and other services to identify posts. Only update GUIDs if you're certain it won't cause issues.

= What happens after I update URLs? =

The plugin will update all matching URLs in the selected database tables and clear the WordPress cache. You may need to:
* Regenerate thumbnails if you updated image URLs
* Clear any caching plugins
* Update any hardcoded URLs in theme/plugin files manually

= Can I undo the changes? =

No, URL updates are permanent. This is why it's critical to backup your database before running the update.

= Does this work with multisite? =

The plugin is designed for single-site installations. Use with caution on multisite networks.

== Screenshots ==

1. The main URL Update interface showing the form fields
2. Update results showing the number of rows affected

== Changelog ==

= 1.0.0 =
* Initial release
* URL update functionality for posts, excerpts, meta, options, and GUIDs
* Admin interface with safety warnings
* AJAX-powered updates with detailed results

== Upgrade Notice ==

= 1.0.0 =
Initial release of WP URL Update.

== Support ==

For support, please visit: https://github.com/below43/wp-url-update

== Credits ==

Developed by Andrew Drake
