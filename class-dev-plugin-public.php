<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://dev-plugin.test
 * @since      1.0.0
 *
 * @package    Dev_Plugin
 * @subpackage Dev_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dev_Plugin
 * @subpackage Dev_Plugin/public
 * @author     Dev Plugin <info@dev-plugin.test>
 */
class Dev_Plugin_Public {
	
	
	
	
	public function student_management(){
		
		if($_GET['id']== 'edit'){      
		//edit 
			?>
			
                <div>
                    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" >
                        Student ID <br>
                        <input type="text" name="student_id" id="student_id" value="<?php echo $_GET['ID'] ?>"><br>
                        Student Name<br>
                        <input type="text" name="student_name_update" id="student_name_update" value = "<?php $name = get_post_meta( $_GET['ID'], 'student_name', true ); echo $name;?>"><br>
                        Student Email<br>
                        <input type="Email"name="student_email_update" id="student_email_update" value = "<?php $email = get_post_meta( $_GET['ID'], 'student_email', true ); echo $email;?>"><br>
                        Student Class<br>
                        <input type="Class"name="student_class_update" id="student_class_update" value="<?php $class = get_post_meta( $_GET['ID'], 'student_class', true ); echo $class;?>"><br>

                        <input type="submit" value="Update">
                        <input type="hidden" name="action" value="update">
						
						<!--task16 de xuat csd-->
					
							<input type="submit" value="Export">
							<input type="hidden" name="action" value="export"> 
						
							
					</form>
                </div>
               
				
			<?php
		}else if($_GET['id']== 'delete'){  
		//delete
		
			wp_delete_post($_GET['ID']);
			$url = site_url('student-management');
			wp_redirect($url);
		}
		else{		
			$wpb_all_student = new WP_Query(array('post_type'=>'students', 'post_status'=>'publish', 'posts_per_page'=>-1)); 
			 if ( $wpb_all_student->have_posts() ) : ?>        
                <table border="1">
                    <thead>
                    <tr>                       
                        <th>Student Name</th>
                        <th>Student Class</th>
                        <th>Student Email</th>  
					
						<th>Image</th>
						
						<th>Delete/Edit</th>	
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ( $wpb_all_student->have_posts() ) : $wpb_all_student->the_post(); ?>
                        <tr>                               
                            <?php $name = get_post_meta( get_the_ID($post), 'student_name', true );?>
                            <td><?php echo $name ?></td>
                            <?php $class = get_post_meta( get_the_ID($post), 'student_class', true );?>
                            <td><?php echo $class ?></td>
                            <?php $email = get_post_meta( get_the_ID($post), 'student_email', true );?>
                            <td><?php echo $email ?></td> 
						
							<?php $image = get_post_meta( get_the_ID($post), 'student_image', true ); ?>
							
							
							
                            <td><label> <img src="http://wordpress.test/wp-content/uploads/2018/10/anhcongty1.jpg" height="30" width="30"/></label></td> 
						
						
						
						
							<td>
								<a href="/wordpress.test/student-management">List</a>
								<a href="/wordpress.test/student-management/?id=delete&ID=<?php echo get_the_ID($post)?>">Delete</a>
								<a href="/wordpress.test/student-management/?id=edit&ID=<?php echo get_the_ID($post)?>">Edit</a>
							
								
							</td>	
							
                        </tr>
						
                    <?php endwhile; ?>				
						
						
                    </tbody>
                </table>                  
            <?php wp_reset_postdata(); ?>
            <?php else : ?>
            <p><?php( 'ko show duoc' );?></p>
			<?php endif; ?>
			
			
			<?php
		
		}
		
        
	}


	public function student_form(){
		ob_start()
		?><div id="content">
			<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post"enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-3">
						<label for="student_name">Student Name</label>
					</div>
					<div class="col-md-9">
						<input type="text" name="student_name" id="student_name"></br>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="student_email">Email</label>
					</div>
					<div class="col-md-9">
						<input type="student_email" name="student_email" id="student_email" placeholder="Enter Email"></br>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="student_class">Class</label>
					</div>
					<div class="col-md-9">
						<select name="student_class" id="student_class">
					
							<option value="a1">A1</option>
							<option value="b1">B1</option>
							<option value="c3">C3</option>
						</select></br>
					</div>
				</div>	
				<!----------->
				<div class="row">
					<div class="col-md-3">
						<label for="student_image">image</label>
					</div>
					<div class="col-md-9">
						<input type="file" name="student_image" id="student_image" ></br>
					</div>
				</div>
				<!----------->

				
				<input type="hidden" name="action" value="contact_form">
				 <input type="submit" value="submit" name="submit">
			</div>	
		<?php
		return ob_get_clean();
	}
			

	
	
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
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;	
		add_shortcode( 'student_form', array($this,'student_form'));
		add_shortcode('student_management', array($this,'student_management'));
		//task16
		add_shortcode('ExportToCSV', array($this,'ExportToCSV'));
		
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
		 * defined in Dev_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dev_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dev-plugin-public.css', array(), $this->version, 'all' );

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
		 * defined in Dev_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dev_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dev-plugin-public.js', array( 'jquery' ), $this->version, false );

	}
}
		