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

 function form_createtion_student_management(){
	 if($_GET['p']=="edit"){
		 if(isset($_GET['id'])){
			?><label>Student Profile</label>: <image src="<?php echo get_post_meta($_GET['id'], 'student_profile', TRUE);?>"></image>
<form action=<?php echo esc_url( admin_url('admin-post.php') ); ?> method="post" enctype="multipart/form-data">			
			  <input type="file" name="uploaded_file"></input><br />
			  <input type="hidden" name="id" value=<?php echo $_GET['id']?>>
                <input type="hidden" name="action" value="edit_profile">
			  <input type="submit" value="Submit">
</form>
			<?php
		 }
		 else{
			 echo do_shortcode("[test]");
		 }
	 }
	 else if($_GET['p']=="delete"&&isset($_GET['id'])){
		wp_delete_post($_GET['id']);
		wp_redirect('http://wordpresstr.test/student_managerment/');
	 }
	 else{
		 $offset= $_GET['offset'];
		 if(!isset($_GET['offset'])){
			 $offset=0;
		 }
	$query = new WP_Query(array(
		'post_type' => 'students',
		'post_status' => 'publish',
		'posts_per_page' => get_option('offset'),
		'offset' =>  $offset
	));
	?>
	<table>
	<tr>
    <th>Student Name</th>
    <th>Student Email</th>
    <th>Student Class</th>
	<th>Student Profile</th>
	<th>Action</th>
  </tr>
	<?php
	?>
<form action=<?php echo esc_url( admin_url('admin-post.php') ); ?> method="post">
<input type="hidden" name="action" value="export_csv">
	 <input style="padding:2px; margin-left: 93%; " type="submit"  value="Export">
	 </form>
	 <?php
	while ($query->have_posts()) {
		$query->the_post();
		$post_id = get_the_ID();
		?>
		 <tr>
		 <td><?php echo get_post_meta(get_the_ID(), 'student_name', TRUE);?> </td>
		 <td><?php echo get_post_meta(get_the_ID(), 'student_email', TRUE);?></td>
		 <td><?php echo get_post_meta(get_the_ID(), 'student_class', TRUE);?></td>
		 <td> <image src="<?php echo get_post_meta(get_the_ID(), 'student_profile', TRUE);?>"></image></td>
		 <td><button style="padding:2px;" onclick="window.location.href='<?php echo get_post_permalink($post_id)?>'">View</button>
		 <button style="padding:2px;" onclick="window.location.href='<?php echo home_url( $wp->request ).'/student_managerment?'.'?p=edit&id='.$post_id?>'">Edit</button>
		 <button style="padding:2px;" onclick="window.location.href='<?php echo home_url( $wp->request ).'/student_managerment?'.'?p=delete&id='.$post_id?>'">Del</button>

		 </td>
		<?php
	}
	?>
	</table>
	<?php
	?><a href="<?php echo home_url( $wp->request ).'/student-management?offset='. ($offset-get_option('offset')) ?>" class="previous">&laquo; Previous</a>
	<a href="<?php echo home_url( $wp->request ).'/student-management?offset='.($offset-get_option('offset')) ?>" class="next">Next &raquo;</a>
	<?php
	wp_reset_query();
}
}
function pagination_tdc() {
	if( is_singular() )
	return;
	global $wp_query;
	/** Ngừng thực thi nếu có ít hơn hoặc chỉ có 1 bài viết */
	if( $wp_query->max_num_pages <= 1 )
	return;
	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max = intval( $wp_query->max_num_pages );
 
	/** Thêm page đang được lựa chọn vào mảng*/
	if ( $paged >= 1 )
	$links[] = $paged;
	/** Thêm những trang khác xung quanh page được chọn vào mảng */
	if ( $paged >= 3 ) {
		   $links[] = $paged - 1;
		   $links[] = $paged - 2;
	 }
 
	 if ( ( $paged + 2 ) <= $max ) {
		   $links[] = $paged + 2;
		   $links[] = $paged + 1;
	  }
 
 /** Hiển thị thẻ đầu tiên \n để xuống dòng code */
  echo '<ul class="pagination">' . "\n";
 
  /** Hiển thị link về trang trước */
  if ( get_previous_posts_link() )
  printf( '<li>%s</li>' . "\n", get_previous_posts_link('Trước') );
 
  /** Nếu đang ở trang 1 thì nó sẽ hiển thị đoạn này */
  if ( ! in_array( 1, $links ) ) {
  $class = 1 == $paged ? ' class="active"' : '';
  printf( '<li %s><a rel="nofollow" class="page larger" href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
  if ( ! in_array( 2, $links ) )
  echo '<li>…</li>';
  }
 
  /** Hiển thị khi đang ở một trang nào đó đang được lựa chọn */
  sort( $links );
  foreach ( (array) $links as $link ) {
  $class = $paged == $link ? ' class="active"' : '';
  printf( '<li%s><a rel="nofollow" class="page larger" href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
  }
 
  /** Hiển thị khi đang ở trang cuối cùng */
  if ( ! in_array( $max, $links ) ) {
  if ( ! in_array( $max - 1, $links ) )
  echo '<li>…</li>' . "\n";
  $class = $paged == $max ? ' class="active"' : '';
  printf( '<li%s><a rel="nofollow" class="page larger" href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
  }
 
  /** Hiển thị link về trang sau */
  if ( get_next_posts_link() )
  printf( '<li>%s</li>' . "\n", get_next_posts_link('Sau') );
  echo '</ul>' . "\n";
 }
function form_creation(){
?>
<script src="js/jquery.min.js"></script>
<script>
function submit_task(){
	$.ajax(){
		type: "POST", // use $_POST method to submit data
		url:<?php echo esc_url( admin_url('admin-post.php') ); ?>,
		data:{
			email: $('#email')
		}

	}
}
</script>
<form action=<?php echo esc_url( admin_url('admin-post.php') ); ?> method="post" enctype="multipart/form-data">
<h1>Student form</h1>
<input type="hidden" name="action" value="student_form">
  Student Name:<br>
  <input type="text" name="name" value=""><br>
  Student Email:<br>
  <input type="text" id ='email' name="Email" value=""><br>
    Student Class:<br>
  <input type="text" name="Class" value=""><br>
    Student Profile:<br>
  <input type="text" name="Profile" value=""><br>
  <input type="file" name="uploaded_file"></input><br />
  <input type="submit" value="Submit">
</form>
<?php
}
add_shortcode('test', 'form_creation');
add_shortcode('student_management','form_createtion_student_management');

class Dev_Plugin_Public {

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
// Our custom post type function

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
