<?php

if ( !class_exists( 'CHCH_LIVE_PREVIEW_FREE_INIT' ) ) {


  class CHCH_LIVE_PREVIEW_FREE_INIT {

    /**
     * CHCH_LIVE_PREVIEW_FREE_INIT version.
     * @var   string
     * @since 1.0.0
     */
    const VERSION = '1.0.0';

    const PRIORITY = 9999;

    /**
     * Instance of CHCH_LIVE_PREVIEW_FREE_INIT class
     *
     * @var object - CHCH_LIVE_PREVIEW_FREE_INIT
     */
    public static $instance = null;


    /**
     * 
     *
     * @since 1.0.0
     */
    private function __construct() {  
      add_action( 'init', array( $this, 'init_chch_live_preview_free' ), self::PRIORITY );
    }


    public function init_chch_live_preview_free() {

      if ( !defined( 'CHCH_LIVE_PREVIEW_FREE_VERSION' ) ) {
        define( 'CHCH_LIVE_PREVIEW_FREE_VERSION', self::VERSION );
      }

      if ( !defined( 'CHCH_LIVE_PREVIEW_FREE_DIR' ) ) {
        define( 'CHCH_LIVE_PREVIEW_FREE_DIR', plugin_dir_path( __FILE__ ) );
      }

      if ( !defined( 'CHCH_LIVE_PREVIEW_FREE_URL' ) ) {
        define( 'CHCH_LIVE_PREVIEW_FREE_URL', plugin_dir_url( __FILE__ ) );
      }

     
      require_once 'includes/LP-include.php';

      
      do_action( 'chch_lpf_init' );

      LPF_wp_hooks::get_instance();


    }

    /**
     * Return an instance of CHCH_LIVE_PREVIEW class.
     *
     * @since  1.0.0
     * @return CHCH_LIVE_PREVIEW single instance object
     */
    public static function get_instance() {
      // If the single instance hasn't been set, set it now.
      if ( null === self::$instance ) {
        self::$instance = new self();
      }
      return self::$instance;
    }

  }

  // Make it alive
  CHCH_LIVE_PREVIEW_FREE_INIT::get_instance();
}
