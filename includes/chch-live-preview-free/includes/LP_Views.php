<?php

if ( !class_exists( 'PluginMetaData' ) )
  require_once ( CHCH_LIVE_PREVIEW_FREE_DIR . 'third-party/PluginMetaData.php' );
/**
 * LPF_Views builds all views in admin panel.
 * 
 * @package CHC LIVE PREVIEW
 * @author 
 * @copyright 2015
 * @version 1.0.0
 * @access public
 */
class LPF_Views {

  /**
   *  
   * @var   string
   * @since 1.0.0
   */
  private $lp = '';

  /**
   * Let's create our LP_Views object
   *  
   * @param string $tpl_dir
   * @return void
   */
  public function __construct( $lp ,$id = '0') {
    $this->lp = $lp;
    $this->id = $id;
  }

  /**
   * Returns hole lp view: form, templates list etc.
   * 
   * @return void
   */
  public function get_lp_view( $echo = 'true' ) {
    $lp_view_dir = CHCH_LIVE_PREVIEW_FREE_DIR . 'views/templates.php';
    $lp_view_dir = apply_filters( 'chch_lp_tpl_view', $lp_view_dir );

    $lp_view = '';
    if ( file_exists( $lp_view_dir ) ) {
      $lp_view = ( include ( $lp_view_dir ) );
    }

    return $lp_view;
  }

  /**
   * Returns hole lp view: form, templates list etc.
   * 
   * @return void
   */
  public function get_lp_form( $template_id ) { 
    $lp_form = new LPF_FORM( $this->lp, $template_id, $this->id );
    $lp_form->get_form();
  }


  /**
   * Return list of templates
   *
   * @since     1.0.0
   *
   * @return    array - template list
   */
  public function get_templates() {

    $pmd = new PluginMetaData;
    $pmd->scan( $this->lp->get_param( 'tpl_dir' ) );
    return $pmd->plugin;
  }


  /**
   * Returns or prints template thumbnail
   * 
   * @param string $template_id - thumbnail will be included from template with this id
   * @param string $echo - Default - false. If set to true thumbnail will be printed immediately, otherwise it will be return as a string
   * 
   * @return string $thumbnail - only if $echo is set to false
   */
  public function get_template_thumbnail( $template_id, $echo = false ) {
    LPF_Templates::get_thumbnail( $template_id, $this->lp->get_param( 'tpl_dir' ), $this->lp->get_param( 'tpl_url' ), $echo );
  }

}
