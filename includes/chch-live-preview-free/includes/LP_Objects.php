<?php

/**
 * LP_Objects
 * 
 * @package CHC LIVE PREVIEW
 * @author 
 * @copyright 2015
 * @version $Id$
 * @access public
 */
class LPF_Objects {

  protected static $LP_instances = array();


  /**
   * LP_Objects::add()
   * 
   * @param mixed $lp_instance
   * @return
   */
  public static function add( CHCH_LIVE_PREVIEW_FREE $lp_instance ) {
    self::$LP_instances[$lp_instance->lp_id] = $lp_instance;
  }

  /**
   * LPF_Objects::get_all()
   * 
   * @return
   */
  public static function get_all() {
    return self::$LP_instances;
  }
   
  public static function get_lp_object($object_id) {
    if(isset(self::$LP_instances[$object_id])) { 
      return self::$LP_instances[$object_id];
    } else {
      return false;
    }
    
  }

  /**
   * LP_Objects::get_all_post_types()
   * 
   * @return
   */
  public static function get_all_post_types() {
    $lp_post_types = array();

    foreach ( self::$LP_instances as $lp ) {

      $lp_post_types[$lp->lp_id] = $lp->get_param( 'target_post_types' );
    }
    return $lp_post_types;
  }

}
