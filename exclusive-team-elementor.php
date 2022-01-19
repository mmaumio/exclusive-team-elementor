<?php

 /**
 * Plugin Name: Exclusive Team for Elementor
 * Plugin URI: http://devscred.com/exclusive-team/
 * Description: The Only Team Member Element you'll ever need
 * Version: 1.2.4
 * Author: DevsCred.com
 * Author URI: https://devscred.com/
 * Elementor tested up to: 3.5.2
 * Elementor Pro tested up to: 3.5.2
 * Text Domain: exclusive-team-elementor
 * Domain Path: /languages
 * License: GPL3
 */

namespace DevsCred;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Exclusive_Team_Elementor' ) ) {
    /**
     * Exclusive Addons core class.
     *
     * Register plugin and make instances of core classes
     *
     * @package ShopMagic
     * @version 1.0.0
     * @since   1.0.0
     */
    class Exclusive_Team_Elementor {

        /**
         * Holds class instance
         *
         * @access private
         *
         * @var ExclusiveAddons
         */
        private static $instance;

    	/**
         * Default constructor.
         *
         * Initialize plugin core and build environment
         *
         * @since   1.0.0
         */
        public function __construct() {
            $this->define_constants();
            $this->add_actions();

        }

        /**
         * Get class instance
         *
         * @return ExclusiveAddons
         */
        public static function get_instance(){
            if( null === self::$instance ){
                self::$instance = new self();
            }
            return self::$instance;
        }


        /**
         * Throw error on object clone
         *
         * The whole idea of the singleton design pattern is that there is a single
         * object therefore, we don't want the object to be cloned.
         */
        public function __clone()
        {
            // Cloning instances of the class is forbidden
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'exclusive-team-elementor' ), '1.0' );
        }
        
        /**
         * Disable unserializing of the class
         *
         */
        public function __wakeup()
        {
            // Unserializing instances of the class is forbidden
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'exclusive-team-elementor' ), '1.0' );
        }

        /**
         * Definition wrapper.
         *
         * Creates some useful def's in environment to handle
         * plugin paths
         *
         * @since   1.0.0
         */
        public function define_constants() {

            // Some Constants for ease of use
            if ( ! defined( 'EXAD_TEAM_VER' ) )
    			define( 'EXAD_TEAM_VER', '1.2.4' );
    		if ( ! defined( 'EXAD_TEAM_PNAME' ) )
    			define( 'EXAD_TEAM_PNAME', basename( dirname( __FILE__ ) ) );
    		if ( ! defined( 'EXAD_TEAM_PNAME' ) )
    		define( 'EXAD_TEAM_PNAME', plugin_basename(__FILE__) );
    		if ( ! defined( 'EXAD_TEAM_PATH' ) )
    			define( 'EXAD_TEAM_PATH', plugin_dir_path( __FILE__ ) );
            if ( ! defined( 'EXAD_TEAM_ELEMENTS' ) )
                define( 'EXAD_TEAM_ELEMENTS', plugin_dir_path( __FILE__ ) . 'elements/' );
    		if ( ! defined( 'EXAD_TEAM_URL' ) )
    		define( 'EXAD_TEAM_URL', plugins_url( '/', __FILE__ ) );
    		if ( ! defined( 'EXAD_TEAM_ASSETS_URL' ) )
    			define( 'EXAD_TEAM_ASSETS_URL', EXAD_TEAM_URL . 'assets/' );
        }

        /**
         * Adds action hooks.
         *
         * @since   1.0.0
         */
        private function add_actions() {
            // Enqueue Styles and Scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'exad_enqueue_scripts' ) );
            // Registering Elementor Widget Category
            add_action( 'elementor/elements/categories_registered', array( $this, 'exad_team_register_category' ) );
        	// Registering custom widgets
            add_action( 'elementor/widgets/widgets_registered', array( $this, 'exad_add_elements' ) );
            // Initiate Appsero
            $this->exclusive_team_appsero_init();
            // Plugin Loaded Action
            add_action( 'plugins_loaded', array( $this, 'exad_element_pack_load_plugin' ) );
            // Add Body Class 
            add_filter( 'body_class', array( $this, 'exad_add_body_classes' ) );

        }

        /*
        *
        * Add Body Class exclusive-addons-elmentor
        */
        public function exad_add_body_classes( $classes ) {
            $classes[] = 'exclusive-addons-elementor';

            return $classes;
        }

        

        /**
         * Plugin load here correctly
         * Also loaded the language file from here
         */
        public function exad_element_pack_load_plugin() {
            load_plugin_textdomain( 'exclusive-team-elementor', false, basename( dirname( __FILE__ ) ) . '/languages' );

            if ( ! did_action( 'elementor/loaded' ) ) {
                add_action( 'admin_notices', array( $this, 'exad_element_pack_fail_load' ) );
                return;
            }
            
        }

        /**
        * Enqueue Plugin Styles and Scripts
        *
        */
        public function exad_enqueue_scripts() {

            // CSS Load for slick slider
            wp_enqueue_style( 'exad-slick', EXAD_TEAM_URL . 'assets/vendor/css/slick.min.css' );
            wp_enqueue_style( 'exad-slick-theme', EXAD_TEAM_URL . 'assets/vendor/css/slick-theme.min.css' );

            wp_enqueue_style( 'exad-main-style', EXAD_TEAM_URL . 'assets/css/exad-style.min.css' );

            wp_enqueue_script( 'jquery-slick', EXAD_TEAM_URL . 'assets/vendor/js/slick.min.js', array( 'jquery' ), EXAD_TEAM_VER, true );

            wp_enqueue_script( 'exad-main-script', EXAD_TEAM_URL . 'assets/js/exad-script.min.js', array( 'jquery' ), EXAD_TEAM_VER, true );
        }

        /**
        * Register Exclusive Elementor Addons category
        *
        */
        public function exad_team_register_category( $elements_manager ) {

            $elements_manager->add_category(
                'exclusive-team',
                [
                    'title' => __( 'Exclusive Team', 'exclusive-team-elementor' ),
                    'icon' => 'fa fa-plug',
                ]
            );

        }

        /**
         * Check Elementor installed and activated correctly
         */
        function exad_element_pack_fail_load() {
            $screen = get_current_screen();
            if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
                return;
            }

            $plugin = 'elementor/elementor.php';

            if ( $this->_is_elementor_installed() ) {
                if ( ! current_user_can( 'activate_plugins' ) ) { return; }
                $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
                $admin_message = '<p>' . esc_html__( 'Opps! Exclusive Addons requires Elementor Plugin to be activated first.', 'exclusive-team-elementor' ) . '</p>';
                $admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Elementor Now', 'exclusive-team-elementor' ) ) . '</p>';
            } else {
                if ( ! current_user_can( 'install_plugins' ) ) { return; }
                $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
                $admin_message = '<p>' . esc_html__( 'Opps! Exclusive Addons not working because you need to install the Elementor plugin', 'exclusive-team-elementor' ) . '</p>';
                $admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor Now', 'exclusive-team-elementor' ) ) . '</p>';
            }

            echo '<div class="error">' . $admin_message . '</div>';
        }

        /**
        * Include Addons
        *
        */
        public function exad_add_elements() {

        	require_once EXAD_TEAM_ELEMENTS . 'team-member/team-member.php';
            require_once EXAD_TEAM_ELEMENTS . 'team-carousel/team-carousel.php';
            
        }

        /**
         * Initialize the tracker
         *
         * @return void
         */
        protected function exclusive_team_appsero_init() {

            if ( ! class_exists( '\Appsero\Client' ) ) {
                require_once __DIR__ . '/vendor/appsero/src/Client.php';
            }

            $client = new \Appsero\Client( '966d5315-a3a2-4622-93a3-7836d5eb251a', 'Exclusive Team Elementor', __FILE__ );

            // Active insights
            $client->insights()->init();

            // Active automatic updater
            $client->updater();

        }

        /**
        * Check the elementor installed or not
        */
        public function _is_elementor_installed() {
            $file_path = 'elementor/elementor.php';
            $installed_plugins = get_plugins();

            return isset( $installed_plugins[ $file_path ] );
        }


    }

// Instance of the Exclusive_Addons_Core class
\DevsCred\Exclusive_Team_Elementor::get_instance();

}