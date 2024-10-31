<?php


class CHCH_LIVE_PREVIEW_FREE {

  /**
   * Live Preview Config array
   * @var   array
   * @since 1.0.0
   */
  protected $live_preview = array();
 

  public $lp_slug = 'lp_';


  /**
   * Live Preview Defaults
   * @var   array
   * @since 1.0.0
   */
  protected $lp_defaults = array(
    'id' => '',
    'title' => '',
    'tpl_dir' => CHCH_LIVE_PREVIEW_FREE_DIR,
    'tpl_url' => CHCH_LIVE_PREVIEW_FREE_URL,
    'target_post_types' => 'post',
    );

  /**
   * Live Preview fields groups
   * @var   array
   * @since 1.0.0
   */
  protected $fields = array();
    
  /**
   * CHCH_LIVE_PREVIEW::__construct()
   * 
   * @return
   */
  public function __construct( array $live_preview ) {

    if ( empty( $live_preview['id'] ) ) {
      wp_die( __( 'Live Preview Instance must have id!', 'lpf' ) );
    }

    $this->live_preview = $live_preview;
    $this->lp_id = $live_preview['id']; 

    LPF_Objects::add( $this );

    //add comments
    do_action( "chch_lpf_init_{$this->lp_id}", $this );
  }
   

  /**
   * CHCH_LIVE_PREVIEW::add_field_section()
   * 
   * @return
   */
  public function add_field_section( array $section ) {
    if ( !is_array( $section ) ) {
      return;
    }
    $id =  $section['id'] ? $section['id'] : 'field_id';
    $this->fields['fields_sections'][$id] = $section;
  }
  
  /**
   * CHCH_LIVE_PREVIEW::add_field_section()
   * 
   * @return
   */
  public function add_fields_group( $section_id = '', array $group ) {
    if ( empty($section_id) || !is_array( $group ) || !isset($group['id'])) {
      return;
    }
    
    if(isset($this->fields['fields_sections'][$section_id]) && (!isset($this->fields['fields_sections'][$section_id]['fields_groups'][$group['id']]) || $group['id'] == 'disabled')) {
      $this->fields['fields_sections'][$section_id]['fields_groups'][$group['id']] = $group;   
    }
    
  }
  
  /**
   * CHCH_LIVE_PREVIEW::add_field_section()
   * 
   * @return
   */
  public function add_fields( $section_id = '', $group_id = '', array $fields ) {
    if ( empty($section_id) || empty($group_id) || !is_array( $fields ) ) {
      return;
    }
    
    if(isset($this->fields['fields_sections'][$section_id]['fields_groups'][$group_id])) {
      $this->fields['fields_sections'][$section_id]['fields_groups'][$group_id]['fields'] = $fields;   
    }
    
  }

  /**
   * CHCH_LIVE_PREVIEW::get_field_sections()
   * 
   * @return
   */
  public function get_all_field_sections() {
    return $this->fields['fields_sections'];
  }
  
  /**
   * CHCH_LIVE_PREVIEW::get_field_sections()
   * 
   * @return
   */
  public function get_field_section($section_id) {
    
    $section = array();
    if(isset($this->fields[$section_id])) {
      $section = $this->fields['fields_sections'][$section_id];   
    }
    
    return $section;
  }
  
  /**
   * CHCH_LIVE_PREVIEW::get_field_sections()
   * 
   * @return
   */
  public function get_section_fields_group($section_id) {
    
    $fields = array();
    if(isset($this->fields['fields_sections'][$section_id]) && isset($this->fields['fields_sections'][$section_id]['fields_groups'])) {
      $fields = $this->fields['fields_sections'][$section_id]['fields_groups'];   
    }
    
    return $fields;
  }
  
  /**
   * CHCH_LIVE_PREVIEW::get_field_sections()
   * 
   * @return
   */
  public function get_all_fields_group() {
    
    $all_sections = $this->get_all_field_sections();
    $all_fields_group = array();
    foreach ( $all_sections as $section ) { 
      
      foreach($section['fields_groups'] as $id => $group) {
        if(isset($group['disabled']) && $group['disabled'] === true) {
          continue;
        }
        $all_fields_group[$id] = $group['fields'];  
      }
      
    } 
    
    return $all_fields_group;
  }
  
  /**
   * CHCH_LIVE_PREVIEW::get_field_sections()
   * 
   * @return
   */
  public function get_section_fields($section_id) {
    
    $fields = array();
    if(isset($this->fields['fields_sections'][$section_id]) && isset($this->fields['fields_sections'][$section_id]['fields'])) {
      $fields = $this->fields['fields_sections'][$section_id]['fields_groups']['fields'];   
    }
    
    return $fields;
  }

  /**
   * CHCH_LIVE_PREVIEW::get_param()
   * 
   * @return
   */
  public function get_param( $param ) {
    if ( array_key_exists( $param, $this->live_preview ) ) {
      return $this->live_preview[$param];
    }
    
    return '';
  }

}
