<?php

namespace WCORL;

function grab_splines() {
  list( $db_splines, $had_error ) = grab_splines_from_db();
  if ( $had_error ) {
    return false;
  }

  list( $splines, $tracks ) = grab_splines_raw( $db_splines, time() );

  array_walk( $tracks, 'wcorl_track' );

  return $splines;
}

function grab_splines_raw( $db_splines, $now ) {
  list( $valid, $expired ) = array_partition( $db_splines, expires_after( $now ) );

  $expired_tracks = array_map( track_for_expired( $now ), $expired );

  $splines = array_map( 'db_spline_to_spline', $valid );
  $spline_track = array( 'name' => 'splineRequest', 'count' => count( $valid ) );

  return array( $splines, array_merge( $expired_tracks, $spline_track ) );
}

function grab_splines_from_db() {
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

function expires_after( $time ) {
  return function( $spline ) {
    return $spline[ 'expiration' ] > $time;
  };
}

function track_for_expired( $time ) {
  return function( $spline ) {
    return array(
      'name' => 'foundExpiredSpline',
      'splineId' => $spline[ 'id' ],
      'foundOn' => $time
    );
  };
}

function db_spline_to_spline( $spline ) {
    return (object) array(
      'id' => $spline[ 'id' ],
      'vertices' => json_decode( $spline[ 'vertices' ] )
    );
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
