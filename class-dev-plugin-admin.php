<?php

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
 
	require_once __DIR__ . '/../vendor/autoload.php';
	use League\Csv\Reader;
	use League\Csv\Writer;
	
	use Dompdf\Dompdf;
	use Dompdf\Options;
	
	
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
	 
	 
	public function prefix_send_email_to_admin(){
		//echo $_POST["fullname"];
		
		$userID = get_current_user_id();
		$my_post = array(
		  'post_title'    => wp_strip_all_tags( $_POST['post_title']),
		  'post_content'  =>'Student name :'.$_POST['student_name']."\n ".'Student email : '.$_POST['student_email']."\n".'Student class :'.$_POST['student_class'].'Student image : '.$_POST['student_image']." ",
		  'post_title' => 'student post', // title of the article
		  'post_status'   => 'publish',
		  'post_type' => 'students',
		);
		// Insert the post into the databas
        $post_id  = (wp_insert_post($my_post));

        $student_name= (isset($_POST['student_name'])) ? $_POST['student_name'] : get_post_meta($post_id , 'student_name', true);
        $student_email= (isset($_POST['student_email'])) ? $_POST['student_email'] : get_post_meta($post_id , 'student_email', true);
        $student_class= (isset($_POST['student_class'])) ? $_POST['student_class'] : get_post_meta($post_id , 'student_class', true);
		//
		  $student_image= (isset($_POST['student_image'])) ? $_POST['student_image'] : get_post_meta($post_id , 'student_image', true);
		//
        update_post_meta($post_id,'student_name',$student_name);
        update_post_meta($post_id,'student_email',$student_email);
        update_post_meta($post_id,'student_class',$student_class);
		//
		 update_post_meta($post_id,'student_image',$student_image);
		//

	}
	public function prefix_send_proflie_to_admin(){
		echo $_POST["student_name"];
		echo $_POST["student_email"];
		echo $_POST["student_class"];
		//
		echo $_POST["student_image"];
		//
	}

	
	
	
    
	public function get_student_name(){
		$name = $_POST['student_name'];
		update_post_meta('student_info', 'student_name',$name);	
	}	
	
	public function create_admin_menu(){
		add_menu_page(
			 __( 'admin Menu Title'),
			'admin menu',
			'manage_options',
			'custom_page',
			array($this, 'admin_setting_function')
			
		);
	}
	
	/*
		admin function
	*/
	public function admin_setting_function(){		
		?>		
			<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post"enctype="multipart/form-data">
				Pagination number:<input type="text" name="pagination_number" id="pagination_number"/>
				<input type="submit" value =" submit"/>
				
			</form>
		<?php
	}
	
	/*end admin function*/
	
	public function create_posttype() {
		// CPT Options
            $labels = array(
					'name' =>  'students',
					'singular_name' =>  'Student',
					'student_name' => 'student_name',
					'student_email' => 'student_email',
					'student_class' => 'student_class',
					
					//
					'student_image' => 'student_image',
					//
					
					
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

            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'revisions',
            ),
            'taxonomies' => array('category', 'post_tag'),
            'menu_position' => 5,
            'exclude_from_search' => false,
        );
        register_post_type('students',$args);
	}
		
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;	
		add_action('init', array($this ,'create_posttype'));
		
		add_action('admin_menu', array($this ,'create_admin_menu'));
		add_action('admin_post_contact_form', array($this, 'admin_setting_function'));

        add_action( 'admin_post_nopriv_update',array($this,'update_to_admin') );
        add_action( 'admin_post_update',array($this,'update_to_admin'));

        add_action('admin_post_nopriv_contact_form', array($this,'prefix_send_email_to_admin'));
		add_action('admin_post_contact_form', array($this,'prefix_send_email_to_admin'));
		add_action('admin_post_nopriv_contact_form', array($this,'prefix_send_proflie_to_admin'));
		add_action('admin_post_contact_form', array($this,'prefix_send_proflie_to_admin'));

		add_action( 'add_meta_boxes', array($this,'meta_boxes_student'));
		add_action( 'save_post', array($this,'save_metabox_callback'));
		
		//task16
		add_action( 'admin_post_nopriv_export',array($this,'export_file_csv') );
        add_action( 'admin_post_export',array($this,'export_file_csv'));		
	}
	
		
public function export_file_csv(){
		
		$options = new Options();
		$options->set('defaultFont', 'Courier');
		$options->set('isRemoteEnabled', TRUE);
		$options->set('debugKeepTemp', TRUE);
		$options->set('isHtml5ParserEnabled', true);
		$dompdf = new Dompdf($options);		?>		
		<?php
		$wpb_all_student = new WP_Query(array('post_type'=>'students', 'post_status'=>'publish', 'posts_per_page'=>-1)); 			
		
		$csv = Writer::createFromString('');			
		//$csv->insertOne('<table>');				
		//$csv->insertOne('<tr><th>Name</th><th>Class</th><th>Email</th><th>Image</th></tr>');			
		while ( $wpb_all_student->have_posts() ) : $wpb_all_student->the_post();  
			
			
		$html = '<label>Student Name</label>:'. get_post_meta( get_the_ID($post), 'student_name', true ).'</br>'.
		'<label>Student Email</label>:'. get_post_meta( get_the_ID($post), 'student_class', true ).'</br>'.
		'<label>Student Class</label>: '. get_post_meta( get_the_ID($post), 'student_class', true ).'</br>'.	
		'<label>Student image</label>:</br> '.'<image width="100" height="50" src="'.('http://wordpress.test/wp-content/uploads/2018/10/anhcongty1.jpg ') .'"></image>';		
		endwhile;
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();	
	$dompdf->stream();
			
	}
	public function student_name_display_callback($post){
		wp_nonce_field('custom_nonce_action', 'custom_nonce');
		$name = get_post_meta( get_the_ID($post), 'student_name', true);
		
		?>		
			<input type="text" name="pagination_number" id="pagination_number" value="<?php  echo $name ?>">
		<?php
	}
	
	
	//fdghjklchooo
	public function student_image_display_callback($post){
		wp_nonce_field('custom_nonce_action', 'custom_nonce');
		$image = get_post_meta(get_the_ID($post), 'student_image', true);
		?>
			
			<img src="<?php echo $image?>" name = "student_image_update" id="student_image_update" value ="" />
		<?php
	}
	//dfsgkjfghfg
	public function student_email_display_callback($post){
		wp_nonce_field('custom_nonce_action', 'custom_nonce');
		$email = get_post_meta(get_the_ID($post), 'student_email', true);
		?>
			<input type="text" name = "student_email_update" id="student_email_update" value ="<?php echo $email?>"/>
		<?php
	}
	
	public function student_class_display_callback($post){
		wp_nonce_field( 'custom_nonce_action', 'custom_nonce' );
		$class = get_post_meta(get_the_ID($post), 'student_class', true);
		?>
			<input type="text" name = "student_class_update" id="student_class_update" value ="<?php echo $class?>"/>
		<?php
	}
	public function meta_boxes_student(){
		add_meta_box( 'student name','student name',array($this, 'student_name_display_callback'), 'students',
            'advanced',
            'default' );	
		add_meta_box('student email', 'student email', array($this, 'student_email_display_callback'), 'students',
			'advanced',
			'default');
		add_meta_box('student class', 'student class', array($this, 'student_class_display_callback'), 'students',
			'advanced',
			'default');	
			//dfghjk
			add_meta_box('student image', 'student image', array($this, 'student_image_display_callback'), 'students',
			'advanced',
			'default');	
			//dfghjh
	}
	public function save_metabox_callback($post_id){
		$nonce_name   = isset( $_POST['custom_nonce'] ) ? $_POST['custom_nonce'] : '';
        $nonce_action = 'custom_nonce_action';
 
        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
		$student_name= (isset($_POST['student_name_update'])) ? $_POST['student_name_update'] : get_post_meta($post_id , 'student_name', true);
		$student_email= (isset($_POST['student_email_update'])) ? $_POST['student_email_update'] : get_post_meta($post_id , 'student_email', true);
		$student_class= (isset($_POST['student_class_update'])) ? $_POST['student_class_update'] : get_post_meta($post_id , 'student_class', true);
		//dfsdghfgjkh
		$student_image= (isset($_POST['student_image_update'])) ? $_POST['student_image_update'] : get_post_meta($post_id , 'student_image', true);
		//dfskhjgfhgj
		
		update_post_meta($post_id,'student_name',$student_name);
		update_post_meta($post_id,'student_email',$student_email);
		update_post_meta($post_id,'student_class',$student_class);		
		//dfgdhgj
		update_post_meta($post_id,'student_image',$student_image);		
		//dfshgjh
	}
	
	public function update_to_admin(){
        $post_id=$_POST['student_id'];
        $student_name= (isset($_POST['student_name_update'])) ? $_POST['student_name_update'] : get_post_meta($post_id , 'student_name', true);
        $student_email= (isset($_POST['student_email_update'])) ? $_POST['student_email_update'] : get_post_meta($post_id , 'student_email', true);
        $student_class= (isset($_POST['student_class_update'])) ? $_POST['student_class_update'] : get_post_meta($post_id , 'student_class', true);
		///dsfdgfgh
		$student_image= (isset($_POST['student_image_update'])) ? $_POST['student_image_update'] : get_post_meta($post_id , 'student_image', true);
		update_post_meta($post_id,'student_image',$student_image);
		///fdghfdhg
        update_post_meta($post_id,'student_name',$student_name);
        update_post_meta($post_id,'student_email',$student_email);
        update_post_meta($post_id,'student_class',$student_class);
        $url = site_url('student-management');
        wp_redirect($url);
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
