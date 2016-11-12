<?php

function wcorl_grab_splines() {
  list( $db_splines, $db_error ) = wcorl_grab_splines_from_db();

  if ( $db_error === false ) {
    return false;
  }

  foreach( $db_splines as $db_spline ) {
    if ( $db_spline[ 'expiration' ] < time() ) {
      wcorl_track( array(
         'name' => 'foundExpiredSpline',
         'splineId' => $db_spline[ 'id' ],
         'foundOn' => time()
      ) );
      continue;
    }

    $splines[] = (object) array(
      'id' => $db_spline[ 'id' ],
      'vertices' => json_decode( $db_spline[ 'vertices'] )
    );
  }

  wcorl_track( array(
    'name' => 'splineRequest',
    'count' => $spline_count
  ) );

  return $splines;
}

function wcorl_grab_splines_from_db() {
  global $wpdb;

  $blog_id = get_current_blog_id();

  $spline_count = $wpdb->query( $wpdb->prepare(
    'SELECT * FROM wcorl_splines WHERE blog_id = %d', $blog_id
  ) );

  if ( ! $spline_count ) {
    error_log( 'Error fetching splines' );
    return array( array(), false );
  }

  return array( $wpdb->get_results(), true );
}
