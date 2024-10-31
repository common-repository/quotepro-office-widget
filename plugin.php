<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Quotepro Office Widget
 * Plugin URI:        http://aq3.processmyquote.com/Content/Widget
 * Description:       Plugin to add the ability to locate insurance offices from wordpress
 * Version:           2.0.0
 * Author:            Brian Marquis
 * Author URI:        https://plus.google.com/u/1/104998013926973170131/posts
 * Text Domain:       quotepro-office-widget
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /lang
 *
 * @package   Quotepro_Office_Widget
 * @author    Brian Marquis <brian@quotepro.com>
 * @license   GPL-2.0+
 * @link      http://www.quotepro.com
 * @copyright 2014 Quotepro Inc *
 *
 * Quotepro Office Widget
 *
 * Add the ability to locate insurance offices from your wordpress website
 *
 *
 */
 
class Quotepro_Office_Widget extends WP_Widget {

    protected $widget_slug = 'quotepro-office-widget';

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		// load plugin text domain
		add_action( 'plugins_loaded', array( $this, 'widget_textdomain' ) );

		// Hooks fired when the Widget is activated and deactivated
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// TODO: update description
		parent::__construct(
			'quotepro-office-widget',
			__( 'Quotepro Office Widget', $this->get_widget_slug() ),
			array(
				'classname'  => 'quotepro-office-widget-class',
				'description' => __( 'Accept offices on your website', 'quotepro-office-widget' )
			)
		);

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

		// Refreshing the widget's cached output with each new post
		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

	} // end constructor


    /**
     * Return the widget slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_widget_slug() {
        return $this->widget_slug;
    }

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		
		// Check if there is a cached output
		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset ( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset ( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];
		


		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		$title= $instance['title'];
		$url= $instance['url'];
		$lang= $instance['lang'];

		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );
		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string;

	} // end widget
	
	
	public function flush_widget_cache() 
	{
    	wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
		$instance['url'] = !empty($new_instance['url']) ? esc_url_raw($new_instance['url']) : 'https://aq3.processmyquote.com/PlainAgent';
		$instance['lang'] = !empty($new_instance['lang']) ? strip_tags($new_instance['lang']) : 'en';


		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args(
			(array) $instance
		);

		$title = !empty( $instance['title']) ? $instance['title'] : __( 'Find An Office');
		$titleId = $this->get_field_id( 'title' );
            $titleName = $this->get_field_name( 'title' );

		$url = !empty( $instance['url']) ? $instance['url'] : __( 'https://aq3.processmyquote.com/PlainAgent');
		$urlId = $this->get_field_id( 'url' );
            $urlName = $this->get_field_name( 'url' );

		$lang = !empty( $instance['lang']) ? $instance['lang'] : 'en';
		$langId = $this->get_field_id( 'lang' );
            $langName = $this->get_field_name( 'lang' );

		// Display the admin form
		include( plugin_dir_path(__FILE__) . 'views/admin.php' );

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {

		load_plugin_textdomain( 'quotepro-office-widget', false, plugin_dir_path( __FILE__ ) . 'lang/' );

	} // end widget_textdomain

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function deactivate( $network_wide ) {
		// TODO define deactivation functionality here
	} // end deactivate

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array('jquery') );

	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		wp_enqueue_style( $this->get_widget_slug().'-colorbox-styles', plugins_url( 'css/colorbox.css', __FILE__ ) );
		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );

	} // end register_widget_styles

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {
		wp_register_script( 'jquery.maskedinput', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js', array(), '1.4.1', true );

		wp_enqueue_script( $this->get_widget_slug().'-colorbox-script', plugins_url( 'js/jquery.colorbox-min.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( $this->get_widget_slug().'-script', plugins_url( 'js/widget.js', __FILE__ ), array('jquery') );
		wp_enqueue_script('jquery.maskedinput');

	} // end register_widget_scripts

} // end class

// TODO: Remember to change 'Quotepro_Insurance_Widget' to match the class name definition
add_action( 'widgets_init', create_function( '', 'register_widget("Quotepro_Office_Widget");' ) );