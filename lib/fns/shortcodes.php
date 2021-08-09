<?php
namespace sfgmedicare\shortcodes;
use function sfgmedicare\utilities\{get_alert,posts_orderby_lastname};
use function sfgmedicare\templates\{render_template};

/**
 * Returns a link to the webinar registration page.
 *
 * @param      array  $atts {
 *   @type  string  $events_page URL of the Events page. Defaults to /events/.
 *   @type  string  $registration_link URL to the webinar registration page. Defaults to /webinar-registration/.
 * }
 *
 * @return     string  The webinar link.
 */
function get_webinar_link( $atts ){
  $args = shortcode_atts([
    'events_page' => site_url() . '/events/',
    'registration_link' => site_url() . '/webinar-registration/',
  ], $atts );

  global $post;

  if( ! function_exists( 'tribe_get_event' ) )
    return get_alert(['title' => 'Missing Plugin', 'description' => 'This shortcode requires <a href="https://wordpress.org/plugins/the-events-calendar/" target="_blank">The Events Calendar</a> plugin.']);

  $event = tribe_get_event( $post );
  $dates = $event->dates;
  $start_date = $dates->start->format('U');
  $current_date = current_time( 'timestamp' );

  $event_details = tribe_events_event_schedule_details( $post->ID );
  $event_details = preg_replace( '/\<div class="recurringinfo"\>.*<\/div>/', '', $event_details );
  $event_details = strip_tags( $event_details );

  if( $start_date < $current_date ){
    return '<p>Registration: This event has already past. Please see our <a href="' . $args['events_page'] . '">events page</a> for upcoming webinars.</p>';
  } else {
    return '<div class="elementor-button-wrapper" style="margin: 1em 0;">
      <a href="' . $args['registration_link'] . '?datetime=' . urlencode( $event_details ) . '" class="elementor-button-link elementor-button elementor-size-md" role="button">
            <span class="elementor-button-content-wrapper">
            <span class="elementor-button-text"><strong>Register for this Webinar</strong> - ' . $event_details . '</span>
    </span>
          </a>
    </div>';
  }
}
add_shortcode( 'webinar_registration_link', __NAMESPACE__ . '\\get_webinar_link'  );

/**
 * Show a listing of child pages.
 *
 * @param      array  $atts {
 *   @type  string  $orderby    The column we are ordering by. Defaults to "menu_order".
 *   @type  string  $sort       How we are ordering the results. Defaults to ASC.
 *   @type  string  $parent     The page of the child pages we want to list. Defaults to `null`.
 * }
 *
 * @return     string  HTML for the subpage list.
 */
function subpage_list( $atts ){
  $args = shortcode_atts( [
    'orderby' => 'menu_order',
    'sort'    => 'ASC',
    'parent'  => null,
  ], $atts );

  global $post;
  $query_args = [
    'parent'      => $post->ID,
    'sort_column' => $args['orderby'],
    'sort_order'  => $args['sort'],
  ];

  if( ! is_null( $args['parent'] ) ){
    $args['parent'] = html_entity_decode( $args['parent'] );
    $parent = get_page_by_title( $args['parent'] );
    if( $parent )
      $query_args['parent'] = $parent->ID;
  }

  $pages = get_pages( $query_args );
  foreach( $pages as $page ){
    $data['pages'][] = [
      'permalink' => get_page_link( $page->ID ),
      'title'     => get_the_title( $page->ID ),
    ];
  }

  return render_template( 'subpage-list', $data );
}
add_shortcode( 'subpage_list', __NAMESPACE__ . '\\subpage_list' );

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
function team_member_list( $atts ){
  $args = shortcode_atts([
    'type'        => null,
    'orderby'     => 'title',
    'order'       => 'ASC',
    'linktopage'  => true,
  ], $atts );
  $data = []; // The data we'll pass into our handlebars template

  $args['linktopage'] = filter_var( $args['linktopage'], FILTER_VALIDATE_BOOLEAN );
  $data['linktopage'] = $args['linktopage'];

  $orderby = ( ! in_array( $args['orderby'], [ 'title', 'menu_order' ] ) )? 'title' : $args['orderby'];
  $order = ( ! in_array( $args['order'], [ 'ASC', 'DESC' ] ) )? 'ASC' : $args['order'];

  $query_args = [
    'posts_per_page'  => -1,
    'post_type'       => 'team_member',
    'orderby'         => $orderby,
    'order'           => $order,
  ];
  if( ! is_null( $args['type'] ) ){
    $type = get_term_by( 'slug', strtolower( $args['type'] ), 'staff_type' );
    if( ! $type )
      return get_alert( ['title' => 'Staff Type Not Found', 'description' => '<strong>No `' . $args['type'] . '` Staff Type</strong><br>We could not locate the Staff Type you entered. Please check your spelling, and make sure the <code>type</code> you entered matches one of the Staff Types in the admin.'] );

    $query_args['tax_query'] = [
      [
        'taxonomy'  => 'staff_type',
        'field'     => 'slug',
        'terms'     => $args['type'],
      ]
    ];
  }

  // Sort by last name
  if( 'title' == $orderby )
    add_filter( 'posts_orderby', '\\sfgmedicare\\utilities\\posts_orderby_lastname' );
  // Query team members
  $team_member_query = new \WP_Query( $query_args );
  // Remove sort by last name filter
  if( 'title' == $orderby )
    remove_filter( 'posts_orderby', '\\sfgmedicare\\utilities\\posts_orderby_lastname' );

  if( ! $team_member_query->have_posts() )
    return get_alert( ['title' => 'No Team Members Found', 'description' => '<strong>No Team Members Found</strong><br/>No Team Members found. Please check your shortcode parameters.'] );

  if( $team_member_query->have_posts() ){
    while( $team_member_query->have_posts() ): $team_member_query->the_post();
      $name = get_the_title();
      $name_array = explode( ' ', $name );
      $lastname = array_pop( $name_array );
      $firstname = implode( ' ', $name_array );
      $meta = get_fields( get_the_ID(), false );

      $phone = ( ! empty( $meta['office_phone'] ) )? $meta['office_phone'] : '(865) 777-0153' ;
      $tel = ( ! empty( $meta['office_phone'] ) )? $meta['office_phone'] : '865-777-0153';

      $data['team_members'][] = [
        'name' => $name,
        'firstname' => $firstname,
        'permalink' => get_permalink( get_the_ID() ),
        'photo'     => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
        'title'     => $meta['title'],
        'bio'       => get_field( 'bio', get_the_ID() ),
        'email'     => $meta['email'],
        'phone'     => $phone,
        'tel'       => $tel,
      ];
    endwhile;
  }
  wp_reset_postdata();

  return render_template( 'team-members', $data );
}
add_shortcode( 'team_member_list', __NAMESPACE__ . '\\team_member_list' );