<?php

class LPF_wp_hooks {

  /**
   * Instance of LP_wp_hooks class
   *
   * @var object - LP_wp_hooks
   */
  public static $instance = null;

  /**
   * Instance of LP_wp_hooks class
   *
   * @var object - LP_wp_hooks
   */
  private $lp_post_types = array( 'post' );

  /**
   * LP_wp_hooks::__construct()
   * 
   * @param mixed $lp
   * @return
   */
  public function __construct() {
    $this->lp_objects = is_array( LPF_Objects::get_all() ) ? LPF_Objects::get_all() : NULL;

    if ( NULL !== $this->lp_objects ) {
      $this->lp_post_types = LPF_Objects::get_all_post_types();
    }

    if ( is_admin() ) {
      $this->admin_hooks();
    }
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

  /**
   * Return an instance of CHCH_LIVE_PREVIEW class.
   *
   * @since  1.0.0
   * @return CHCH_LIVE_PREVIEW single instance object
   */
  private function get_all_post_types() {

    $post_types = array();

    foreach ( $this->lp_objects as $lp ) {
      $post_types[$lp->get_param( 'target_post_types' )];

    }

    return $post_types;

  }

  private function check_post_type( $check ) {

    return array_search( $check, $this->lp_post_types );

  }

  /**
   * LP_wp_hooks::admin_hooks()
   * 
   * @return
   */
  public function admin_hooks() {

    add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    add_action( 'edit_form_after_title', array( $this, 'templates_view' ) );

    add_action( 'save_post', array( $this, 'save_lp_data' ), 10, 3 );

    add_action( 'admin_init', array( $this, 'chch_lp_tinymce_event' ) );

    add_action( 'wp_ajax_chch_lpf_load_template', array( $this, 'load_template' ) );
    add_action( 'wp_ajax_chch_lpf_load_lp_form', array( $this, 'ajax_load_lpf_form' ) );
  }

  /**
   * LP_wp_hooks::register_scripts()
   * 
   * @return
   */
  public function register_scripts() {
    $this->register_styles();
    $this->register_js();
  }

  /**
   * LP_wp_hooks::enqueue_scripts()
   * 
   * @return
   */
  public function enqueue_scripts() {
    $screen = get_current_screen();
    if ( 'post' == $screen->base && ( $lp_id = $this->check_post_type( $screen->post_type ) ) ) {
      $this->enqueue_styles();
      $this->enqueue_js( $lp_id );
    }
  }

  /**
   * Registers styles for CMB2
   * @since 2.0.7
   */
  private function register_styles() {
    wp_register_style( 'lpf_ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css', null );
    wp_register_style( 'lpf_admin_style', CHCH_LIVE_PREVIEW_FREE_URL . 'css/admin.css', array( 'wp-color-picker' ) );
  }

  /**
   * LP_wp_hooks::register_js()
   * 
   * @return
   */
  private function register_js() {
    wp_register_script( 'lpf_js', CHCH_LIVE_PREVIEW_FREE_URL . 'js/lp-admin.js', array(
      'jquery',
      'jquery-ui-core',
      'jquery-ui-slider',
      'wp-color-picker' ) );
  }

  /**
   * LP_wp_hooks::enqueue_cmb_css()
   * 
   * @return
   */
  private function enqueue_styles() {
    wp_enqueue_style( 'lpf_ui' );
    return wp_enqueue_style( 'lpf_admin_style' );
  }

  /**
   * LP_wp_hooks::enqueue_cmb_js()
   * 
   * @return
   */
  private function enqueue_js( $lp_id ) {

    if ( !$lp_object = LPF_Objects::get_lp_object( $lp_id ) ) {
      return;
    }
    wp_enqueue_media();
    wp_enqueue_script( 'lpf_js' );

    wp_localize_script( 'lpf_js', 'chch_lpf_ajax_object', array(
      'ajaxUrl' => admin_url( 'admin-ajax.php' ),
      'chch_lpf_tpl_url' => $lp_object->get_param( 'tpl_url' ),
      'chch_lpf_base_css' => $lp_object->get_param( 'base_css' ) ) );

  }

  /**
   * Add Templates View 
   *
   * @since  0.1.0 
   */
  public function templates_view( $post ) {
    $screen = get_current_screen();

    if ( 'post' == $screen->base && ( $lp_id = $this->check_post_type( $screen->post_type ) ) ) {

      if ( !$lp_object = LPF_Objects::get_lp_object( $lp_id ) ) {
        return;
      }

      //create a new LP_Views object
      $lp_view = new LPF_Views( $lp_object );
      //get live preview view and form
      $lp_view->get_lp_view();
    }
  }

  /**
   * Save Post Type Meta
   *
   * @since  0.1.0 
   */
  function save_lp_data( $post_id, $post, $update ) {

    if ( !isset( $_POST['chch_lpf_save_nonce'] ) || !wp_verify_nonce( $_POST['chch_lpf_save_nonce'], 'chch_lpf_save_nonce_' . $post_id ) ) {
      return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      return;
    }

    if ( !current_user_can( 'edit_post', $post_id ) ) {
      return;
    }

    $screen = get_current_screen();
    if ( 'post' == $screen->base && ( $lp_id = $this->check_post_type( $post->post_type ) ) ) {

      if ( !$lp_object = LPF_Objects::get_lp_object( $lp_id ) ) {
        return;
      }

      if ( isset( $_REQUEST['_chch_lpf_template'] ) || !empty( $_REQUEST['_chch_lpf_template'] ) ) {
        $data = new LPF_Data( $lp_object, $_REQUEST['_chch_lpf_template'], $post_id );
        $data->save_post_fields( $_POST, $post_id );
      }

    }
  }

  /**
   * Register TinyMce event
   *
   * @since     1.0.0
   * 
   */
  function chch_lp_tinymce_event() {
    if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
      add_filter( 'mce_external_plugins', array( $this, 'chch_lp_tinymce_keyup' ) );
    }
  }

  /**
   * Add keyup to tineMce for WP version > 3.9
   *
   * @since     1.0.0
   * 
   */
  function chch_lp_tinymce_keyup( $plugin_array ) {
    $plugin_array['chch_lp_keyup_event'] = CHCH_LIVE_PREVIEW_FREE_URL . 'js/chch-tinymce.js';
    return $plugin_array;
  }

  public function load_template() { 
    $template = $_POST['template'];
    $lp_id = $_POST['lp_id'];
    $id = $_POST['post_id'];
    if ( !$lp_object = LPF_Objects::get_lp_object( $lp_id ) ) {
      echo 'something wrong with you LP, check configuration';
      die();
    }

    $template = new LP_Templates( $lp_object, $template, $id );
    $template->get_template();
    die();
  }

  public function ajax_load_lpf_form() {
    $template = $_POST['template'];
    $lpf_id = $_POST['lpf_id'];
    $id = $_POST['post_id'];
    if ( !$lpf_object = LPF_Objects::get_lp_object( $lpf_id ) ) {
      echo 'something wrong with you LP, check configuration';
      die();
    }

    //create a new LP_Views object
    $lp_view = new LPF_Views( $lpf_object, $id );
    //get live preview view and form
    $lp_view->get_lp_form( $template );
    die();
  }
}
