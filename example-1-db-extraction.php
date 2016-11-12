<?php

function wcorl_grab_splines() {
  global $wpdb;

  $blog_id = get_current_blog_id();

  $spline_count = $wpdb->query( $wpdb->prepare(
    'SELECT * FROM wcorl_splines WHERE blog_id = %d', $blog_id
  ) );

  if ( ! $spline_count ) {
    error_log( 'Error fetching splines' );
    return false;
  }

  $db_splines = $wpdb->get_results();
  foreach( $splines as $db_spline ) {
    if ( $db_spline[ 'expiration' ] < time() ) {
      wcorl_track( array(
         'name' => 'foundExpiredSpline',
         'splineId' => $db_spline[ 'id' ],
         'foundOn' => time()
      ) );
      continue;
    }

    $spline = new stdClass();

    $spline->id = $db_spline[ 'id' ];
    $spline->vertices = json_decode( $db_spline[ 'vertices' ] );

    $splines[] = $spline;
  }

  wcorl_track( array(
    'name' => 'splineRequest',
    'count' => $spline_count
  ) );

  return $splines;
}
