=== SFG Medicare Extras ===
Contributors: TheWebist
Donate link: https://mwender.com/
Tags: shortcodes
Requires at least: 6.3
Tested up to: 6.3.1
Requires PHP: 8.0
Stable tag: 0.9.9
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Extras for the SFG Medicare website.

== Description ==

This plugin provides extra functionality for the SFG Medicare website.

=== Elementor Style Button Shortcode ===

Use `[button/]` to render an "Elementor-style" button.

```
/**
 * Renders an Elementor button.
 *
 * @param      array  $atts {
 *   @type  string  $icon       Font Awesome icon class name.
 *   @type  string  $icon_align Aligns the icon `left` or `right`. Default `left`.
 *   @type  string  $link       The URL the button will point to.
 *   @type  string  $target     Value of the target attribute for the anchor tag. Defaults to `_self`.
 *   @type  string  $text       The text for the button. Default "Click Here".
 *   @type  string  $size       The size of the button ( xs, sm, md, lg, xl ). Defaults to `sm`.
 *   @type  string  $style      Styling applied to the style attribute of the parent anchor.
 * }
 *
 * @return     string  The HTML for the button.
 */
```

=== Render Template Shortcode ===

```
/**
 * Renders a handlebars template
 *
 * @param      array  $atts {
 *   @type  string  $data         Key/Value pairs of data to send to the template. Formatted like
 *                                so `key1|value1::key2|value2`. `meta` is a special key which causes the
 *                                shortcode to check the current post for a custom field with the
 *                                name given by the "value". Example: `meta|radius_scheduler` will
 *                                check the current post for a meta field called `radius_scheduler`.
 *                                If found, the $data array passed to the template will have a key
 *                                called `radius_scheduler` with the value of the custom field.
 *   @type  string  $hidecss      CSS selector used to hide this element if $hideifempty is TRUE.
 *                                Default null.
 *   @type  bool    $hideifempty  Used with the special key `meta` in $data. If any meta
 *                                meta values are emtpy, hide this template if `true`.
 *                                Defaults to TRUE.
 *   @type  string  $hideelement  ID of an element to hide when the template rendered by
 *                                this shortcode is shown. Default NULL.
 *   @type  string  $template     The template we are rendering. Default NULL.
 * }
 *
 * @return     string  HTML for the template.
 */
```

=== Sub Pages List ===

Add `[subpage_list/]` to display a list of sub pages.

```
/**
 * Show a listing of child pages.
 *
 * @param      array  $atts {
 *   @type  string  $orderby    The column we are ordering by. Defaults to "menu_order".
 *   @type  string  $sort       How we are ordering the results. Defaults to ASC.
 *   @type  string  $parent     The post_title of the parent page whose child pages we want to list. Defaults to `null`.
 * }
 *
 * @return     string  HTML for the subpage list.
 */
```

=== Team Member List Shortcode ===

Add `[team_member_list]` to list Team Member CPTs.

```
/**
 * Lists Team Member CPTs.
 *
 * @param      array  $atts {
 *   @type  string  $type       Staff Type taxonomy slug.
 *   @type  string  $orderby    Value used to order the query's results. Defaults to `title`.
 *   @type  string  $order      Either ASC or DESC. Defaults to `ASC`.
 *   @type  bool    $linktopage Should we link to the Team Member's page? Defaults to TRUE.
 * }
 *
 * @return     string  HTML for listing Team Member CPTs.
 */
```

=== Webinar Registration Link Shortcode ===

Add `[webinar_registration_link]` to any event post to link to the Webinar Registration page with the event date/time matching the event where you added the shortcode.

```
/**
 * Returns a link to the webinar registration page.
 *
 * @param      array  $atts {
 *   @type  string  $registration_link URL to the webinar registration page. Defaults to /webinar-registration/.
 * }
 *
 * @return     string  The webinar link.
 */
```

== Changelog ==

= 0.9.9 =
* Setting default value for `$start_date` inside `lib/fns/shortcodes.php::get_webinar_link()` to prevent fatal error while editing in Elementor.

= 0.9.8 =
* Linking Team Member photos in `team-members.hbs`.
* Updating `[subpage_list/]` to respect the `parent` attribute.
* Adding `hidecss` attribute to `[rendertemplate/]` shortcode.

= 0.9.7 =
* Adding processing for Post data to `[rendertemplate/]` shortcode.
* Updating the layout in `radius-scheduler.hbs`.

= 0.9.6 =
* Adding logic for `[rendertemplate hideifempty=true /]`.

= 0.9.5 =
* Initial implementation of Radius Scheduler link option for Team Members.
* Adding `[rendertemplate\]` shortcode.
* Updating `get_alert()` to use Handlebars rendering.

= 0.9.4 =
* Adding `gettext` filter to remove "Search Results for:" from Search archive title.

= 0.9.3 =
* Setting `echo` to false when calling `wp_list_pages()` for `[subpage_list/]`.

= 0.9.2 =
* Adding `<ul>' around `[subpage_list/]`.

= 0.9.1 =
* Updating `[subpage_list/]` to use `wp_list_pages()`.

= 0.9.0 =
* Adding `[button/]` shortcode for rendering Elementor-style buttons.

= 0.8.1 =
* Adding `parent` attribute to `[subpage_list/]`.

= 0.8.0 =
* Adding `[subpage_list/]` shortcode for listing subpages of the current page.

= 0.7.1 =
* Updating `[team_member_list/]` query variable from `numberposts` to `posts_per_page` to match expected parameter in WP_Query.

= 0.7.0 =
* Adding `linktopage` option for `[team_member_list]` shortcode.

= 0.6.0 =
* Adding `orderby` option for `[team_member_list/]`. Ordering by `title` uses a special query filter which sorts by the last word in the Team Member's title (i.e. the last name).
* Outputting default phone number when a Team Member's phone number is empty.
* Setting value for Team Member `$data['tel']` for the `tel` link in the Handlebars template for Team Members.

= 0.5.0 =
* New shortcode: `[team_member_list/]`.
* Adding CSS/SCSS.
* CSS build via NPM scripts.
* Adding `SFG_CSS_DIR` and `SFG_DEV_ENV` constants.

= 0.4.0 =
* Saving ACF settings to `lib/acf-json/`.
* Adding custom columns to Staff Member admin listing.
* Handlebars processing for templates.

= 0.3.0 =
* Adding event time to registration link.

= 0.2.0 =
* Adding "Past Event" logic to `[webinar_registration_link]`.
* Adding Elementor button style for `[webinar_registration_link]`.

= 0.1.0 =
* Initial implementation of the `[webinar_registration_link]` shortcode.
