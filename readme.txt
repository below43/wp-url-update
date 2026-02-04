=== WP URL Update ===
Contributors: andrewdrake
Tags: url, migration, domain, images, cdn
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.2
Stable tag: 1.0.0
License: MIT
License URI: https://opensource.org/licenses/MIT

Update image URLs from one domain to another in your WordPress site's post content.

== Description ==

WP URL Update is a focused, safe plugin that helps you update image URLs in your WordPress database when migrating your site to a new domain, moving to a CDN, or changing from HTTP to HTTPS.

= Features =

* **Dry Run Mode**: Preview changes before making them (enabled by default)
* **Image-Focused**: Only updates image URLs in wp-content/uploads directory
* **Safe Approach**: Updates only post content, avoiding complex meta fields
* **Real-time Feedback**: See how many posts will be affected before and after
* **Multisite Protection**: Prevents accidental use on multisite installations
* **Simple Interface**: Easy-to-use form in Tools menu

= How It Works =

The plugin takes your old and new domain URLs and specifically updates image paths in the wp-content/uploads directory. This is the safest and most common use case for image URL updates.

For example, if you enter:
* Old URL: https://old-site.com
* New URL: https://new-site.com

The plugin will replace all instances of:
https://old-site.com/wp-content/uploads/ with https://new-site.com/wp-content/uploads/

= Use Cases =

* Migrating from one domain to another
* Changing from HTTP to HTTPS
* Moving images to a CDN
* Updating staging site image URLs to production
* Fixing broken image links after server migration

= Important Notes =

**Always backup your database before using this plugin!**

* Run in dry run mode first to preview changes
* Only updates image URLs in post content
* Does not update GUIDs, site options, or serialized data
* Not compatible with multisite installations

== Installation ==

1. Upload the `wp-url-update` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Tools > Image URL Update
4. Enter your old and new URLs
5. Run in dry run mode to preview changes
6. Uncheck dry run and run again to make actual changes

== Frequently Asked Questions ==

= Should I run in dry run mode first? =

Yes! Dry run mode is enabled by default and highly recommended. It shows you exactly what will be changed without making any modifications to your database. Review the results, and if everything looks correct, uncheck the dry run box and run again to make the actual changes.

= What gets updated? =

The plugin specifically updates image URLs in post content that reference the wp-content/uploads directory. It does not modify GUIDs, post meta, site options, or other database fields.

= Will this plugin update URLs in my theme files? =

No, this plugin only updates URLs stored in the WordPress database. Theme files and other server-side files are not modified.

= Can I undo the changes? =

No, once the actual update is run (with dry run disabled), changes are permanent. This is why backing up your database and running in dry run mode first is critical.

= Does this work with multisite? =

No, the plugin includes a safety check and will refuse to run on multisite installations.

= What if I need to update other types of URLs? =

This plugin is specifically designed for image URLs in the uploads directory. For more comprehensive URL updates (site options, meta fields, etc.), you may need a different tool or direct database access.

== Screenshots ==

1. The main Image URL Update interface with dry run mode
2. Dry run results showing preview of changes
3. Actual update results after unchecking dry run

== Changelog ==

= 1.0.0 =
* Initial release
* Image URL update functionality for wp-content/uploads
* Dry run mode for safe testing
* Admin interface with safety warnings
* AJAX-powered updates with detailed results
* Multisite protection

== Upgrade Notice ==

= 1.0.0 =
Initial release of WP URL Update with dry run mode and image-focused updates.

== Support ==

For support, please visit: https://github.com/below43/wp-url-update

== Credits ==

Developed by Andrew Drake
