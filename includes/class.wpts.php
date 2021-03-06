<?php
/**
 * WP_Theme_Settings Core (Main Class)
 *
 * @class    WP_Theme_Settings
 * @author   DevDiamond <me@devdiamond.com>
 * @package  WP_Theme_Settings
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load Main WP_Theme_Settings Class
if ( ! class_exists( 'WP_Theme_Settings' ) ) :

/**
 * Class WP_Theme_Settings - Main Class (Core).
 */
final class WP_Theme_Settings
{
	const OPTIONS_PREFIX = 'WPTS_';

	/**
	 * The single instance of the class.
	 *
	 * @var WP_Theme_Settings
	 */
	protected static $_instance = null;

	/**
	 * Main WP_Theme_Settings Instance.
	 *
	 * Ensures only one instance of WP_Theme_Settings is loaded or can be loaded.
	 *
	 * @static
	 * @see WPTS()
	 * @return WP_Theme_Settings - Main instance.
	 */
	public static function instance()
	{
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone(){}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup(){}

	/**
	 * WP_Theme_Settings Constructor.
	 */
	public function __construct()
	{
		$this->includes();
		$this->init_hooks();

		do_action('wpts_loaded');
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks()
	{
		// activation and deactivation hook
		register_activation_hook( WPTS_PLUGIN_FILE, array( $this, 'plugin_activation' ) );
		register_deactivation_hook( WPTS_PLUGIN_FILE, array( $this, 'plugin_deactivation' ) );

		// init action
		add_action('init', array( $this, 'init' ), 0 );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes()
	{
		if ( $this->is_request('admin') )
			require_once('admin/class.wpts-admin.php');
	}

	/**
	 * Check the type of request
	 *
	 * @param  string $type  - admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type )
	{
		switch ( $type )
		{
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
		return false;
	}

	/**
	 * Init WP_Theme_Settings when WordPress Initialises.
	 */
	public function init()
	{
		// Set up localisation.
		$this->load_plugin_textdomain();

		// Init action.
		do_action( 'wpts_init' );
	}

	/**
	 * Load Localisation files.
	 */
	private function load_plugin_textdomain()
	{
		if ( ! is_textdomain_loaded( WPTS_PLUGIN_SLUG ) )
			load_plugin_textdomain( WPTS_PLUGIN_SLUG, false, basename( WPTS_PLUGIN_DIR ) . '/languages' );
	}

	/**
	 * WP_Theme_Settings activation
	 */
	public function plugin_activation()
	{
		return;
	}

	/**
	 * WP_Theme_Settings deactivation
	 */
	public function plugin_deactivation()
	{
		return;
	}

	/**
	 * Get option
	 *
	 * @param  string $option_slug  - Option slug
	 * @param  string $option_name  - Option name
	 * @param  bool   $default      - Default options
	 *
	 * @return mixed
	 */
	public function get_option( $option_slug, $option_name = null, $default = false )
	{
		$option = get_option( self::OPTIONS_PREFIX . $option_slug );

		if ( false === $option )
			return $default;

		if ( $option_name === null )
			return $option;
		elseif ( isset( $option[ $option_name ] ) )
			return $option[ $option_name ];
		else
			return $default;
	}
}

endif;

/**
 * Main instance of WP_Theme_Settings.
 *
 * @return WP_Theme_Settings
 */
function WPTS()
{
	return WP_Theme_Settings::instance();
}
