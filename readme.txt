=== Supafolio ===
Contributors: Supadu
Tags: supadü, supadu, folio, books, publishers, supafolio, supadu for wordpress, supapress, supafolio for wordpress
Requires at least: 6.0
Tested up to: 6.6.2
Stable tag: 2.24.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Quickly and easily connect your book metadata (ONIX) to your WordPress site.

== Description ==

The [Supafolio WordPress plugin](https://www.supadu.com/) allows trade publishers to access powerful features through WordPress previously only available to Supacms users which can be easily embedded in your pages using shortcodes.

**Features include:**

* **Metadata.** Display the metadata (e.g. ONIX) for any book in your catalog, including cover image, title, subtitle, author, price, format and link by typing in the ISBN13.
* **Layouts.** Choose how to display your books through a variety of layouts including grid, carousel and list.
* **Control.** Change the metadata that is displayed on the page by overwriting the underlying data through the SupaFolio dashboard.

== Installation ==

1. Install the Supafolio plugin either via the WordPress.org plugin directory, or by uploading the entire `supapress` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. After activating the Supafolio plugin try out the example widget that has been created or add you API key in the settings page

You will find 'Supafolio' menu in your WordPress admin panel.

== Screenshots ==

1. Go to Supafolio > Settings to add your API Key.
2. Use the Links tab in the settings to create you custom book page url patterns.
3. Go to Supafolio > Add New to start creating your widget - add a new and select which type of widget you wish to create.
4. If you're creating an ISBN Lookup Widget, use the content tab to set your books.
5. Use the elements tab to set which elements you wish to display in your widget.
6. Use the Arrangement tab to choose which default layout to use, these layouts can be overridden in your theme.

== Changelog ==

= 2.24.8 =
Release Date: Oct 2024
* Bug: check added in setAdditionalParams function

= 2.24.7 =
Release Date: Sep 2024
* Update: Edition field added as option
* Update: wpseo_twitter_title added
* Update: supapress_seo_override_url edited

= 2.24.0 =
Release Date: Aug 2024
* Update: supapress_run_canonical_redirect added

= 2.23.4 =
Release Date: Nov 2023
* Update: Author Bio hidden by default
* Update: JPY locale currency added
* Update: Adding Series show/hide option in modules

= 2.23.3 =
Release Date: Aug 2023
* Update: restriction by category feature added

= 2.23.2 =
Release Date: July 2023
* Fix: folio url fixed on edit collection link

= 2.23.1 =
Release Date: April 2023
* Fix: preg_match for isbn13 fixed

= 2.23.0 =
Release Date: March 2023
* Update: restriction by series feature added
* Update: supafolio module button removed from wysiwig editor
* Fix: enqueue_scripts function fixed

= 2.22.4 =
Release Date: January 2023
* Fix: fix issue with SVN from previous release

= 2.22.1 =
Release Date: January 2023
* Fix: error non static method
* Fix: sales_date_format fix

= 2.22.0 =
Release Date: January 2023
* cleanup of release files

= 2.21.0 =
Release Date: January 2023

* Fix: SUP-5430 remove old references to supafolio.com
* Fix: SUP-3797 reduce calls to the API by allowing include_price to be part of each details call

= 2.20.4 =
Release Date: December 2021

* Fix: renderItem js error fixed

= 2.20.3 =
Release Date: March 2021

* Fix: Support for Wordpress 5.6
* Fix: Fixing deprecated jquery issue and mixed content issue
* Fix: Added missing translation for over and under �2

= 2.20.2 =
Release Date: December 2020

* Fix: A bug in the canonical support

= 2.20.0 =
Release Date: September 2020

* Enhancement: Languages and translation support
* Enhancement: Welsh (cy) language support
* Enhancement: French (fr-ca) language support
* Enhancement: Spanish (es-mx) language support
* Enhancement: Added composer file
* Update: canonical support for seo
* Fix: Overflow fix on search filters
* Fix: Price part isset fix
* Fix: Arrangement option for books to scroll by 5

= 2.19.6 =
Release Date: August 2020

* Fix: Extra check when author isn't present
* Enhancement: extracted the currency symbol functionality can be used with get_currency_symbol

= 2.19.5 =
Release Date: May 2020

* Fix: resolve incorrect version number after migration

= 2.19.4 =
Release Date: May 2020

* Fix: angular blog details should be an array with site_url to work
* Fix: retailers was missing an array check
* Fix: check yoast or yoast premium versions to use appropriate filter
* Enhancement: use wpseo_opengraph_url filter instead

= 2.19.3 =
Release Date: April 2020

* Fix: Make sure old retailer links are rendered


= 2.19.2 =
Release Date: April 2020

* Enhancement: Tracking autocomplete options to include price and discount for usd, cad and gbp
* Enhancement: Frontend to show prices if set on autocomplete
* Enhancement: Updated retailer links to be orderable by dragging and dropping
* Enhancement: Separate get_retailer_links check into multiple lines
* Enhancement: Added filter to allow theme to set permission on clear cache button
* Fix: Added check to get_retailer_links method in book.php
* Fix: If statement removed to not return no books message directly in the supafolio_call function

= 2.19.1 =
Release Date: January 2020

* Enhancement: Improved XSS injection protection
* Enhancement: Filter by format set on shortcode attribute
* Enhancement: Subtitle option added to urls for seo purposes
* Enhancement: Searchfilter service being used to return collections instead of collection service
* Fix: Apply the clear all link for layout with sub categories in filters
* Fix: Add a check to make sure book details page has been set
* Fix: Default value added for retailer name
* Fix: Remove possible extra forward slashes on book details pages

= 2.19.0 =
Release Date: December 2019

* New Feature: Enhanced retailer links functionality

= 2.18.5 =
Release Date: September 2019

* Enhancement: Added ability to ignore filter heading skips by using a hook 
* Enhancement: Update supafolio override logic to use PHP closures 
* Enhancement: Update supafolio SEO checks 
* Enhancement: Added publisher imprint params in in setAdditionalParams function

= 2.18.3 =
Release Date: April 15 2019

* Enhancement: Added a filter for adding custom parameters to the supapress filters call
* Enhancement: Improved Angular support
* Bug fix: Filter out slashes from the folio calls
* Bug fix: Stop arrays from being passed to the folio calls

= 2.18.2 =
Release Date: January 21 2019

* Enhancement: Added order attribute to shortcode
* Enhancement: Added get_params function
* Bug fix: php 7.2 changing sizeof to !empty check

= 2.18.1 =
Release Date: October 8 2018

* Enhancement: Filters layout AJAX call is now GET instead of POST so can be cached
* Bug fix: Remove notice when contributors array from product details API call is empty

= 2.18.0 =
Release Date: August 28 2018

* Enhancement: Added ability to use file-based caching instead of transient-based

= 2.17.1 =
Release Date: August 09 2018

* Bug fix: Fixed has_book and has_books when API 'result' is a string and not an object or array

= 2.17.0 =
Release Date: August 08 2018 

* Enhancement: Added more API options to the search results shortcode
* Update: Changed default service URL
* Bug fix: Clear cache no longer exhausts memory on large sites
* Enhancement: Added filter to override sortby label

= 2.16.2 =
Release Date: November 20 2017

* Enhancement: Added utility function to get search messages
* Bug: Updated order in which different search order parameter names are detected to prioritise 'supapress_order' over 'order'

= 2.16.1 =
Release Date: November 2 2017

* Bug: Fix to readme format

= 2.16.0 =
Release Date: November 2 2017

* Enhancement: Added og:image and twitter:image tags when overriding SEO on product details widgets
* Bug: Replaced PHP4 style widget constructors with PHP7 compatible ones

= 2.15.1 =
Release Date: October 17 2017

* Bug: Pagination and Sort query parameters now work when there is a hash on the page URL

= 2.15.0 =
Release Date: September 25 2017

* Enhancement: Ability to set the service call cache duration for each type of module 

= 2.14.0 =
Release Date: September 14 2017

* Bug: Sort by order param changed to supapress_order so it does not clash with default search order
* Enhancement: SEO Description override no longer can contain HTML tags and is capped at 160 characters

= 2.13.2 =
Release Date: August 18 2017

* Bug: Fixed readme format

= 2.13.1 =
Release Date: August 04 2017

* Bug fix: Fixed bug where custom layout would save but not display the saved value

= 2.13.0 =
Release Date: August 02 2017

* Bug fix: Imprint and Publisher text is no longer always printed
* Enhancement: Active filter now has an active class

= 2.12.0 =
Release Date: July 12 2017

* Bug fix: resolved a lot of warnings/errors displayed when WP_DEBUG is true
* Bug fix: resolved issue with URL template replacements being used in descriptions and titles for SEO overrides
* Bug fix: resolved warnings created by SEO overrides being used on product details
* Bug fix: search term message no longer appears when keyword parameter is present but empty
* Enhancement: Added Publisher and Imprint elements to all module types

= 2.11.0 =
Release Date: June 26 2017

* Bug fix: Custom templates will now load from child themes
* Bug fix: Custom templates will now store their paths relative to site root
* Enhancement: Added Author Bio element to all module types

= 2.10.1 =
Release Date: May 26 2017

* Bug fix: Fixed renamed function

= 2.10.0 =
Release Date: May 25 2017

* Bug fix: Search filters API calls are cached again
* Bug fix: Improved performance of image alignment
* Enhancement: Added ability to set permanent Imprint and Publisher filters on a Search Results module
* Enhancement: Added filters for book cover URL

= 2.9.8 =
Release Date: May 12 2017

* Bug fix: Fixed issue where JS was not added to page for AngularJS applications

= 2.9.7 =
Release Date: May 10 2017

* Bug fix: Fixed issues with drop downs in admin when jQuery migrate is turned off
* Enhancement: Added sort order options for Author A-Z and Author Z-A

= 2.9.6 =
Release Date: May 04 2017

* Bug fix: Fix issue with page slow down when processing carousel images which were not lazy loaded

= 2.9.5 =
Release Date: April 27 2017

* Bug fix: Fix version number

= 2.9.4 =
Release Date: April 27 2017

* Security: Fix possible XSS injection attack vulnerabilities

= 2.9.3 =
Release Date: April 26 2017

* Bug fix: Fixed style incompatibility with ACF that stopped the insert module drop down from working
* Enhancement: Added supapress_filter_heading_before and supapress_filter_heading_after filters to give more control of filter headers

= 2.9.2 =
Release Date: April 13 2017

* Bug fix: Fixed default setting for Product Details custom layout

= 2.9.1 =
Release Date: April 11 2017

* Bug fix: Removed console log

= 2.9.0 =
Release Date: April 11 2017

* Enhancement: Lazy load carousel images option added
* Enhancement: Upgraded Slick to 1.6
* Bug fix: scripts.min.js no longer loads twice on carousel pages
* Bug fix: add_object_page() replaced with add_menu_page()
* Bug fix: Parameters are now passed to custom layout files

= 2.8.3 =
Release Date: February 15 2017

* Bug fix: Fixed readme format

= 2.8.2 =
Release Date: February 15 2017

* Bug fix: WPSEO override no longer returns false on pages it should not override

= 2.8.1 =
Release Date: February 15 2017

* Bug fix: Multi-site URLs are now working relatively

= 2.8.0 =
Release Date: February 13 2017

* Enhancement: Demo catalog now uses v2 search by default
* Enhancement: Plugin parameters now available in overridden filters layouts and custom templates
* Enhancement: Added filter to skip output of filters even when selected
* Bug Fix: Custom layout wrappers now have correct CSS classes

= 2.7.1 =
Release Date: January 31 2017

* Bug fix: Multi site link URL fix no longer breaks external links
* Bug fix: Baseline image now works for all layouts 

= 2.7.0 =
Release Date: January 25 2017

* Enhancement: Improved widget panel UI and UX
* Bug fix: Fixed multi site link URLs

= 2.6.3 =
Release Date: January 25 2017

* Bug fix: Removed console logs

= 2.6.2 =
Release Date: January 23 2017 

* Bug fix: Fixed readme.txt

= 2.6.1 =
Release Date: January 23 2017

* Bug fix: No results override message is now output correctly

= 2.6.0 =
Release Date: January 17 2017

* Bug fix: No results override message is now output correctly
* Bug fix: baseline class now works correctly in IE11
* Bug fix: ISBN lookup ordering is now preserved in both front end and admin
* Enhancement: 7 and 8 per row options added
* Enhancement: Added support for custom templates
* Enhancement: Added ability to show a search term message e.g. 'You have searched for "X"'
* Enhancement: Added ability to show pagination message e.g. 'Results 1-10 of 100'
* Enhancement: Added support for overriding page title and description, under SEO settings

= 2.5.0 =
Release Date: October 25 2016

* Bug fix: API Calls within the admin no longer use the cache so changes from the Supafolio dashboard can be seen instantly
* Bug fix: Cache clearing now works correctly for multi-site installs
* Enhancement: Pagination elements with only one page are now hidden by default. An option has been added to control this behaviour in the Search Pagination element
* Enhancement: New date format added for Sale and Publication date output 'January 1970'
* Enhancement: Search per page default value is no longer optional
* Enhancement: Trailing slashes are now removed from book page URLs

= 2.4.1 =
Release Date: October 11, 2016

* Bug Fix: Fixed issue with book pages not loading when format is used in the URL pattern

= 2.4.0 =
Release Date: September 30, 2016

* Bug Fix: Add class back onto filter items for backwards compatibility
* Bug Fix: Fixed issue with filter group limit being set to 0
* Enhancement: `exclude_imprint`, `exclude_category`, `from`, `to` added as new search filterable parameters
* Enhancement: `collection` added as shortcode attribute to filter specific search / lookup modules
* Enhancement: `series` added as shortcode attribute to filter specific search / lookup modules
* Bug Fix: ID attributes added to pagination form elements per page and sort by for AA compliance
* Bug Fix: Fixed issue which carousel image sizes on tablet orientation
* Enhancement: `%format%` added as a possible url part for book detail pages
* Bug Fix: Fixed issue with new version of jQuery no longer support `$.browser`

= 2.3.2 =
Release Date: May 27, 2016

* Bug Fix: Fixed issue on re-ordered select2 elements were the user was unable to select the first option

= 2.3.1 =
Release Date: May 26, 2016

* Bug Fix: Fixed styling on select2 elements being used by other 3rd party plugins

= 2.3.0 =
Release Date: May 25, 2016

* Enhancement: Dashboard widget created for the Pages and Posts editor to allow users to quickly and easily choose the shortcodes they wish to add to the content
* Enhancement: The view for Search Results Filters can now be overridden
* Enhancement: WP Filters added for the Search Results Filters so developers can filter out specific filters and change filter group headings easily from the theme `functions.php` file
* Enhancement: `get_assets` function added to the Book class so developers can access any asset attached to a book
* Enhancement: Default CSS class names added to all elements by default so developers do not need to manually set them
* Bug Fix: Service parameters decoded before they are encoded to prevent double encoding issues
* Bug Fix: URL rewrite functions prefixed with `supapress` to avoid function name clashes
* Enhancement: `set_book` function added to the Book class so books from secondary service calls made in the view can be passed through the same class
* Bug Fix: `supapress_order` added as a possible URL parameter for setting the search results ordering
* Enhancement: Listener added to the DOM so carousels and search results will work when dynamically added to the page
* Enhancement: Support for sites built in AngularJS added. Simply enable AngularJS in the settings and add the dependancy to your AngularJS application
* Bug Fix: Cache clear function improved to clear if sites are not using the default `wp_` prefix
* Bug Fix: Make blank book url link not include the domain so the domain is not included on install
* Enhancement: Dropdowns updated to list options in alphabetical order for better usability
* Enhancement: Edit link added to the Supafolio Module widget so users can quickly get to the edit screen 
* Enhancement: Build packages updated to the latest versions
* Bug Fix: Buggy fade out removed from hidden elements when switching between module types
* Bug Fix: Buggy `ISBN not found` when adding an ISBN with an apostrophe fixed

= 2.2.0 =
Release Date: February 4, 2016

* Bug Fix: Update to the_reviews to stop blank reviews being printed out
* Enhancement: Publisher added as a possible URL part for book details
* Enhancement: ISBN-10 added as a possible URL part for book details
* Bug Fix: URI decoding fixing when using pagination to stop the keyword changing
* Bug Fix: Slashes removed from search bar when searching with and apostrophe

= 2.1.3 =
Release Date: January 18, 2016

* Bug Fix: The URL pattern for book pages has been updated to be relative to avoid issues when migrating a site
* Bug Fix: ISBN lookup updated to skip service calls if search by collection is set but no collection has been set
* Bug Fix: All layouts updated to include post ID in the wrappers ID attribute to avoid invalid HTML when there are multiple modules on a page
* Enhancement: Shortcode added to the notice bar on save or update for quicker copying
* Enhancement: ISBN lookup updated to allow users to input multiple ISBNs at once
* Enhancement: Module type column moved on list table for better visibility
* Bug Fix: Missing settings added to the uninstall script
* Enhancement: Text still referencing `widgets` updated to reference `modules`
* Bug Fix: Form navigation updated to hide element configurations if those elements are not available when changing module type

= 2.1.2 =
Release Date: December 21, 2015

* Bug Fix: Fixed no results message on the search results not coming through
* Bug Fix: Swapped default wrappers for book summary and description to divs for HTML validity
* Bug Fix: Fixed issue with checkbox column on the Supafolio module table being too wide when other options were hidden
* Enhancement: Type column added to Supafolio module table for improved ordering
* Bug Fix: Fixed width issue on book covers where the width was 0 in some themes
* Bug Fix: API key added to hash for cache key so results are not loaded from cache when the API key is changed
* Bug Fix: Fixed issue where save button would become visible when repeatedly clicked on the Cache tab
* Enhancement: Text change on cache cleared message

= 2.1.1 =
Release Date: December 10, 2015

* Enhancement: Caching added to the plugin so all API calls will now be cached for 24 hours. These can be cleared in the admin by going to Supafolio > Settings > Cache
* Bug Fix: Incorrect layout selected when switch from ISBN Carousel to Search Results
* Enhancement: Additional Supafolio API params added as shortcode attributes to request extra data like other format prices
* Bug Fix: Disabled options on prices element dropdown fixed to change when adding / removing options
* Enhancement: Updated widget to include title as part of the attribute data
* Enhancement: Ability to view the service URL by adding `?debug` to the browser URL
* Bug Fix: Fixed `isset` errors showing for filter lists
* Enhancement: CSS updates for responsive sites missing certain styles in the theme
* Security: Direct access to all PHP files removed
* Enhancement: Extra searchable filters added
* Enhancement: Retailer names added to retailer links for CSS targeting
* Security: Permissions to use the plugin changed to be editors and above only
* Enhancement: Styling fixes to element tab
* Enhancement: Carousel layout update to be more flexible which which wrapper is the carousel parent wrapper for easier HTML changes

= 2.1.0 =
Release Date: November 04, 2015

* New admin page interface to match branding to Supafolio
* Enhancement: Added support for IE9, IE10 and Microsoft Edge
* Bug Fix: Fixed bottom bulk actions on the list page
* Enhancement: Improved UX and to the per page element config
* Enhancement: New function `get_pagination` added to the view to get pagination details from search
* Enhancement: Imprint add as a URL part of the book URLs
* Enhancement: Book service URL improved to accept URL with or without `http`

= 2.0.8 =
Release Date: October 07, 2015

* Enhancement: Added support for Safari
* Enhancement: Config options added to search results `per_page` element to allow user to set the drop down options
* Enhancement: Added url support for sites without rewrite rules turned on
* Bug Fix: Fixed responsive layout issues on product details pages
* Enhancement: Updated search results filters so they have a show more / show less feature (labels and limits can be set in the template) to avoid long lists on the page
* Bug Fix: Search results filters pre-loader fix to make sure it is always center aligned
* Enhancement: Relevance added as the default option for the `sort_by` element when creating new widgets
* Bug Fix: API calls url encoded to fix issue with spaces in keyword searches
* Enhancement: `amount` attribute can now be added to ISBN Lookup widgets

= 2.0.7 =
Release Date: September 23, 2015

* Bug Fix: Encode image alt tag so html source is not broke by quotes
* Enhancement: Added ability to pass custom attributes on shortcodes
* Enhancement: Set search results default amount with `amount` attribute on shortcode
* Enhancement: Added date format config to publication date element
* Enhancement: Added on sales date element
* Enhancement: Added function to return books count
* Enhancement: Added functions to return configuration settings and attributes

= 2.0.6 =
Release Date: September 14, 2015

* Enhancement: Select element updated to work cross browser and also to have filtering abilities
* Bug Fix: Clear left added to floats for 5 per row grid layouts
* Bug Fix: JS validation updated to force 13 digit numbers only for manually entered ISBNs
* Enhancement: Colour of placeholder text updated for better UX
* Enhancement: Edit collection link updated to go directly to books tab
* Enhancement: Widget updated to return full list instead of filtering by ISBN Lookups only
* Bug Fix: Filter for book pages drop down updated to allow external links
* Enhancement: Functions added to model to allow raw data to be grabbed in the layout for custom html updates

= 2.0.5 =
Release Date: September 07, 2015

* Bug Fix: Plugin security permissions updated to `edit_posts`

= 2.0.4 =
Release Date: September 07, 2015

* Enhancement: Default price locale added for the price element configuration
* Enhancement: External link option added to book page dropdown
* Bug Fix: Text change to link on plugins page

= 2.0.3 =
Release Date: September 07, 2015

* Bug Fix: Custom rewrite rules updated to ignore `Link not in use`

= 2.0.2 =
Release Date: September 07, 2015

* Bug Fix: Install example ISBN list updated to key value pair to include book titles

= 2.0.1 =
Release Date: September 07, 2015

* Bug Fix: `package.json` file removed from plugin

= 2.0.0 =
Release Date: September 07, 2015

* Enhancement: First release of the plugin launched

= 1.0.0 =
Release Date: February 16, 2015

* Prototype created
