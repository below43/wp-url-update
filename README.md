# WP URL Update

A WordPress plugin that updates image URLs from one domain to another in your WordPress site's post content. Perfect for site migrations, moving to a new CDN, or changing domains.

## Features

- **Focused on Images**: Specifically targets image URLs in `wp-content/uploads/` directory
- **Dry Run Mode**: Preview changes before making them permanent (enabled by default)
- **Safe & Simple**: Only updates post content, avoiding complex meta fields and serialized data
- **Real-time Feedback**: Shows how many posts will be affected before and after updates
- **Multisite Protection**: Prevents accidental use on multisite installations
- **Easy Interface**: Simple form in WordPress admin (Tools > Image URL Update)

## Installation

1. Clone or download this repository to your WordPress plugins directory:
   ```bash
   cd wp-content/plugins
   git clone https://github.com/below43/wp-url-update.git
   ```

2. Activate the plugin through the WordPress admin panel (Plugins > Installed Plugins)

3. Navigate to Tools > Image URL Update

## Usage

1. **Backup your database** - This is critical! URL updates cannot be undone.

2. Navigate to **Tools > Image URL Update** in your WordPress admin panel

3. Enter your **old URL** (e.g., `https://old-domain.com`)

4. Enter your **new URL** (e.g., `https://new-domain.com`)

5. **Run in Dry Run mode first** (checkbox is checked by default):
   - Click "Update URLs" to see what will be changed
   - Review the results to confirm the correct posts will be updated
   - No changes are made to your database in dry run mode

6. **Make actual changes**:
   - Uncheck the "Dry run" checkbox
   - Click "Update URLs" again
   - Confirm the action
   - Review the results

## What Gets Updated

The plugin specifically targets image URLs in the `wp-content/uploads/` directory. When you enter:
- Old URL: `https://old-site.com`
- New URL: `https://new-site.com`

The plugin will replace:
- `https://old-site.com/wp-content/uploads/` → `https://new-site.com/wp-content/uploads/`

This updates all image references in your post content that use the old domain.

## Use Cases

- Migrating from one domain to another
- Changing from HTTP to HTTPS
- Moving images to a CDN
- Updating staging site URLs to production
- Fixing broken image links after server migration

## Important Notes

⚠️ **Always backup your database before using this plugin!**

- **Dry run first**: Always run in dry run mode to preview changes
- **Focused scope**: Only updates image URLs in post content, not GUIDs, meta fields, or options
- **Multisite**: Not supported - the plugin will refuse to run on multisite installations
- **Path-specific**: Only updates URLs containing `wp-content/uploads/`

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- MySQL 5.6 or higher
- Not compatible with multisite installations

## Development

The plugin consists of:

- `wp-url-update.php` - Main plugin file with core functionality
- `assets/js/admin.js` - JavaScript for the admin interface
- `assets/css/admin.css` - Styles for the admin interface
- `readme.txt` - WordPress.org plugin repository readme
- `README.md` - This file

## Security

- Nonce verification for all AJAX requests
- Capability checks (requires `manage_options`)
- Proper input sanitization and output escaping
- Prepared SQL statements to prevent SQL injection
- Multisite safety check

## License

This plugin is licensed under the MIT License.

## Support

For issues, questions, or contributions, please visit:
https://github.com/below43/wp-url-update

## Author

Andrew Drake
- GitHub: [@below43](https://github.com/below43)

## Changelog

### 1.0.0
- Initial release
- Image URL update functionality for wp-content/uploads
- Dry run mode for safe testing
- Admin interface with safety warnings
- AJAX-powered updates with detailed results
- Multisite protection
