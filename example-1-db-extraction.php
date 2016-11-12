<?php

function wcorl_grab_splines() {
  list(
      $splines,
      $had_error,
      $tracks
  ) = wcorl_grab_splines_raw();

  if ( $had_error ) {
    return false;
  }

  array_walk( $tracks, 'wcorl_track' );

  return $splines;
}

function wcorl_grab_splines_raw() {
  list( $db_splines, $db_error ) = wcorl_grab_splines_from_db();

  list( $valid_splines, $expired_splines ) = array_partition(
    $db_splines,
    function( $spline ) {
      return $spline[ 'expiration' ] > $now;
    }
  );

  $expired_tracks = array_map( function( $spline ) use ( $now ) {
    return array(
      'name' => 'foundExpiredSpline',
      'splineId' => $spline[ 'id' ],
      'foundOn' => $now
    );
  }, $expired_splines );

  $splines = array_map( function( $spline ) {
    return (object) array(
      'id' => $spline[ 'id' ],
      'vertices' => json_decode( $spline[ 'vertices' ] )
    );
  }, $valid_splines );

  return array(
    $splines,
    $db_error,
    array_merge(
      $expired_tracks,
      array( array(
        'name' => 'splineRequest',
        'count' => count( $valid_splines )
      ) )
    )
  );
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

function array_partition( $array, $predicate ) {
  $left = array();
  $right = array();

  foreach ( $array as $item ) {
    if ( $predicate( $item ) ) {
      $left[] = $item;
    } else {
      $right[] = $item;
    }
  }

  return array( $left, $right );
}
