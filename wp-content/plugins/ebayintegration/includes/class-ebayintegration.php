<?php
/**
 * Main plugin class file.
 *
 * @package Ebay Integration/Includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Ebay_Integration {

	/**
	 * The single instance of Ebay_Integration.
	 *
	 * @var     object
	 * @access  private
	 * @since   1.0.0
	 */
	private static $_instance = null; //phpcs:ignore

	/**
	 * Local instance of Ebay_Integration_Admin_API
	 *
	 * @var Ebay_Integration_Admin_API|null
	 */
	public $admin = null;

	/**
	 * Settings class object
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version; //phpcs:ignore

	/**
	 * The token.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token; //phpcs:ignore

	/**
	 * The main plugin file.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for JavaScripts.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor funtion.
	 *
	 * @param string $file File constructor.
	 * @param string $version Plugin version.
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token   = 'Ebay_Integration';

		// Load plugin environment variables.
		$this->file       = $file;
		$this->dir        = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions.
		if ( is_admin() ) {
			$this->admin = new Ebay_Integration_Admin_API();
		}

		// Handle localisation.
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );


        add_action("rest_api_init", array($this, 'add_api_endpoint'));



	} // End __construct ()

    public function add_api_endpoint(  ){

		register_rest_route( '/ebayintegration/v1', '/request', array(
			'methods' => 'GET',
			'callback' => array( $this, 'handle_api_endpoint' )
		) );        

    }

    public function handle_api_endpoint($data){

		$access_token = "v^1.1#i^1#f^0#I^3#p^3#r^0#t^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";		
        $apiURL = "https://api.ebay.com/ws/api.dll";
        $per_page = 100;
        $page_number = 1;

		if(isset($_GET["page_number"])){
			$page_number = $_GET["page_number"];
		}
        
        $post_data = 
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
        '<RequesterCredentials>' .
          '<eBayAuthToken>' . $access_token . '</eBayAuthToken>' .
            '</RequesterCredentials>' .
          '<ErrorLanguage>en_US</ErrorLanguage>' .
            '<WarningLevel>High</WarningLevel>' .
            '<ActiveList>' .
          '<Sort>TimeLeft</Sort>' .
            '<Pagination>' .
            '<EntriesPerPage>' . $per_page . '</EntriesPerPage>' .
              '<PageNumber>' . $page_number . '</PageNumber>' .
              '</Pagination>' .
            '</ActiveList>' .
        '</GetMyeBaySellingRequest> ';

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $apiURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$post_data,
                CURLOPT_HTTPHEADER => [
                    'X-EBAY-API-SITEID:0',
                    'X-EBAY-API-COMPATIBILITY-LEVEL:967',
                    'X-EBAY-API-CALL-NAME:GetMyeBaySelling',
                ]
            ]
        );

        $response = curl_exec($curl);
        $status = curl_getinfo($curl);

        curl_close($curl);

        $xml=simplexml_load_string($response) or die("Error: Cannot create object");
        $json = json_decode(json_encode($xml), true);
        
		if($json["Ack"] != "Success"){
			return "API Failed";
		}
		
		return $json;

    }


	/**
	 * Register post type function.
	 *
	 * @param string $post_type Post Type.
	 * @param string $plural Plural Label.
	 * @param string $single Single Label.
	 * @param string $description Description.
	 * @param array  $options Options array.
	 *
	 * @return bool|string|Ebay_Integration_Post_Type
	 */
	public function register_post_type( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

		if ( ! $post_type || ! $plural || ! $single ) {
			return false;
		}

		$post_type = new Ebay_Integration_Post_Type( $post_type, $plural, $single, $description, $options );

		return $post_type;
	}

	/**
	 * Wrapper function to register a new taxonomy.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @param string $plural Plural Label.
	 * @param string $single Single Label.
	 * @param array  $post_types Post types to register this taxonomy for.
	 * @param array  $taxonomy_args Taxonomy arguments.
	 *
	 * @return bool|string|Ebay_Integration_Taxonomy
	 */
	public function register_taxonomy( $taxonomy = '', $plural = '', $single = '', $post_types = array(), $taxonomy_args = array() ) {

		if ( ! $taxonomy || ! $plural || ! $single ) {
			return false;
		}

		$taxonomy = new Ebay_Integration_Taxonomy( $taxonomy, $plural, $single, $post_types, $taxonomy_args );

		return $taxonomy;
	}

	/**
	 * Load frontend CSS.
	 *
	 * @access  public
	 * @return void
	 * @since   1.0.0
	 */
	public function enqueue_styles() {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version, true );
		wp_enqueue_script( $this->_token . '-frontend' );
	} // End enqueue_scripts ()

	/**
	 * Admin enqueue style.
	 *
	 * @param string $hook Hook parameter.
	 *
	 * @return void
	 */
	public function admin_enqueue_styles( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 *
	 * @access  public
	 *
	 * @param string $hook Hook parameter.
	 *
	 * @return  void
	 * @since   1.0.0
	 */
	public function admin_enqueue_scripts( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version, true );
		wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'ebayintegration', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = 'ebayintegration';

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Ebay_Integration Instance
	 *
	 * Ensures only one instance of Ebay_Integration is loaded or can be loaded.
	 *
	 * @param string $file File instance.
	 * @param string $version Version parameter.
	 *
	 * @return Object Ebay_Integration instance
	 * @see Ebay_Integration()
	 * @since 1.0.0
	 * @static
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cloning of Ebay_Integration is forbidden' ) ), esc_attr( $this->_version ) );

	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Unserializing instances of Ebay_Integration is forbidden' ) ), esc_attr( $this->_version ) );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function install() {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	private function _log_version_number() { //phpcs:ignore
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}