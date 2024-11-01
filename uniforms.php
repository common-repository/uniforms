<?php
/*
 Plugin Name: UniForms
 Plugin URI: https://theme-vision.com/plugins/uniforms/
 Author: Theme Vision
 Author URI: https://theme-vision.com
 Description: Extends WordPress with light weight drag & drop contact form builder.
 Version: 1.0.0
 Text Domain: uniforms
 Domain Path: /languages
*/

// No direct access allowed.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * UniForms Plugin Class
 *
 * @since 1.0.0
 */
class UniForms_Plugin {
    
    /**
     * Class Instance
     *
     * @since 1.0.0
     */
    private static $instance = null;
    
    /**
     * Development Mode
     *
     * @since 1.0.0
     */
    private static $development = false;
    
    /**
     * Plugin Version
     *
     * @since 1.0.0
     */
    public static $version = '1.0.0';
    
    /**
     * Class Constructor
     */
    function __construct() {
        
        if( self::$development ) {
            self::$version = esc_attr( uniqid() );
        }
        
        $this->constants();
        
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        $this->load_files();
        
    }
    
    /**
	 * Plugin Textdomain
     *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'uniforms' );
	}
    
    /**
     * Define Constants
     *
     * @since 1.0.0
     */
    function constants() {
        
        define( 'UNIFORMS_DIR', plugin_dir_path( __FILE__ ) );
        define( 'UNIFORMS_URI', plugin_dir_url( __FILE__ ) );
        
    }
    
    /**
     * Load Plugin Files
     *
     * @since 1.0.0
     */
    function load_files() {
        if( is_admin() ) {
            
            require_once UNIFORMS_DIR . 'includes/admin/class-uniforms-admin.php';
            require_once UNIFORMS_DIR . 'includes/admin/class-uniforms-post-type.php';
            require_once UNIFORMS_DIR . 'includes/admin/class-uniforms-meta-box.php';
            
        } else {
            
            require_once UNIFORMS_DIR . 'includes/class-uniforms.php';
            require_once UNIFORMS_DIR . 'includes/class-uniforms-shortcode.php';
            
        }
    }
    
    /**
     * Enqueue Backend Scripts
     *
     * @since 1.0.0
     */
    function admin_enqueue_scripts() {
        $screen = get_current_screen();
        if( $screen->post_type == 'uniforms' ) {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'uniforms-backend', UNIFORMS_URI . 'assets/js/uniforms-backend.js', array('jquery'), self::$version );
            wp_enqueue_script( 'uniforms-builder', UNIFORMS_URI . 'assets/js/form-builder.min.js', array('jquery'), self::$version );
            
            wp_enqueue_style( 'uniforms-admin', UNIFORMS_URI . 'assets/css/uniforms-backend.css', array(), self::$version );
        }
    }
    
    /**
     * Enqueue Frontend Scripts
     *
     * @since 1.0.0
     */
    function enqueue_scripts() {
        wp_enqueue_style( 'unifroms-frontend', UNIFORMS_URI . 'assets/css/uniforms-frontend.css', array(), self::$version );
    }
    
    /**
     * Creates or returns an instance of this class.
     *
     * @since 1.0.0
     */
    public static function get_instance() {

        if( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
}
add_action( 'plugins_loaded', array( 'UniForms_Plugin', 'get_instance' ) );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
