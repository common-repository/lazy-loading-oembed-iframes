<?php
/**
 * Lazy Loading oEmbed Iframes plugin bootstrap.
 *
 * @package   Google\Lazy_Loading_oEmbed_Iframes
 * @author    Weston Ruter, Google
 * @license   GPL-2.0-or-later
 * @copyright 2020 Google Inc.
 *
 * @wordpress-plugin
 * Plugin Name: Lazy Loading oEmbed Iframes
 * Plugin URI: https://gist.github.com/westonruter/9a8cda7307d08140f9edc29d246e46e8
 * Description: Add <code>loading=lazy</code> to <code>iframe</code> elements coming from oEmbeds. This plugin will be obsolete as of WordPress 5.7 per <a href="https://core.trac.wordpress.org/ticket/50756">#50756</a>.
 * Version: 1.0.1
 * Author: Weston Ruter
 * Author URI: https://weston.ruter.net/
 * License: GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Gist Plugin URI: https://gist.github.com/westonruter/9a8cda7307d08140f9edc29d246e46e8
 */

namespace Google\Lazy_Loading_oEmbed_Iframes;

/**
 * Filter oEmbed HTML to inject loading=lazy attribute into iframes.
 *
 * @param string $html HTML for oEmbed.
 * @return string Filtered oEmbed HTML.
 */
function filter_oembed_html( $html ) {
	return preg_replace_callback(
		'/(?<=<iframe\s)[^>]+(?=>)/',
		function ( $matches ) {
			$attributes = $matches[0];
			if ( false === strpos( $attributes, 'loading=' ) ) {
				$attributes .= ' loading="lazy"';
			}
			return $attributes;
		},
		$html
	);
}

if ( version_compare( strtok( get_bloginfo( 'version' ), '-' ), '5.7', '<' ) ) {
	add_filter( 'embed_oembed_html', __NAMESPACE__ . '\filter_oembed_html' );
} else {
	add_filter(
		'plugin_row_meta',
		function ( $plugin_meta, $plugin_file ) {
			if ( plugin_basename( __FILE__ ) === $plugin_file ) {
				$plugin_meta[] = '👉 <strong>This plugin is now obsolete as of WordPress 5.7! Please deactivate and uninstall.</strong> 🚮';
			}
			return $plugin_meta;
		},
		10,
		2
	);
}
