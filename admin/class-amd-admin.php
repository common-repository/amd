<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://adpu.net
 * @since      1.0.0
 *
 * @package    amd
 * @subpackage amd/public
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    amd
 * @subpackage amd/public
 * @author      Jordi Verdaguer <info@adpu.net>
 */
class Amd_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('add_meta_boxes', array( $this, 'amd_add_metaboxes'  ));
		add_action( 'save_post', array( $this, 'amd_save_metatags'));
		add_action( 'admin_menu', array( $this, 'amd_scan_menu'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/amd-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/amd-admin.js', array( 'jquery' ), $this->version, true );

	}


	/**
* This function add meta box
* @since    1.0.0
*/
public function amd_add_metaboxes() {
	add_meta_box(
    'amd-metatitle',
    'Title tag',
    array( $this, 'amd_print_metatitle_meta_box' )
  );
  add_meta_box(
    'amd-metadescription',
    'Meta description',
    array( $this, 'amd_print_metadescription_meta_box' )
  );

	
  
}


/**
* This function print meta box 
* @since    1.0.0
* @param    string $val  value of metadescription
*/

public function amd_print_metatitle_meta_box() {
  $val_title = get_post_meta( get_the_ID(), '_amd_metatitle', true ); 
  include_once 'partials/amd_create_metatitle_metabox.php';
  
  }
  public function amd_print_metadescription_meta_box() {
  $val_desc = get_post_meta( get_the_ID(), '_amd_metadescription', true ); 
  include_once 'partials/amd_create_metadescription_metabox.php';
  
  }

/**
* This function scan meta tags
* @since    1.0.0
*/
public function amd_scan_menu() {
	add_options_page( 'Scan Meta tags', 'Scan Meta tags', 'manage_options', 'scan-meta-tags', array(
				$this,'amd_scan_menu_options') );
}

/**
* This function scan list options
* @since    1.0.2
*/
public function amd_scan_menu_options($type) {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$url = admin_url();

  global $post,$wpdb;
    if( isset( $_GET[ 'tab' ] )  ) {
       $active_tab = $_GET[ 'tab' ];
    }else{
       $active_tab ='posts';
    }
    if( isset( $_GET[ 'current_page' ] )  ) {
       $current_page = $_GET[ 'current_page' ];
    }else{
       $current_page =1;
    }
  /* Total posts and pages */
  $count_posts = wp_count_posts();
  $count_pages = wp_count_posts('page');

  /* *** POSTS ** */
  /* Title tag */
  /* Title tag filled */
  $amd_title_tag_filled=$wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE postmeta.meta_key='_amd_metatitle' AND postmeta.meta_value != '' AND posts.post_type ='post' AND posts.post_status ='publish' ");
   /* Title tag empty */
  $amd_title_tag_empty=($count_posts->publish) - $amd_title_tag_filled;
  /* Title tag with more of 60 characters */ 
  $amd_title_tag_lenght_exceed=$wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts ON postmeta.post_id = posts.id WHERE  postmeta.meta_key='_amd_metatitle' AND char_length(postmeta.meta_value) > 60 AND posts.post_type ='post' AND posts.post_status ='publish' ");
  /* Title tag with less of 60 characters */
  $amd_title_tag_lenght_optimal=($count_posts->publish)-$amd_title_tag_lenght_exceed-$amd_title_tag_empty;
  /* Duplicate title tags */
  $amd_title_tag_no_duplicated=$wpdb->get_var( "SELECT COUNT(distinct meta_value) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE postmeta.meta_key='_amd_metatitle' AND postmeta.meta_value != '' AND posts.post_type ='post' AND posts.post_status ='publish' ");
  $amd_title_tag_duplicated=$amd_title_tag_filled - $amd_title_tag_no_duplicated;

  /* Meta description */
  /* Meta description filled */
  $amd_meta_description_filled=$wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE postmeta.meta_key='_amd_metadescription' AND postmeta.meta_value!= '' AND posts.post_type ='post' AND posts.post_status ='publish' ");
   /* Meta description empty */
  $amd_meta_description_empty=($count_posts->publish)-$amd_meta_description_filled;
  /* Meta description with more of 160 characters */ 
  $amd_meta_description_lenght_exceed=$wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE  postmeta.meta_key='_amd_metadescription' AND char_length(postmeta.meta_value) > 160 AND posts.post_type ='post' AND posts.post_status ='publish' ");
  /* Meta description with less of 160 characters */
  $amd_meta_description_lenght_optimal=($count_posts->publish)-$amd_meta_description_lenght_exceed-$amd_meta_description_empty;

/* Duplicate meta description */
  $amd_meta_description_no_duplicated=$wpdb->get_var( "SELECT COUNT(distinct meta_value) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE postmeta.meta_key='_amd_metadescription' AND postmeta.meta_value != '' AND posts.post_type ='post' AND posts.post_status ='publish' ");
  $amd_meta_description_duplicated=$amd_meta_description_filled - $amd_meta_description_no_duplicated;

/* *** PAGES ** */
  /* Title tag */
  /* Title tag filled */
  $amd_page_title_tag_filled=$wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE postmeta.meta_key='_amd_metatitle' AND postmeta.meta_value != '' AND posts.post_type ='page' AND posts.post_status ='publish' ");
   /* Title tag empty */
  $amd_page_title_tag_empty=($count_pages->publish) - $amd_page_title_tag_filled;
  /* Title tag with more of 60 characters */ 
  $amd_page_title_tag_lenght_exceed=$wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts ON postmeta.post_id = posts.id WHERE  postmeta.meta_key='_amd_metatitle' AND char_length(postmeta.meta_value) > 60 AND posts.post_type ='page' AND posts.post_status ='publish' ");
  /* Title tag with less of 60 characters */
  $amd_page_title_tag_lenght_optimal=($count_pages->publish)-$amd_page_title_tag_lenght_exceed-$amd_page_title_tag_empty;
  /* Duplicate title tags */
  $amd_page_title_tag_no_duplicated=$wpdb->get_var( "SELECT COUNT(distinct meta_value) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE postmeta.meta_key='_amd_metatitle' AND postmeta.meta_value != '' AND posts.post_type ='page' AND posts.post_status ='publish' ");
  $amd_page_title_tag_duplicated=$amd_page_title_tag_filled - $amd_page_title_tag_no_duplicated;

  /* Meta description */
  /* Meta description filled */
  $amd_page_meta_description_filled=$wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE postmeta.meta_key='_amd_metadescription' AND postmeta.meta_value!= '' AND posts.post_type ='page' AND posts.post_status ='publish' ");
   /* Meta description empty */
  $amd_page_meta_description_empty=($count_pages->publish)-$amd_page_meta_description_filled;
  /* Meta description with more of 160 characters */ 
  $amd_page_meta_description_lenght_exceed=$wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE  postmeta.meta_key='_amd_metadescription' AND char_length(postmeta.meta_value) > 160 AND posts.post_type ='page' AND posts.post_status ='publish' ");
  /* Meta description with less of 160 characters */
  $amd_page_meta_description_lenght_optimal=($count_pages->publish)-$amd_page_meta_description_lenght_exceed-$amd_page_meta_description_empty;

  /* Duplicate meta description */
  $amd_page_meta_description_no_duplicated=$wpdb->get_var( "SELECT COUNT(distinct meta_value) FROM $wpdb->postmeta as postmeta JOIN $wpdb->posts as posts  ON postmeta.post_id = posts.id WHERE postmeta.meta_key='_amd_metadescription' AND postmeta.meta_value != '' AND posts.post_type ='page' AND posts.post_status ='publish' ");
  $amd_page_meta_description_duplicated=$amd_page_meta_description_filled - $amd_page_meta_description_no_duplicated;

  
  /* ceil Returns the next highest integer value by rounding up value */
  $total_numposts=ceil(($count_posts->publish) / 10);
  $total_numpages=ceil(($count_pages->publish) / 10);
  $page_offset=(10*$current_page) - 10;


  /* Posts */
  if($active_tab =='posts'){
  if($current_page==1)
  {
     $args_post = array( 'order_by' => 'post_date','order' => 'desc','posts_per_page'   => 10);
  }else{
    $args_post = array( 'order_by' => 'post_date','order' => 'desc','posts_per_page'   => 10,'offset' => $page_offset );
  }
     $lastposts = get_posts( $args_post );
  
  }else{
  if($current_page==1)
  {
  	$args_page = array( 'number' => 10, 'sort_order' => 'desc','sort_column' => 'post_date' );
  }else{
  	 $args_page = array( 'number' => 10, 'sort_order' => 'desc','sort_column' => 'post_date','offset' => $page_offset );
  }
    $lastpages = get_pages ( $args_page );
  }
    
    include_once 'partials/amd_create_scan_meta_content.php';
    wp_reset_postdata();

}

/**
* This function save meta box value
* @since    1.0.0
*/


public function amd_save_metatags() {
  
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

 
  if ( isset( $_REQUEST['amd-metadescription'] ) ) {
    $amd_metadescription=$_REQUEST['amd-metadescription'];
  }else{
    return;
  }
   if ( isset( $_REQUEST['amd-metatitle'] ) ) {
    $amd_metatitle=$_REQUEST['amd-metatitle'];
    }else{
    return;
  }

  $texto_desc = trim( sanitize_text_field( $amd_metadescription ) );
  $texto_title = trim( sanitize_text_field( $amd_metatitle ) );

  update_post_meta( get_the_ID(), '_amd_metatitle', $texto_title );
  update_post_meta( get_the_ID(), '_amd_metadescription', $texto_desc );
}
	
}