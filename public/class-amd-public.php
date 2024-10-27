<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.adpu.net
 * @since      1.0.0
 *
 * @package    amd
 * @subpackage amd/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    amd
 * @subpackage amd/public
 * @author      Jordi Verdaguer <info@adpu.net>
 */
class Amd_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      string    $text    The text of meta description.
	 * @param      int    $id    The current id of post.
	 * @param      string    $meta_description text of meta description.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_filter('document_title_parts',array( $this, 'adpuamd_write_add_metatitle'),10);
        add_action('wp_head', array( $this, 'adpuamd_write_add_metadescription'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		/*wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css', array(), $this->version, 'all' );*/

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		/*wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js', array( 'jquery' ), $this->version, false );*/

	}
	
/**
* This function print meta box 
* @since    1.0.0
* @param    string $text  value of metadescription
*/
public function adpuamd_write_add_metatitle($title) {
	$text_title = get_post_meta( get_the_ID(), '_amd_metatitle', true );
    if ( !empty($text_title) ) {
    $title['title']=$text_title;
    }
    return $title;
    }
  public function adpuamd_write_add_metadescription() {
	$text_desc = get_post_meta( get_the_ID(), '_amd_metadescription', true );
    if ( !empty($text_desc) ) {
    echo '<meta name="description" content="'.$text_desc.'" />
    ';
    }
    
    }

}