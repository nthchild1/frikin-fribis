<?php

/**
 * The metablocks of the plugin.
 *
 * @link       https://frik-in.io
 * @since      1.0.0
 * @package     Frikin_Fribis
 * @subpackage    Frikin_Fribis/admin
 * @author       Frik-in <webmaster@frik-in.com>
 */
class Frikin_Fribis_Admin_Metablocks
{

	/**
	 * The post meta data
	 *
	 * @since        1.0.0
	 * @access        private
	 * @var        string $meta The post meta data.
	 */
	private $meta;

	/**
	 * The ID of this plugin.
	 *
	 * @since        1.0.0
	 * @access        private
	 * @var            string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since        1.0.0
	 * @access        private
	 * @var            string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @since        1.0.0
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->set_meta();
	}

	private function sanitizer($type, $data)
	{
		if (empty($type)) {
			return;
		}
		if (empty($data)) {
			return;
		}
		$return = '';
		$sanitizer = new Frikin_Fribis_Sanitize();
		$sanitizer->set_data($data);
		$sanitizer->set_type($type);
		$return = $sanitizer->clean();
		unset($sanitizer);
		return $return;
	} // sanitizer()

	/*
	 * Register fields for use in the REST API.
	 */
	public function register_meta_for_rest()
	{
		$args = array(
			'type' => 'string',
			'single' => true,
			'show_in_rest' => true,
		);

		$metas = $this->get_metablocks_fields();
		foreach ($metas as $meta) {
			$name = $meta[0];
			register_meta('post', $name, $args);
		}
	}


	/**
	 * Sets the class variable $options
	 */
	public function set_meta()
	{
		global $post;
		if (empty($post)) {
			return;
		}
		if (!in_array($post->post_type, array('fribi'))) {
			return;
		}
		//wp_die( '<pre>' . print_r( $post->ID ) . '</pre>' );
		$this->meta = get_post_custom($post->ID);
	} // set_meta()

	public function register_metablocks()
	{
		// Register block styles for both frontend + backend.
		wp_register_style(
			'frikin-fribis-build-css', // Handle.
			plugins_url('dist/blocks.style.build.css', dirname(__FILE__)), // Block style CSS.
			array('wp-editor'), // Dependency to include the CSS after it.
			null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
		);

		// Register block editor script for backend.
		wp_register_script(
			'frikin-fribis-build-js', // Handle.
			plugins_url('dist/blocks.build.js', dirname(__FILE__)), // Block.build.js: We register the block here. Built with Webpack.
			array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-data', 'wp-core-data',), // Dependencies, defined above.
			null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
			true // Enqueue the script in the footer.
		);

		// Register block editor styles for backend.
		wp_register_style(
			'frikin-fribis-build-editor-css', // Handle.
			plugins_url('dist/blocks.editor.build.css', dirname(__FILE__)), // Block editor CSS.
			array('wp-edit-blocks'), // Dependency to include the CSS after it.
			null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
		);

		/**
		 * Register Gutenberg block on server-side.
		 *
		 * Register the block on server-side to ensure that the block
		 * scripts and styles for both frontend and backend are
		 * enqueued when the editor loads.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
		 * @since 1.16.0
		 */
		register_block_type(
			'frik-in/fribis-block-build', array(
				// Enqueue blocks.style.build.css on both frontend & backend.
				'style' => 'frikin-fribis-build-css',
				// Enqueue blocks.build.js in the editor only.
				'editor_script' => 'frikin-fribis-build-js',
				// Enqueue blocks.editor.build.css in the editor only.
				'editor_style' => 'frikin-fribis-build-editor-css',
			)
		);
	}


	/**
	 * Returns an array of the all the metabox fields and their respective types
	 *
	 * @return        array        Metabox fields and types
	 * @since        1.0.0
	 * @access        public
	 */
	private function get_metablocks_fields()
	{
		$fields = array();

        $fields[] = array('uid', 'text');
        $fields[] = array('fribi_id', 'text');
        $fields[] = array('set', 'text');
        $fields[] = array('slug', 'text');
        $fields[] = array('parts', 'text');
        $fields[] = array('bio', 'text');
        $fields[] = array('locked_bio', 'text');

		return $fields;
	} // get_metabox_fields()

	/**
	 * Check each nonce. If any don't verify, $nonce_check is increased.
	 * If all nonces verify, returns 0.
	 *
	 * @return        int        The value of $nonce_check
	 * @since        1.0.0
	 * @access        public
	 */
	private function check_nonces($posted, $post_type)
	{
		$nonces = array();
		$nonce_check = 0;
		if (in_array($post_type, array('fribi'))) {
			$nonces[] = 'place_additional_info_nonce';
			$nonces[] = 'place_social_networks_nonce';
		} elseif (in_array($post_type, array('event'))) {
			$nonces[] = 'event_additional_info_nonce';
		} elseif (in_array($post_type, array('activity'))) {
			$nonces[] = 'activity_additional_info_nonce';
		}

		foreach ($nonces as $nonce) {
			if (!isset($posted[$nonce])) {
				$nonce_check++;
			}
			if (isset($posted[$nonce]) && !wp_verify_nonce($posted[$nonce], $this->plugin_name)) {
				$nonce_check++;
			}
		}
		return $nonce_check;
	} // check_nonces()

	/**
	 * Saves metablock data
	 *
	 * @param int $post_id The post ID
	 * @param object $object The post object
	 * @return    void
	 * @since    1.0.0
	 * @access    public
	 */
	public function validate_meta($post_id, $object)
	{
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
		if (!in_array($object->post_type, array('fribi'))) {
			return $post_id;
		}
		$nonce_check = $this->check_nonces($_POST, $object->post_type);
		if (0 < $nonce_check) {
			return $post_id;
		}
		$metas = $this->get_fribi_metablock_fields();

		foreach ($metas as $meta) {
			$name = $meta[0];
			$type = $meta[1];

			$new_value = $this->sanitizer($type, $_POST[$name]);

			update_post_meta($post_id, $name, $new_value);
		} // foreach
	} // validate_meta()

	/**
	 * Registers template for fribi custom post type
	 *
	 * @param int $post_id The post ID
	 * @param object $object The post object
	 * @return    void
	 * @since    1.0.0
	 * @access    public
	 */
	public function register_template()
	{

	    $post_type_object = get_post_type_object('fribi');

		$post_type_object->template = array(
			array('frik-in/fribi-info')
		);
		$post_type_object->template_lock = 'all';
	}

}
