<?php

/**
 * Fired during plugin activation
 *
 * @link       frik-in.io
 * @since      1.0.0
 *
 * @package    Frikin_Fribis
 * @subpackage Frikin_Fribis/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Frikin_Fribis
 * @subpackage Frikin_Fribis/includes
 * @author     Frik-in <webmaster@frik-in.com>
 */
class Frikin_Fribis_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-frikin-fribis-admin-post-types.php';

           Frikin_Fribis_Admin_Post_Types::new_cpt_fribi();
           flush_rewrite_rules();
	}

}
