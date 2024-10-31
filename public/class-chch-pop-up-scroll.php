<?php

/**
 * Pop-Up CC - Scroll
 *
 * @package   ChChPopUpScroll
 * @author    Chop-Chop.org <shop@chop-chop.org>
 * @license   GPL-2.0+
 * @link      https://shop.chop-chop.org
 * @copyright 2014
 */

if ( file_exists( CHCH_PUSF_PLUGIN_DIR . 'includes/chch-live-preview-free/chch-live-preview-free-init.php' ) ) {
	require_once( CHCH_PUSF_PLUGIN_DIR . 'includes/chch-live-preview-free/chch-live-preview-free-init.php' );
}

/**
 * @package ChChPopUpScroll
 * @author  Chop-Chop.org <shop@chop-chop.org>
 */
class ChChPopUpScroll {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '2.0.2';

	/**
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'chch-pusf';

	/**
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	private $pop_ups = array();

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Get all active Pop-Ups
		$this->pop_ups = $this->chch_pusf_get_pop_ups();

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array(
			$this,
			'chch_pusf_activate_new_site',
		) );

		// add live preview and register all fields
		add_action( 'chch_lpf_init', array(
			$this,
			'chch_pusf_live_preview',
		) );

		// Include fonts on front-end
		add_action( 'wp_head', array(
			$this,
			'chch_pusf_hook_fonts',
		) );

		// Include public fancing styles and scripts
		add_action( 'wp_enqueue_scripts', array(
			$this,
			'chch_pusf_template_scripts',
		) );

		// Display active Pop-Ups on front-end
		add_action( 'wp_footer', array(
			$this,
			'chch_pusf_show_popup',
		) );

		// Register ajax subscribe
		add_action( 'wp_ajax_chch_pusf_newsletter_subscribe', array(
			$this,
			'chch_pusf_ajax_newsletter_subscribe',
		) );
		add_action( 'wp_ajax_nopriv_chch_pusf_newsletter_subscribe', array(
			$this,
			'chch_pusf_ajax_newsletter_subscribe',
		) );

	}

	/**
	 * Add live preview and register all fields
	 *
	 */
	function chch_pusf_live_preview() {

		//Templates dir and url
		$tpl_dir = CHCH_PUSF_PLUGIN_DIR . 'public/templates/m-2/';
		$tpl_url = CHCH_PUSF_PLUGIN_URL . 'public/templates/m-2/';

		$lp_config = array(
			'id'                       => 'pusf_templates',
			'title'                    => 'POP-UP SCROLL FREE',
			'tpl_dir'                  => $tpl_dir,
			'tpl_url'                  => $tpl_url,
			'target_post_types'        => 'chch-pusf',
			'disabled_section_content' => '<a href="http://ch-ch.org/puspro" target="_blank">AVAILABLE IN PRO</a>',
		);

		$live_preview = new CHCH_LIVE_PREVIEW_FREE( $lp_config );

		/** ---------------------------------------------------
		 *
		 * SECTION: GENERAL
		 * GROUP: SIZE
		 *
		 * -----------------------------------------------------*/

		$live_preview->add_field_section( array(
			'name' => 'General',
			'id'   => 'general',
		) );

		$live_preview->add_fields_group( 'general', array(
			'id'    => 'size',
			'title' => 'Size',
		) );

		$size_fields = array(
			array(
				'type'    => 'class_switcher',
				'name'    => 'size',
				'target'  => '.pop-up-cc',
				'desc'    => 'Pop-Up Size:',
				'options' => array(
					'chch-scroll-small' => 'Small',
					'chch-scroll-big'   => 'Big',
				),
			),
		);

		$live_preview->add_fields( 'general', 'size', $size_fields );

		/** ---------------------------------------------------
		 *
		 * SECTION: GENERAL
		 * GROUP: OVERLAY
		 *
		 * -----------------------------------------------------*/

		$live_preview->add_fields_group( 'general', array(
			'id'       => 'overlay',
			'title'    => 'Overlay',
			'disabled' => true,
		) );

		$overlay_fields = array(
			array(
				'type' => 'checkbox',
				'desc' => 'Hide Overlay:',
			),
			array(
				'type' => 'color_picker',
				'name' => 'color',
				'desc' => 'Overlay Color:',
			),
			array(
				'type' => 'slider',
				'name' => 'opacity',
				'min'  => '0',
				'max'  => '1.0',
				'step' => '0.1',
				'desc' => 'Overlay Opacity:',
			),
		);

		$live_preview->add_fields( 'general', 'overlay', $overlay_fields );

		/** ---------------------------------------------------
		 *
		 * SECTION: CONTENT
		 * GROUP: CONTENTS
		 *
		 * -----------------------------------------------------*/

		$live_preview->add_field_section( array(
			'name' => 'Content',
			'id'   => 'content',
		) );

		$live_preview->add_fields_group( 'content', array(
			'id'    => 'contents',
			'title' => 'Content',
		) );

		$content_fields = array(
			array(
				'type'   => 'editor',
				'name'   => 'header',
				'target' => '.cc-pu-header-section',
				'desc'   => 'Header:',
				'html'   => 'yes',
			),
			array(
				'type'   => 'editor',
				'name'   => 'subheader',
				'target' => '.cc-pu-subheader-section',
				'html'   => 'yes',
				'desc'   => 'Subheader:',
			),
			array(
				'type'   => 'editor',
				'name'   => 'content',
				'target' => '.cc-pu-content-section',
				'html'   => 'yes',
				'desc'   => 'Content:',
			),
			array(
				'type'   => 'editor',
				'name'   => 'privacy_message',
				'target' => '.cc-pu-privacy-section',
				'desc'   => 'Privacy Message:',
				'html'   => 'yes',
			),
			array(
				'type'   => 'attr',
				'name'   => 'privacy_link',
				'target' => '.cc-pu-privacy-info a',
				'attr'   => 'href',
				'desc'   => 'Privacy Policy Link (if there is no URL provided, the link will not be displayed):',
			),
			array(
				'type'   => 'text',
				'name'   => 'privacy_link_label',
				'target' => '.cc-pu-privacy-info a',
				'desc'   => 'Privacy Policy Link Label:',
			),
			array(
				'type'   => 'text',
				'name'   => 'thank_you',
				'target' => '.cc-pu-thank-you p',
				'desc'   => 'Success Message:',
			),
			array(
				'type'   => 'text',
				'name'   => 'already_subscribe',
				'target' => '.cc-pu-error-message p',
				'desc'   => 'Error Message:',
			),
		);
		$live_preview->add_fields( 'content', 'contents', $content_fields );

		$live_preview->add_fields_group( 'content', array(
			'id'    => 'button',
			'title' => 'Sumbit Button',
		) );

		$button_fields = array(
			array(
				'type'   => 'text',
				'name'   => 'text',
				'target' => '.cc-pu-newsletter-form button',
				'desc'   => 'Button Text:',
			),
		);
		$live_preview->add_fields( 'content', 'button', $button_fields );

		/** ---------------------------------------------------
		 *
		 * SECTION: Background
		 * GROUP: Background
		 *
		 * -----------------------------------------------------*/
		$live_preview->add_field_section( array(
			'name' => 'Background',
			'id'   => 'background',
		) );

		$live_preview->add_fields_group( 'background', array(
			'id'    => 'background',
			'title' => 'Background',
		) );

		$background_fields = array(
			array(
				'type'   => 'color_picker',
				'name'   => 'color',
				'target' => '.modal-inner',
				'attr'   => 'background-color',
				'desc'   => 'Background Color:',
			),
			array(
				'type'     => 'revealer_group',
				'name'     => 'type',
				'desc'     => 'Background Type:',
				'target'   => '.modal-inner',
				'add_css'  => array(
					array(
						'attr'  => 'background-image',
						'value' => '',
					),
				),
				'options'  => array(
					'no'      => 'No Image',
					'image'   => 'Image',
					'pattern' => 'Pattern',
				),
				'revaeals' => array(
					array(
						'section_title' => 'Background Image',
						'section_id'    => 'image',
						'fields'        => array(
							array(
								'type'    => 'upload',
								'name'    => 'image',
								'target'  => '.modal-inner',
								'attr'    => 'background-image',
								'add_css' => array(
									array(
										'attr'  => 'background-size',
										'value' => 'cover',
									),
								),
								'desc'    => 'Enter a URL or upload an image:',
							),
						),
					),
					array(
						'section_title' => 'Background Pattern',
						'section_id'    => 'pattern',
						'reset'         => 'background-size',
						'fields'        => array(
							array(
								'type'    => 'upload',
								'name'    => 'pattern',
								'add_css' => array(
									array(
										'attr'  => 'background-size',
										'value' => 'auto',
									),
								),
								'target'  => '.modal-inner',
								'attr'    => 'background-image',
								'desc'    => 'Enter a URL or upload an image:',
							),
							array(
								'type'    => 'select',
								'name'    => 'repeat',
								'target'  => '.modal-inner',
								'attr'    => 'background-repeat',
								'desc'    => 'Pattern Repeat:',
								'options' => array(
									'repeat'    => 'Repeat',
									'repeat-x'  => 'Repeat-x',
									'repeat-y'  => 'Repeat-y',
									'no-repeat' => 'No Repeat',
								),
							),
						),
					),
				),
			),
		);
		$live_preview->add_fields( 'background', 'background', $background_fields );

		/** ---------------------------------------------------
		 *
		 * SECTION: Border
		 * GROUP: Border
		 *
		 * -----------------------------------------------------*/
		$live_preview->add_field_section( array(
			'name' => 'Borders',
			'id'   => 'border',
		) );
		$live_preview->add_fields_group( 'border', array(
			'id'       => 'border',
			'title'    => 'Borders',
			'disabled' => true,
		) );

		$border_fields = array(
			array(
				'type' => 'slider',
				'name' => 'radius',
				'desc' => 'Border Radius:',
			),
			array(
				'type' => 'slider',
				'name' => 'border_width',
				'desc' => 'Width:',
			),
			array(
				'type'    => 'select',
				'name'    => 'style',
				'desc'    => 'Border Style:',
				'options' => array(
					'solid'  => 'Solid',
					'dashed' => 'Dashed',
					'dotted' => 'Dotted',
				),
			),
			array(
				'type' => 'color_picker',
				'name' => 'color',
				'desc' => 'Color:',
			),
		);
		$live_preview->add_fields( 'border', 'border', $border_fields );

		/** ---------------------------------------------------
		 *
		 * SECTION: Fonts
		 * GROUP: Fonts
		 *
		 * -----------------------------------------------------*/
		$live_preview->add_field_section( array(
			'name' => 'Fonts and Colors',
			'id'   => 'fonts',
		) );
		$live_preview->add_fields_group( 'fonts', array(
			'id'       => 'header',
			'title'    => 'Header',
			'disabled' => true,
		) );

		$headers_fields = array(
			array(
				'type'    => 'select',
				'name'    => 'font',
				'desc'    => 'Header Font:',
				'options' => array( 'Open Sans' => 'Open Sans', ),
			),
			array(
				'type' => 'color_picker',
				'name' => 'color',
				'desc' => 'Color:',
			),
		);
		$live_preview->add_fields( 'fonts', 'header', $headers_fields );

		$live_preview->add_fields_group( 'fonts', array(
			'id'       => 'subheader',
			'title'    => 'Subheader',
			'disabled' => true,
		) );

		$subheaders_fields = array(
			array(
				'type'    => 'select',
				'name'    => 'font',
				'desc'    => 'Header Font:',
				'options' => array( 'Open Sans' => 'Open Sans', ),
			),
			array(
				'type' => 'color_picker',
				'name' => 'color',
				'desc' => 'Color:',
			),
		);
		$live_preview->add_fields( 'fonts', 'subheader', $subheaders_fields );

		$live_preview->add_fields_group( 'fonts', array(
			'id'       => 'content',
			'title'    => 'Content',
			'disabled' => true,
		) );

		$content_fields = array(
			array(
				'type'    => 'select',
				'name'    => 'font',
				'desc'    => 'Header Font:',
				'options' => array( 'Open Sans' => 'Open Sans', ),
			),
			array(
				'type' => 'color_picker',
				'name' => 'color',
				'desc' => 'Color:',
			),
		);
		$live_preview->add_fields( 'fonts', 'content', $content_fields );

		/** ---------------------------------------------------
		 *
		 * SECTION: Fonts
		 * GROUP: Fonts
		 *
		 * -----------------------------------------------------*/
		$live_preview->add_field_section( array(
			'name' => 'Inputs',
			'id'   => 'Inputs',
		) );
		$live_preview->add_fields_group( 'Inputs', array(
			'id'       => 'Inputs',
			'title'    => 'Inputs',
			'disabled' => true,
		) );

		$inputs_fields = array(
			array(
				'type'    => 'select',
				'name'    => 'grid',
				'desc'    => 'Grid:',
				'options' => array(
					'single' => 'Single',
				),
			),
			array(
				'type' => 'slider',
				'name' => 'input_radius',
				'desc' => 'Border Radius:',
			),
			array(
				'type'    => 'select',
				'name'    => 'font',
				'desc'    => 'Header Font:',
				'options' => array( 'Open Sans' => 'Open Sans', ),
			),
			array(
				'type' => 'color_picker',
				'name' => 'color',
				'desc' => 'Color:',
			),
		);
		$live_preview->add_fields( 'Inputs', 'Inputs', $inputs_fields );

		/** ---------------------------------------------------
		 *
		 * SECTION: buttons
		 * GROUP: buttons
		 *
		 * -----------------------------------------------------*/
		$live_preview->add_field_section( array(
			'name' => 'Buttons',
			'id'   => 'buttons',
		) );
		$live_preview->add_fields_group( 'buttons', array(
			'id'       => 'buttons',
			'title'    => 'Submit Button',
			'disabled' => true,
		) );

		$buttons_fields = array(
			array(
				'type' => 'slider',
				'name' => 'button_radius',
				'desc' => 'Border Radius:',
			),
			array(
				'type'    => 'select',
				'name'    => 'font',
				'desc'    => 'Font:',
				'options' => array( 'Open Sans' => 'Open Sans', ),
			),
			array(
				'type' => 'color_picker',
				'name' => 'color',
				'desc' => 'Color:',
			),
			array(
				'type' => 'color_picker',
				'name' => 'background',
				'desc' => 'Color:',
			),
		);
		$live_preview->add_fields( 'buttons', 'buttons', $buttons_fields );

		/** ---------------------------------------------------
		 *
		 * SECTION: link
		 * GROUP: link
		 *
		 * -----------------------------------------------------*/
		$live_preview->add_field_section( array(
			'name' => 'Link',
			'id'   => 'link',
		) );
		$live_preview->add_fields_group( 'link', array(
			'id'       => 'link',
			'title'    => 'Link',
			'disabled' => true,
		) );

		$link_fields = array(
			array(
				'type'    => 'select',
				'name'    => 'font',
				'desc'    => 'Font:',
				'options' => array( 'Open Sans' => 'Open Sans', ),
			),
			array(
				'type' => 'color_picker',
				'name' => 'color',
				'desc' => 'Color:',
			),
		);
		$live_preview->add_fields( 'link', 'link', $link_fields );

		/** ---------------------------------------------------
		 *
		 * SECTION: close_button
		 * GROUP: close_button
		 *
		 * -----------------------------------------------------*/
		$live_preview->add_field_section( array(
			'name' => 'Close Button',
			'id'   => 'close_button',
		) );
		$live_preview->add_fields_group( 'close_button', array(
			'id'       => 'close_button',
			'title'    => 'Close Button',
			'disabled' => true,
		) );

		$close_button_fields = array(
			array(
				'type'    => 'select',
				'name'    => 'font',
				'desc'    => 'Font:',
				'options' => array( 'Open Sans' => 'Open Sans', ),
			),
			array(
				'type' => 'color_picker',
				'name' => 'color',
				'desc' => 'Color:',
			),
		);
		$live_preview->add_fields( 'close_button', 'close_button', $close_button_fields );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide       True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();

					restore_current_blog();
				}

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide       True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

					restore_current_blog();

				}

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int $blog_id ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    0.1.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {

	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {

	}

	/**
	 * Get All Active Pop-Ups IDs
	 *
	 * @since  1.0.0
	 *
	 * @return   array - Active Pop-Ups ids
	 */
	private function chch_pusf_get_pop_ups() {
		$list = array();

		$args = array(
			'post_type'      => 'chch-pusf',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'   => '_chch_pusf_status',
					'value' => 'yes',
				),
			),
		);

		$pop_ups = get_posts( $args );

		if ( $pop_ups ) {
			$is_update = get_option('chch-pusf-update');

			foreach ( $pop_ups as $pop_up ) {
				if ($old_template = get_post_meta($pop_up->ID, '_chch_pusf_template', true)) {
					update_post_meta($pop_up->ID, '_chch_lpf_template', $old_template);
					delete_post_meta($pop_up->ID, '_chch_pusf_template');
				}

				if($is_update !== 'yes') {
					$this->upgrade_content_options($pop_up->ID);
				}


				$list[] = $pop_up->ID;
			}

			update_option('chch-pusf-update','yes');
		}

		return $list;
	}

	private function upgrade_content_options($id) {
		$template_id = get_post_meta($id, '_chch_lpf_template', true) ? get_post_meta($id, '_chch_lpf_template', true) :
			get_post_meta($id, '_chch_pusf_template', true);

		$popup_data = get_post_meta($id, '_' . $template_id . '_template_data', true);

		if($popup_data){
			if(isset($popup_data['contents']['header'])) {
				if(substr($popup_data['contents']['header'], 0, 2) !== '<h'){
					$popup_data['contents']['header'] = '<h2 style="text-align: center">' . $popup_data['contents']['header'] . '</h2>';
				}
			}

			if(isset($popup_data['contents']['subheader'])) {
				if(substr($popup_data['contents']['subheader'], 0, 2) !== '<h') {
					$popup_data['contents']['subheader'] = '<h3 style="text-align: center">' . $popup_data['contents']['subheader'] . '</h3>';
				}
			}

			update_post_meta($id, '_' . $template_id . '_template_data', $popup_data);
		}

	}

	/**
	 * Include fonts on front-end
	 *
	 * @since  0.1.0
	 */
	function chch_pusf_hook_fonts() {

		$output = "<link href='//fonts.googleapis.com/css?family=Playfair+Display:400,700,900|Lora:400,700|Open+Sans:400,300,700|Oswald:700,300|Roboto:400,700,300|Signika:400,700,300' rel='stylesheet' type='text/css'>";

		echo $output;
	}

	/**
	 * Include Templates scripts on Front-End
	 *
	 * @since  1.0.0
	 *
	 * @return   array - Pop-Ups ids
	 */
	function chch_pusf_template_scripts() {

		$pop_ups = $this->pop_ups;

		if ( file_exists( CHCH_PUSF_PLUGIN_DIR . 'public/templates/css/defaults.css' ) ) {
			wp_enqueue_style( $this->plugin_slug . '_template_defaults', CHCH_PUSF_PLUGIN_URL . 'public/templates/css/defaults.css', null, ChChPopUpScroll::VERSION, 'all' );
		}

		if ( file_exists( CHCH_PUSF_PLUGIN_DIR . 'public/templates/css/fonts.css' ) ) {
			wp_enqueue_style( $this->plugin_slug . '_template_fonts', CHCH_PUSF_PLUGIN_URL . 'public/templates/css/fonts.css', null, ChChPopUpScroll::VERSION, 'all' );
		}


		if ( file_exists( CHCH_PUSF_PLUGIN_DIR . 'public/assets/js/jquery-cookie/jquery.cookie.js' ) ) {
			wp_enqueue_script( $this->plugin_slug . 'jquery-cookie', CHCH_PUSF_PLUGIN_URL . 'public/assets/js/jquery-cookie/jquery.cookie.js', array( 'jquery' ), ChChPopUpScroll::VERSION );

		}

		if ( file_exists( CHCH_PUSF_PLUGIN_DIR . 'public/assets/js/public.js' ) ) {
			wp_enqueue_script( $this->plugin_slug . 'public-script', CHCH_PUSF_PLUGIN_URL . 'public/assets/js/public.js', array( 'jquery' ), ChChPopUpScroll::VERSION );
			wp_localize_script( $this->plugin_slug . 'public-script', 'chch_pusf_ajax_object', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) ) );
		}

		foreach ( $pop_ups as $id ) {
			$template_id = get_post_meta( $id, '_chch_lpf_template', true );
			if ( file_exists( CHCH_PUSF_PLUGIN_DIR . 'public/templates/m-2/' . $template_id . '/css/style.css' ) ) {
				wp_enqueue_style( $this->plugin_slug . '_style_' . $template_id, CHCH_PUSF_PLUGIN_URL . 'public/templates/m-2/' . $template_id . '/css/style.css', null, ChChPopUpScroll::VERSION, 'all' );

			}
		}
	}


	/**
	 * Display Pop-Up on Front-End
	 *
	 * @since  1.0.0
	 */
	public function chch_pusf_show_popup() {

		$pop_ups = $this->pop_ups;

		if ( !empty( $pop_ups ) ) {
			foreach ( $pop_ups as $id ) {

				$user_role = get_post_meta( $id, '_chch_pusf_role', true );
				$user_login = is_user_logged_in();

				if ( $user_role == 'logged' && !$user_login ) {
					continue;
				}

				if ( $user_role == 'unlogged' && $user_login ) {
					continue;
				}

				$pages = get_post_meta( $id, '_chch_pusf_page', true );
				if ( is_array( $pages ) ) {
					if ( is_home() ) {
						if ( in_array( 'chch_home', $pages ) ) {
							continue;
						} else {
							$array_key = array_search( get_the_ID(), $pages );
							if ( $array_key ) {
								unset( $pages[ $array_key ] );
							}
						}
					}

					if ( in_array( 'chch_woocommerce_shop', $pages ) ) {
						if ( function_exists( 'is_shop' ) ) {
							if ( is_shop() ) {
								continue;
							}
						}
					}

					if ( in_array( 'chch_woocommerce_category', $pages ) ) {
						if ( function_exists( 'is_product_category' ) ) {
							if ( is_product_category() ) {
								continue;
							}
						}
					}

					if ( in_array( 'chch_woocommerce_products', $pages ) ) {
						if ( function_exists( 'is_product' ) ) {
							if ( is_product() ) {
								continue;
							}
						}
					}

					if ( in_array( get_the_ID(), $pages ) ) {
						continue;
					}
				}


				$template_id = get_post_meta( $id, '_chch_lpf_template', true );
				echo '<div style="display:none;" id="modal-' . $id . '" class="pro-scroll ' . $template_id . '">';

				$lp_object = LPF_Objects::get_lp_object( 'pusf_templates' );


				if ( $lp_object instanceof CHCH_LIVE_PREVIEW_FREE ) {
					$template = new LPF_Templates( $lp_object, $template_id, $id );
					$template->build_css( '#modal-' . $id );
					$template->get_template();
					$this->build_js( $id );
				}
				echo '</div>';
			}
		}
	}

	/**
	 * Build js from post_meta data.
	 *
	 * @param int $id - post id
	 */
	function build_js( $id ) {

		$mobile_header = 'if($(window).width() > 1024){';
		$mobile_footer = '}';

		if ( get_post_meta( $id, '_chch_pusf_show_on_mobile', true ) ) {
			$mobile_header = '';
			$mobile_footer = '';
		}

		if ( get_post_meta( $id, '_chch_pusf_show_only_on_mobile', true ) ) {
			$mobile_header = 'if($(window).width() < 1025){';
			$mobile_footer = '}';
		}

		$scroll_type = get_post_meta( $id, '_chch_pusf_scroll_type', true );

		$script = '<script type="text/javascript">';
		$script .= 'jQuery(function($) {';

		$script .= 'if(!Cookies.get("shown_modal_' . $id . '")){ ';

		$script .= $mobile_header;

		$script .= '$(window).on("scroll", function() { var y_scroll_pos = window.pageYOffset;';

		switch ( $scroll_type ):
			case 'px':
				$scroll_px = get_post_meta( $id, '_chch_pusf_scroll_px', true );

				$scroll_head = '
   		 						var scroll_pos_test = ' . $scroll_px . ';
 								if(y_scroll_pos > scroll_pos_test) { ';
				$scroll_footer = '}';
			break;

			case 'percent':
				$scroll_percent = get_post_meta( $id, '_chch_pusf_scroll_percent', true );

				$scroll_head = '
				var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();

    			var  scrolltrigger = ' . ( $scroll_percent / 100 ) . ';

				if  ((wintop/(docheight-winheight)) > scrolltrigger) { ';
				$scroll_footer = '}';
			break;

			case 'item':
				$scroll_item = get_post_meta( $id, '_chch_pusf_scroll_item', true );

				$scroll_head = '
				var scrollEl = $("' . $scroll_item . '");

				if(scrollEl.length) {
					var scrollElOffset = scrollEl.offset().top;
					if (y_scroll_pos > scrollElOffset) { ';
				$scroll_footer = '}}';
			break;
		endswitch;


		$script .= $scroll_head;
		$script .= '$("#modal-' . $id . '").not(".chch_shown").show("fast");
			$("#modal-' . $id . '").addClass("chch_shown");
		if($(window).width() < 768){
			windowPos = $(window).scrollTop();
			windowHeight = $(window).height();
			popupHeight = $( "#modal-' . $id . ' .modal-inner" ).outerHeight();
			popupPosition = windowPos + ((windowHeight - popupHeight)/2);
			$( "#modal-' . $id . ' .pop-up-cc").css("top",Math.abs(popupPosition));
		}';
		$script .= $scroll_footer;
		$script .= '});';
		$script .= $mobile_footer;

		$script .= '}';

		$script .= '});';
		$script .= '</script>';

		echo $script;
	}

	/**
	 * Ajax subscribe function
	 *
	 * @since  1.0.0
	 */
	public function chch_pusf_ajax_newsletter_subscribe() {

		if ( !check_ajax_referer( 'chch-pusf-newsletter-subscribe', 'nounce' ) ) {
			print json_encode( array( 'status' => 'cheating' ) );
			die();
		}

		$fields = array();
		if ( isset( $_POST[ 'fields' ] ) ) {
			$fields = $_POST[ 'fields' ];
			$field_check = $this->check_fields( $_POST[ 'fields' ] );

		}

		if ( !empty( $field_check ) ) {

			$response = array();
			$response[ 'errors' ] = $field_check;
			$response[ 'status' ] = 'fields_error';
			print json_encode( $response );
			die();
		}

		$id = $_POST[ 'popup' ];
		$email_options = get_post_meta( $id, '_Email_data', true );
		$to_email = ( isset( $email_options[ 'email' ] ) && !empty( $email_options[ 'email' ] ) ) ?
			$email_options[ 'email' ] : get_option( 'admin_email' );

		if ( isset( $fields ) && ( isset( $email_options[ 'email_message' ] ) && $email_options[ 'email_message' ] !== '' ) ) {
			$message = $this->build_email_content( $fields, $email_options[ 'email_message' ] );
		} else {
			$message = $this->build_email_content( $fields, sprintf( __( "Hello,\n\nA new user has subscribed through: %s.\n\nSubscriber's email: {email}", $this->plugin_slug ), get_bloginfo( 'url' ) ) );
		}

		$sent = wp_mail( $to_email, __( 'You have a new subscriber!', $this->plugin_slug ), $message );

		if ( $sent ) {
			print json_encode( array( 'status' => 'ok' ) );
		} else {
			print json_encode( array(
				'status'  => 'error',
				'code'    => 'subscribeError',
				'message' => '<strong>Something went wrong!</strong>',
			) );
		}
		die();
	}


	/**
	 * Checks newsletter fields
	 *
	 * @param array $post_array
	 *
	 * @return array
	 */
	private function check_fields( array $post_array ) {
		$req_messages = __( 'This field is mandatory.', $this->plugin_slug );
		$format_messages = __( 'This value is invalid.', $this->plugin_slug );
		$errors = array();
		if ( is_array( $post_array ) ) {
			foreach ( $post_array as $field ) {

				if ( isset( $field[ 'fieldReq' ] ) && $field[ 'fieldReq' ] == 'yes' ) {

					if ( !isset( $field[ 'fieldVal' ] ) || $field[ 'fieldVal' ] == '' ) {
						$errors[] = array(
							'error_type'    => 'field_req',
							'field_name'    => $field[ 'fieldName' ],
							'error_message' => $req_messages,
						);
						continue;
					}

				}
				if ( !$this->validate_field( $field ) ) {

					$errors[] = array(
						'error_type'    => 'format_error',
						'field_name'    => $field[ 'fieldName' ],
						'error_message' => $format_messages,
					);
				}

			}
		}

		return $errors;
	}


	/**
	 * @param $field
	 *
	 * @return bool|mixed
	 */
	private function validate_field( $field ) {
		if ( isset( $field[ 'fieldType' ] ) ) {
			switch ( $field[ 'fieldType' ] ) {
				case 'email':
					return filter_var( $field[ 'fieldVal' ], FILTER_VALIDATE_EMAIL );
				break;

				case 'phone':
					return true;
				break;

				default:
					return true;
				break;
			}
		}

	}


	/**
	 *
	 *
	 * @param array $fields
	 * @param string $message
	 *
	 * @return string $message
	 */
	private function build_email_content( $fields, $message ) {
		if ( is_array( $fields ) ) {
			foreach ( $fields as $field ) {
				if ( isset( $field[ 'fieldVal' ] ) && isset( $field[ 'fieldName' ] ) ) {
					$message = str_replace( "{" . $field[ 'fieldName' ] . "}", $field[ 'fieldVal' ], $message );
				}
			}
		}

		return $message;
	}

	/**
	 * CcPopUp::get_newsletter_form()
	 *
	 * @param mixed $lp_options
	 * @param mixed $post_id
	 * @param mixed $template_obj
	 *
	 */
	function get_newsletter_form( $lp_options, $post_id, LPF_Templates $template_obj ) {

		if ( get_post_meta( $post_id, '_chch_pusf_newsletter', true ) != 'no' ) {
			if ( !is_admin() ) {
				echo "<form action=\"#\" class=\"cc-pu-newsletter-form cc-pusf-newsletter-form \">\n";
			} else {
				echo "<div class=\"cc-pu-newsletter-form \">\n";
			}
			print( "<div class=\"cc-pu-form-group cc-pu-smart-form cc-pu-form-group__full \"> \n" );

			echo "<div class=\"cc-pu-form-inputs\">\n";

			$this->get_newsletter_fields( $post_id );
			echo "</div>";
			$thank_you = $lp_options[ 'contents' ][ 'thank_you' ];
			printf( "\t<div class=\"cc-pu-thank-you\"><p>%s</p></div> \n", $thank_you );
			$main_error = $template_obj->get_template_option( 'contents', 'already_subscribe' );
			printf( "\t<div class=\"cc-pu-main-error\"><p>%s</p></div> \n", $main_error );

			printf( "\t<input type=\"hidden\" name=\"_ajax_nonce\" id=\"_ajax_nonce\" value=\"%s\" data-popup=\"%s\">", wp_create_nonce( "chch-pusf-newsletter-subscribe" ), $post_id );
			$button = $lp_options[ 'button' ];

			$auto_close = get_post_meta( $post_id, '_chch_pusf_auto_closed', true ) ? 'yes' : 'no';
			$auto_close_time = get_post_meta( $post_id, '_chch_pusf_close_timer', true ) ? get_post_meta( $post_id, '_chch_pusf_close_timer', true ) : '0';

			printf( "\t<button type=\"submit\" class=\"cc-pu-btn\" data-auto-close=\"%s\" data-auto-close-time=\"%s\"  ><i class=\"fa fa-spinner fa-spin fa-2x\"></i><span>%s</span></button>", $auto_close, $auto_close_time, $button[ 'text' ] );
			echo "</div>";

			if ( !is_admin() ) {
				echo "</form>";
			} else {
				echo "</div>";
			}

		}
	}

	/**
	 * CcPopUp::get_newsletter_fields()
	 *
	 * @param mixed $post_id
	 *
	 */
	private function get_newsletter_fields( $post_id ) {

		$email_options = get_post_meta( $post_id, '_Email_data', true );
		if ( $email_options ) {
			if ( isset( $email_options[ 'fields' ] ) && is_array( $email_options[ 'fields' ] ) ) {

				foreach ( $email_options[ 'fields' ] as $field ) {
					if ( !isset( $field[ 'name' ] ) || !isset( $field[ 'id' ] ) || !isset( $field[ 'type' ] ) ) {
						continue;
					}
					switch ( $field[ 'type' ] ) {
						case 'email':
							$icon = 'envelope';
						break;

						case 'phone':
							$icon = 'phone';
						break;

						default:
							$icon = 'user';
						break;
					}
					echo "<div class=\"cc-pu-form-control__wrapper\">";
					printf( "<i class=\"fa fa-%s\"></i>\n", $icon );
					printf( "\t<input type=\"text\" class=\"cc-pu-form-control cc-pusf-form-additional\" placeholder=\"%s\" name=\"%s\" data-type=\"%s\" data-req=\"%s\">", $field[ 'name' ], $field[ 'id' ], $field[ 'type' ], isset( $field[ 'req' ] ) ?
						'yes' : 'no' );
					echo "<span class=\"cc-pu-error-message\"></span>\n";
					echo "</div>";
				}
			} else {
				echo "<div class=\"cc-pu-form-control__wrapper\">";
				printf( "<i class=\"fa fa-%s\"></i>\n", 'envelope' );
				echo "\t<input type=\"text\" class=\"cc-pu-form-control cc-pusf-form-additional\" placeholder=\"Your e-mail\" name=\"email\" data-type=\"email\" data-req=\"yes\">";
				echo "<span class=\"cc-pu-error-message\"></span>\n";
				echo "</div>";
			}
		} else {
			echo "<div class=\"cc-pu-form-control__wrapper\">";
			printf( "<i class=\"fa fa-%s\"></i>\n", 'envelope' );
			echo "\t<input type=\"text\" class=\"cc-pu-form-control cc-pusf-form-additional\" placeholder=\"Your e-mail\" name=\"email\" data-type=\"email\" data-req=\"yes\">";
			echo "<span class=\"cc-pu-error-message\"></span>\n";
			echo "</div>";
		}

	}

	/**
	 * CcPopUp::get_close_button()
	 *
	 * @param mixed $id
	 *
	 */
	function get_close_button( $id ) {
		$views_control = get_post_meta( $id, '_chch_pusf_show_only_once', true );

		printf( "<a class=\"cc-pu-close cc-pusf-close\" data-modalId=\"%s\" data-views-control=\"yes\" data-expires-control=\"%s\">  <i class=\"fa fa-times\"></i> </a> ", $id, $views_control );

	}
}
