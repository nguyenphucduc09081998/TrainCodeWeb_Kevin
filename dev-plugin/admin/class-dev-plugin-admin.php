<?php
require_once __DIR__ . '/../vendor/autoload.php';
use League\Csv\Reader;
use League\Csv\Writer;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://dev-plugin.test
 * @since      1.0.0
 *
 * @package    Dev_Plugin
 * @subpackage Dev_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dev_Plugin
 * @subpackage Dev_Plugin/admin
 * @author     Dev Plugin <info@dev-plugin.test>
 */
function student_export(){//xuat file 
	$query = new WP_Query(array(
		'post_type' => 'students',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'offset' =>  -1
	));
	$csv = Writer::createFromString('');
	$header = ['Student Name', 'Student Email', 'Student Class','Student Profile'];
	$csv->insertOne($header);
	while ($query->have_posts()) {
		$query->the_post();
		$post_id = get_the_ID();
		$name =get_post_meta(get_the_ID(), 'student_name', TRUE);
		$email=get_post_meta(get_the_ID(), 'student_email', TRUE);;
		$class =get_post_meta(get_the_ID(), 'student_class', TRUE);
		$profile =get_post_meta(get_the_ID(), 'student_profile', TRUE);;
		$records = [
			[$name, $email, $class,$profile],
		];
		$csv->insertAll($records);
	}
	$csv->output('users.csv');
}
function student_export_pdf(){//xuat file

$options = new Options();
$options->set('defaultFont', 'Courier');
$options->set('isRemoteEnabled', TRUE);
$options->set('debugKeepTemp', TRUE);
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
 $html= '<label>Student Name</label>:'. get_post_meta($_POST['id'] ,'student_name', TRUE).'</br>'.
 '<label>Student Email</label>:'. get_post_meta($_POST['id'], 'student_email', TRUE).'</br>'.
'<label>Student Class</label>: '. get_post_meta($_POST['id'], 'student_class', TRUE).'</br>'.
// '<label>Student profile</label>: '.'<image src="'.content_url('uploads/2018/08/1546195_570405293092773_8307200118171649519_n-1.jpg').'"></image>';
'<ladd_meta_boxabel>Student profile</label>:</br> '.'<image width="480" height="480" src="'. get_post_meta($_POST['id'], 'student_profile', TRUE).'"></image>';

// $uploads = wp_upload_dir();

//  echo content_url('uploads/2018/08/1546195_570405293092773_8307200118171649519_n-1.jpg');
// $html='<image src="'.content_url('uploads/2018/08/1546195_570405293092773_8307200118171649519_n-1.jpg').'"></image>';
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream();
}

//add custom-fields
function sunset_contact_add_meta_box() {
	add_meta_box('student_name','Student Name','student_name_callback','students','high');
	add_meta_box('created_at','created_at','created_at_callback','students','high');	
	add_meta_box('update_at','update_at','update_at_callback','students','high');	
	
	add_meta_box('student_email','Student Email','student_email_callback','students','high');
	add_meta_box('student_class','Student Class','student_class_callback','students','high');
	add_meta_box('student_profile','Student Profile Picture','student_profile_callback','students','high');
}


function update_at_callback($post){
	$value= get_post_meta($post->ID,'update_at',true);
	
	echo '<input type="text" id="update_at_field" name="update_at_field" value="' . esc_attr( $value ) . '" size="25" />';
}
function created_at_callback($post){
	$value= get_post_meta($post->ID,'created_at',true);
	echo '<input type="text" id="created_at_field" name="created_at_field" value="' . esc_attr( $value ) . '" size="25" />';
}
function student_name_callback($post){
	wp_nonce_field( 'save_student_name_data', 'save_student_name_data_box_nonce' );
	$value= get_post_meta($post->ID,'student_name',true);
	echo '<input type="text" id="student_name_field" name="student_name_field" value="' . esc_attr( $value ) . '" size="25" />';
}
function student_email_callback($post){
	wp_nonce_field( 'save_student_email_data', 'save_student_email_data_box_nonce' );
	$value= get_post_meta($post->ID,'student_email',true);
	echo '<input type="text" id="student_email_field" name="student_email_field" value="' . esc_attr( $value ) . '" size="25" />';
}
function student_class_callback($post){
	wp_nonce_field( 'save_student_class_data', 'save_student_class_data_box_nonce' );
	$value= get_post_meta($post->ID,'student_class',true);
	echo '<input type="text" id="student_class_field" name="student_class_field" value="' . esc_attr( $value ) . '" size="25" />';
	
}
function student_profile_callback($post){
	wp_nonce_field( 'save_student_profile_data', 'save_student_profile_data_box_nonce' );
	$value= get_post_meta($post->ID,'student_profile',true);
	echo '<input type="text" id="student_profile_field" name="student_profile_field" value="' . esc_attr( $value ) . '" size="25" />';
	
}



function save_student_name_data( $post_id ) {
	if( ! isset( $_POST['save_student_name_data_box_nonce'] ) ){
		return;
	}
	
	if( ! wp_verify_nonce( $_POST['save_student_name_data_box_nonce'], 'save_student_name_data') ) {
		return;
	}
	
	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
		return;
	}
	
	if( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if( ! isset( $_POST['student_name_field'] ) ) {
		return;
	}
	
	$my_data = sanitize_text_field( $_POST['student_name_field'] );
	
	update_post_meta( $post_id, 'student_name', $my_data );
	
}
function save_student_email_data( $post_id ) {
	
	if( ! isset( $_POST['save_student_email_data_box_nonce'] ) ){
		return;
	}
	
	if( ! wp_verify_nonce( $_POST['save_student_email_data_box_nonce'], 'save_student_email_data') ) {
		return;
	}
	
	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
		return;
	}
	
	if( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if( ! isset( $_POST['student_email_field'] ) ) {
		return;
	}
	
	$my_data = sanitize_text_field( $_POST['student_email_field'] );
	
	update_post_meta( $post_id, 'student_email', $my_data );
	
}
function save_student_class_data( $post_id ) {
	
	if( ! isset( $_POST['save_student_class_data_box_nonce'] ) ){
		return;
	}
	
	if( ! wp_verify_nonce( $_POST['save_student_class_data_box_nonce'], 'save_student_class_data') ) {
		return;
	}
	
	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
		return;
	}
	
	if( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if( ! isset( $_POST['student_class_field'] ) ) {
		return;
	}
	
	$my_data = sanitize_text_field( $_POST['student_class_field'] );
	
	update_post_meta( $post_id, 'student_class', $my_data );
	
}
function save_student_profile_data( $post_id ) {
	
	if( ! isset( $_POST['save_student_profile_data_box_nonce'] ) ){
		return;
	}
	
	if( ! wp_verify_nonce( $_POST['save_student_profile_data_box_nonce'], 'save_student_profile_data') ) {
		return;
	}
	
	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
		return;
	}
	
	if( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if( ! isset( $_POST['student_profile_field'] ) ) {
		return;
	}
	
	$my_data = sanitize_text_field( $_POST['student_profile_field'] );
	
	update_post_meta( $post_id, 'student_profile', $my_data );
	
}
function create_posttype() {
	$labels = array(
	'name'=>'students',
		'singular_name' => 'students',
		'student_name' => 'student_name',

	);
	$args = array(
		'labels' => $labels,
		'post_status' => 'publish',
		'public' => true,
		'has_archive' => true,
		'publicly_queryable' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'student_profile_picture' => array('title', 'editor', 'thumbnail', 'custom-fields'),
		'supports' => array( 
			'title', 
			'editor', 
			'excerpt', 
			'thumbnail', 
			'custom-fields', 
			'revisions' ,
		),
		'taxonomies' => array('category', 'post_tag'),
		'menu_position' => 5,
		'exclude_from_search' => false
	);
	$id=register_post_type('students',$args);
}


function student_management(){
	add_options_page( 'Student management', 'Student management', 'manage_options', 'student_management', 'my_student_management' );
}
function my_student_management(){

}
 function prefix_admin_student_form() {
	if(!empty($_FILES['uploaded_file']))
	{
		$uploads = wp_upload_dir();
	  $path = $uploads['path'].'/'.(basename( $_FILES['uploaded_file']['name']));
	
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	$attachment_id = media_handle_upload( 'uploaded_file', $id );
	
	};
	 $my_post = array(
 		'post_content' => 'student_name :'.$_POST['name']."</br> ".'student_email : '.$_POST['Email']."\n".'student_class :'.$_POST['Class']." \n".'student_profile :'.$_POST['Profile']."\n "."URL image:".wp_get_attachment_url($attachment_id), // content of the article,
  		'post_title' => 'student post', // title of the article
  		'post_type' => 'students', // here is my custom post type, you can change it to your own type there.
		 'post_status' => 'publish',
);

$id= wp_insert_post($my_post);
update_post_meta($id,'student_name',$_POST['name']);
update_post_meta($id,'student_email',$_POST['Email']);
update_post_meta($id,'student_class',$_POST['Class']);
update_post_meta($id,'student_profile',wp_get_attachment_url($attachment_id));
update_post_meta($id,'student_profile_name',basename( $_FILES['uploaded_file']['name']));

var_dump($id);
var_dump($_POST['name']);
var_dump($_POST['Email']);
var_dump($_POST['Class']);


}
 function edit_image_profile() {
	 var_dump($_POST);
	if(!empty($_FILES['uploaded_file']))
	{
		$uploads = wp_upload_dir();
	  $path = $uploads['path'].'/'.(basename( $_FILES['uploaded_file']['name']));
	
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	$attachment_id = media_handle_upload( 'uploaded_file',$_POST['id'] );
	wp_redirect( home_url( $wp->request ).'/student-management?'.'?p=edit&id='.$_POST['id']);
	};

 var_dump(update_post_meta($_POST['id'],'student_profile',wp_get_attachment_url($attachment_id)));
}
/** Step 2 (from text above). */

function check_email(){
	$email= $_POST['email'];
	$query = new WP_Query(array(
		'post_type' => 'students',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'offset' =>  -1
	));
	
	while ($query->have_posts()) { 
		$query->the_post();
		$email_check=get_post_meta(get_the_ID(), 'student_email', TRUE);
		if($email_check==$email){
			?>0<?php
			return;
		}
	}
	?>1<?php
	die();
}
/** Step 1. */

function my_plugin_menu() {
	
    add_menu_page( 'Admin Menu', 'Admin Menu', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
}

/** Step 3. */
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?><form  action=<?php echo esc_url( admin_url('admin-post.php') ); ?> method="post">
	
	Time Login:<label style="color:red"><?php echo get_option('time_login')?></label> 
	
	<input type="hidden" name="action" value="admin_menu_custom">
	<input style="margin:10px" type="submit" value="OK">
  </form>'<?php
}










function setpaging(){
	global $paging;
	if(isset($_POST)){
		$paging=$_POST['pagination'];
	}
	else{
		$paging=5;
	}
}

function create_at_function( $post_id, $post, $update ){
	update_post_meta($post_id,'created_at',time());
}

function update_at_function( $post_id, $post, $update ){
	update_post_meta($post_id,'update_at',time());
}


function check_values( $post_id, $post, $update ){
	//update_option( 'update_at', time());
	update_post_meta($post_id,'update_at',time());
}


//get_post_meta( get_the_ID($post), 'student_class', true )
//get_post_meta( get_the_ID($post), 'student_class', true )



function check_login(){
	update_option( 'time_login', time());
	wp_redirect('http://wordpress.test/wp-admin/options-general.php?page=my-unique-identifier');
}



function set_option(){
	update_option( 'time_login', time());
	wp_redirect('http://wordpress.test/wp-admin/options-general.php?page=my-unique-identifier');
}
class Dev_Plugin_Admin {

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

	
add_action('init','create_posttype');
add_action( 'admin_post_export_csv','student_export' );
add_action( 'admin_post_nopriv_export_csv','student_export' );
add_action( 'admin_post_export_pdf','student_export_pdf' );
add_action( 'admin_post_nopriv_export_pdf','student_export_pdf' );
add_action( 'admin_post_student_form','prefix_admin_student_form' );
add_action( 'admin_post_nopriv_student_form','prefix_admin_student_form' );
add_action( 'admin_post_edit_profile','edit_image_profile' );
add_action( 'admin_post_nopriv_edit_profile','edit_image_profile' );
add_action( 'admin_post_admin_menu_custom','check_login' );
add_action( 'admin_post_nopriv_admin_menu_custom','check_login' );
add_action( 'admin_post_admin_view_post','view_post' );
add_action( 'admin_post_nopriv_admin_view_post','view_post' );
add_action( 'admin_post_nopriv_check_mail','check_email' );
add_action( 'admin_post_check_mail','check_email' );



add_action('wp_login', 'check_login');




add_action( 'add_meta_boxes', 'sunset_contact_add_meta_box' );
add_action( 'save_post', 'save_student_class_data' );
add_action( 'save_post', 'save_student_profile_data' );
add_action( 'save_post', 'save_student_name_data' );
add_action( 'save_post', 'save_student_email_data' );
add_action( 'admin_menu', 'my_plugin_menu' );
add_action( 'admin_menu', 'student_management' );
add_action( 'wp_insert_post', 'create_at_function', 10, 3 );

//update_at
add_action( 'wp_insert_post', 'update_at_function', 10, 3 );
add_action( 'post_updated', 'check_values', 10, 3 );


	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dev_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dev_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dev-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dev_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dev_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dev-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}

}
