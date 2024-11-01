<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Plugin Name: Supafolio
 * Plugin URI: https://www.supadu.com
 * Description: Quickly and easily connect your book metadata (ONIX) to your WordPress site.
 * Version: 2.24.8
 * Author: Supadü
 * Author URI: https://www.supadu.com
 * Text Domain: supapress
 * Domain Path: /languages
 * License: GPL2
 */

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( defined('WP_DEBUG') && WP_DEBUG === true ) {
	error_reporting( E_ALL );
	ini_set( 'display_errors', '1' );
}

defined( 'ABSPATH' ) or die( 'Illegal Access!' );

define( 'SUPAPRESS_VERSION', '2.24.8' );

define( 'SUPAPRESS_SITE_URL', get_site_url() );

define( 'SUPAPRESS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( 'SUPAPRESS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

define( 'SUPAPRESS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

define( 'SUPAPRESS_DEFAULT_SERVICE_URL', 'https://api.supafolio.com/v2/' );

define( 'SUPAPRESS_DEFAULT_SERVICE_API', '9819864f6ff0e8a5a097c99224cfd18a' );

define( 'SUPAPRESS_DEFAULT_NO_BOOKS_MESSAGE', 'No books found...' );

define( 'SUPAPRESS_DEFAULT_BOOK_NOT_FOUND_MESSAGE', 'Book not found...' );

// Used for clearing transient data out of the wp_options table
// If this is changed, please make sure it is unique within the table as it is used to remove the cached elements
define( 'SUPAPRESS_CACHE_PREFIX', 'spcache-' );

// If object caching is turned on for the site, store a rotating prefix in this field
define( 'SUPAPRESS_CACHE_PREFIX_KEY', 'spcache-prefix' );

// Define capability level, edit_private_posts = Super Admin, Administrator and Editor roles
define( 'SUPAPRESS_CAPABILITIES', 'edit_private_posts' );

// Set up the the time (in seconds) to stored service calls
define( 'SUPAPRESS_CACHE_LIFETIME_DEFAULT', DAY_IN_SECONDS );

define( 'SUPAPRESS_CACHE_LIFETIME_PRODUCT_DETAILS', get_option( 'product_details_cache_lifetime' ) ? get_option( 'product_details_cache_lifetime' ) : SUPAPRESS_CACHE_LIFETIME_DEFAULT );

define( 'SUPAPRESS_CACHE_LIFETIME_SEARCH_RESULTS', get_option( 'search_results_cache_lifetime' ) ? get_option( 'search_results_cache_lifetime' ) : SUPAPRESS_CACHE_LIFETIME_DEFAULT );

define( 'SUPAPRESS_CACHE_LIFETIME_ISBN_LOOKUP', get_option( 'isbn_lookups_cache_lifetime' ) ? get_option( 'isbn_lookups_cache_lifetime' ) : SUPAPRESS_CACHE_LIFETIME_DEFAULT );

require_once SUPAPRESS_PLUGIN_DIR . '/settings.php';
