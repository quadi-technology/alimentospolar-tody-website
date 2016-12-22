<?php
/* 
Plugin Name: JBalvin SMS MMS
Description: A complete wordpress plugin to send sms with a high capability.
Version: 3.2.3
Author: Briqs Data PVT LTD
*/
error_reporting(NULL);
define('JBalvin_SMS_MMS_VERSION', '3.2.3');
define('JBalvin_WP_SMS_DIR_PLUGIN', plugin_dir_url(__FILE__));
define('JBalvin_WP_ADMIN_URL', get_admin_url());
define('JBalvin_SMS_MMS_MOBILE_REGEX', '/^[\+|\(|\)|\d|\- ]*$/');

$date = date('Y-m-d H:i:s' ,current_time('timestamp', 0));

include_once __DIR__ . '/includes/classes/twilio/Twilio/autoload.php';
use Twilio\Rest\Client;
//$client = new Services_Twilio($account_sid, $auth_token);


$account_sid = 'AC58bb9059b290eb1e0f4f28ae02368e1b';
$auth_token = 'cbab916f4201fbd1cf3e377f60b58e0f';
$mms_sender = '+17325734981';

$mms = new Client($account_sid, $auth_token);

// Use default gateway class if webservice not active 
// SMS Gateway plugin
$max_file_size = '500000';
// Create object of plugin
$WP_SMS_Plugin = new JBalvin_SMS_MMS_Plugin;
register_activation_hook( __FILE__, array( 'JBalvin_SMS_MMS_Plugin', 'install' ) );
register_activation_hook( __FILE__, array( 'JBalvin_SMS_MMS_Plugin', 'add_cap' ) );

// WP SMS Plugin Class
class JBalvin_SMS_MMS_Plugin {
	/**
	 * Wordpress Admin url
	 *
	 * @var string
	 */
	public $admin_url = JBalvin_WP_ADMIN_URL;
	
	/**
	 * WP SMS gateway object
	 *
	 * @var string
	 */
	public $sms;
	
	/**
	 * WP MMS gateway object
	 *
	 * @var string
	 */
	public $mms;
	/**
	 * WP SMS subscribe object
	 *
	 * @var string
	 */
	/**
	 * Current date/time
	 *
	 * @var string
	 */
	public $date;	
	
	/**
	 * Wordpress Database
	 *
	 * @var string
	 */
	protected $db;
	
	/**
	 * Wordpress Table prefix
	 *
	 * @var string
	 */
	protected $tb_prefix;
	
	/**
	 * Constructors plugin
	 *
	 * @param  Not param
	 */
	 
	public $regerror;
	
	public $success;
	 
	public function __construct() {
		global $mms,$sms, $wpdb, $table_prefix, $date,$mms_sender,$max_file_size;
		global $regerror,$success;
		$this->mms = $mms;
		$this->sms = $sms;
		$this->date = $date;
		$this->db = $wpdb;
		$this->tb_prefix = $table_prefix;
		$this->mms_sender = $mms_sender;
		$this->max_file_size = $max_file_size;	
		$this->error = $regerror;
		$this->success = $success;
		__('JBalvin WP SMS MMS', 'jbalvin-wp-sms');
		__('A complete wordpress plugin to send sms with a high capability.', 'jbalvin-wp-sms');
		
		$this->activity();
			
		add_action('admin_enqueue_scripts', array(&$this, 'admin_assets'));
		add_action('wp_enqueue_scripts', array(&$this, 'front_assets'));
		add_action('admin_bar_menu', array($this, 'adminbar'));
		add_action('dashboard_glance_items', array($this, 'dashboard_glance'));
		add_action('admin_menu', array(&$this, 'menu'));
	}
	
	/**
	 * Creating plugin tables
	 *
	 * @param  Not param
	 */
	static function install() {
		global $wp_sms_db_version;
		
		include_once dirname( __FILE__ ) . '/install.php';
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($create_sms_send);
		add_option('jbalvin_wp_sms_db_version', JBalvin_SMS_MMS_VERSION);
		// Delete notification new wp_version option
		delete_option('wp_notification_new_wp_version');
	}
	
	/**
	 * Adding new capability in the plugin
	 *
	 * @param  Not param
	 */
	public function add_cap() {
		// gets the administrator role
		$role = get_role( 'administrator' );
		$role->add_cap( 'jbalvin_wpsms_sendsms' );
		$role->add_cap( 'jbalvin_wpsms_outbox' );
	}
	
	/**
	 * Notification plugin with another system
	 *
	 * @param  Not param
	 */
	/**
	 * Include admin assets
	 *
	 * @param  Not param
	 */
	public function admin_assets() {
		wp_register_style('jbalvin-wpsms-admin', plugin_dir_url(__FILE__) . 'assets/css/admin.css', true, '1.1');
		wp_enqueue_style('jbalvin-wpsms-admin');
		
		wp_enqueue_style('chosen', plugin_dir_url(__FILE__) . 'assets/css/chosen.min.css', true, '1.2.0');
		wp_enqueue_script('chosen', plugin_dir_url(__FILE__) . 'assets/js/chosen.jquery.min.js', true, '1.2.0');
		
		if( get_option('wp_call_jquery') )
			wp_enqueue_script('jquery');
	}
	
	/**
	 * Activity plugin
	 *
	 * @param  Not param
	 */
	private function activity() {
		// Check exists require function
		if( !function_exists('wp_get_current_user') ) {
			include(ABSPATH . "wp-includes/pluggable.php");
		}
		// Add plugin caps to admin role
		if( is_admin() and is_super_admin() ) {
			$this->add_cap();
		}
	}
	
	/**
	 * Admin bar plugin
	 *
	 * @param  Not param
	 */
	public function adminbar() {
		global $wp_admin_bar;
		
		if(is_super_admin() && is_admin_bar_showing()) {
			$wp_admin_bar->add_menu(array(
				'id'		=>	'jbalvin-wp-send-sms',
				'parent'	=>	'new-content',
				'title'		=>	__('SMS', 'jbalvin-wp-sms'),
				'href'		=>	$this->admin_url.'/admin.php?page=jbalvin-wp-sms'
			));
		}
	}
	
	/**
	 * Dashboard glance plugin
	 *
	 * @param  Not param
	 */
	public function dashboard_glance() {
		echo "<li class='wpsms-credit-count'><a href='".$this->admin_url."admin.php?page=wp-sms-settings&tab=web-service'>".sprintf(__('%s SMS Credit', 'jbalvin-wp-sms'), get_option('wp_last_credit'))."</a></li>";
	}
	
	/**
	 * Admin newsletter
	 *
	 * @param  Not param
	 */
	
	/**
	 * Shortcodes plugin
	 *
	 * @param  Not param
	 */
	public function shortcode( $atts, $content = null ) {
		
	}
	
	/**
	 * Administrator menu
	 *
	 * @param  Not param
	 */
	public function menu() {
		add_menu_page(__('jbalvin SMS MMS', 'jbalvin-wp-sms'), __('jbalvin SMS MMS', 'jbalvin-wp-sms'), 'jbalvin_wpsms_sendsms', 'jbalvin-wp-sms', array(&$this, 'send_page'), 'dashicons-email-alt');
		add_submenu_page('jbalvin-wp-sms', __('Send SMS', 'jbalvin-wp-sms'), __('Send SMS', 'jbalvin-wp-sms'), 'jbalvin_wpsms_sendsms', 'jbalvin-wp-sms', array(&$this, 'send_page'));
		add_submenu_page('jbalvin-wp-sms', __('Outbox', 'jbalvin-wp-sms'), __('Outbox', 'jbalvin-wp-sms'), 'jbalvin_wpsms_outbox', 'wp-sms-outbox', array(&$this, 'outbox_page'));
	}
	
	/**
	 * Sending sms admin page
	 *
	 * @param  Not param
	 */
	public function send_page() {
		wp_enqueue_script('functions', plugin_dir_url(__FILE__) . 'assets/js/functions.js', true, '1.0');
		$recipient =array();
		$user_array = array();
		$country_code = "+57";
		/*
		$querystr = "SELECT {$this->tb_prefix}usermeta.meta_value as celular_number,{$this->tb_prefix}users.user_nicename
				FROM {$this->tb_prefix}users 
				LEFT JOIN {$this->tb_prefix}usermeta
				ON {$this->tb_prefix}users.ID = {$this->tb_prefix}usermeta.user_id WHERE {$this->tb_prefix}usermeta.meta_key = 'celular' AND CAST({$this->tb_prefix}usermeta.meta_value AS CHAR) != ''";
		$get_users_mobile =  $this->db->get_results($querystr);
		foreach ($get_users_mobile as $key => $userdetails) {
			$recipient['name'] =$userdetails->user_nicename;
			$recipient['celular_number'] = $userdetails->celular_number;
			$user_array[] = $recipient;
		} 
		*/
		$temp_user = array();
		$querystr = "SELECT * FROM {$this->tb_prefix}dhvc_form_entry_data";
		$get_users_mobile =  $this->db->get_results($querystr);
		foreach ($get_users_mobile as $key => $userdetails) {
		    $user_data = unserialize($userdetails->entry_data);  
			//$parsed = parse_url($urlStr);
			
			$user_celular_final = $user_data['celular'];
			$user_celular = $user_data['celular'];
			if(strpos($user_celular, $country_code) !== 0) {
			  $user_celular_final = $country_code.$user_celular;
			}
			
			if(!in_array($user_celular_final, $temp_user)){
				$recipient = array();    
				$recipient['name'] = $user_data['name'];
				$recipient['celular_number'] = trim($user_celular_final);
				$user_array[] = $recipient;	
				$temp_user[] = trim($user_celular_final);
			}
			
		}
		if(!empty($_POST)) {
			if(!empty($_POST['wp_get_message'])) {
				$File_url = "";
				if (!empty($_FILES['wp_get_attachment']['name'])){
					if(!function_exists('wp_handle_upload')){
						require_once(ABSPATH.'wp-admin/includes/admin.php');
						require_once(ABSPATH.'wp-admin/includes/file.php');
					}
					if(isset($_POST['message_type']) && strtolower($_POST['message_type']) == 'mms' ) {
						
				 		$name = $_FILES['wp_get_attachment']['name'];
						$file = wp_handle_upload($_FILES['wp_get_attachment'], array('test_form' => false));
						if(isset($_FILES['wp_get_attachment']['type'])){
							$type = $_FILES['wp_get_attachment']['type'];
							// Allow only JPG, GIF, PNG
							if(!preg_match('/(jpe?g|gif|png)$/i', $type)){
								echo '<div class="error notice is-dismissible below-h2"><p>'.__('Sorry, this file type is not permitted for security reasons. Only Allow jpg,jpeg,png,gif', 'jbalvin-wp-sms').'</p></div>';
							}
						}
						if(isset($_FILES['wp_get_attachment']['size'])){
							$size = $_FILES['wp_get_attachment']['size'];
							// Allow only Size will be less then 500kb
							if ($size > $this->max_file_size ){
								echo '<div class="error notice is-dismissible below-h2"><p>'.__('Sorry, File Size must be less then '.number_format($this->max_file_size / 1000, 2).'kb', 'jbalvin-wp-sms').'</p></div>';
							}
						}
						// Break out file info
						$name_parts = pathinfo($name);
						$name = trim(substr($name, 0, -(1 + strlen($name_parts['extension']))));
						$File_url = WP_SITEURL.$file['url'];
						$file = $file['file'];
						$title = $name;
						// Use image exif/iptc data for title if possible
						if($image_meta = @wp_read_image_metadata($file)){
							if(trim($image_meta['title']) && !is_numeric(sanitize_title($image_meta['title']))){
								$title = $image_meta['title'];
							}
						}
						// Construct the attachment array
						$attachment = array(
								'guid'           => $File_url,
								'post_mime_type' => $type,
								'post_title'     => $title,
								'post_content'     => ""
						);
						// This should never be set as it would then overwrite an existing attachment
						if(isset($attachment['ID'])){
							unset($attachment['ID']);
						}
						// Save the attachment metadata
						$attachment_id = wp_insert_attachment($attachment, $file);
						wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $file));	
					}
				}
				if(isset($_POST['message_type']) && strtolower($_POST['message_type']) == 'sms' ) {
					echo '<div class="updated notice is-dismissible below-h2"><p>' . __('SMS has been sent Sucessfully', 'jbalvin-wp-sms').'</p></div>';
				} else if(isset($_POST['message_type']) && strtolower($_POST['message_type']) == 'mms' ) {
					if(!empty($user_array)){
						$errorIds = array();
						$count = 0;
						$insert = $this->InsertToDB($this->mms_sender,$user_array,$_POST['wp_get_message'],strtolower($_POST['message_type']),$File_url);
						
						foreach ($user_array as $key => $details) {
							$celular_number = trim($details['celular_number']);
							try{
								   $send_mms = $this->mms->account->messages->create(
									    $celular_number,
								    array(
								        'from' => $this->mms_sender,
								        'body' => $_POST['wp_get_message'],
								        'mediaUrl' => array($File_url)
								    )
								);
							}
							catch (Exception $e)
							{  
							    $count++;
							    array_push($errorIds, $celular_number);
							}
						}
						if(empty($errorIds)){
							echo '<div class="updated notice is-dismissible below-h2"><p>' . __('MMS has been sent Sucessfully', 'jbalvin-wp-sms').'</p></div>';
						}else{
							echo '<div class="error notice is-dismissible below-h2"><p>' . __('MMS could not be sent to '.count($errorIds).'/'.count($user_array).'. Those numbers are following : '.implode(",",$errorIds),'jbalvin-wp-sms').'</p></div>';
						}
					}	
				}
			} else {
				echo '<div class="error notice is-dismissible below-h2"><p>' . __('Please enter a message', 'jbalvin-wp-sms').'</p></div>';
			}
		}
		include_once dirname( __FILE__ ) . "/includes/templates/send/send-sms.php";
	}
	
	/**
	 * 
	 */
	 public function InsertToDB($sender,$recipient,$message,$type,$file_url=NULL) {
	 	return $this->db->insert(
			$this->tb_prefix . "jbalvin_sms_send",
			array(
				'type'		=> strtolower($type),
				'sender'	=>	$sender,
				'message'	=>	$message,
				'mms_file'	=>  $file_url,
				'recipient'	=>	json_encode($recipient),
				'date'		=>	date('Y-m-d H:i:s' ,current_time('timestamp', 0))
			)
		);
	 }
	 
	/**
	 * Outbox sms admin page
	 *
	 * @param  Not param
	 */
	public function outbox_page() {
		include_once dirname( __FILE__ ) . '/includes/wp-sms-outbox.php';
		
		//Create an instance of our package class...
		$list_table = new JBalvin_WP_SMS_Outbox_List_Table();
		
		//Fetch, prepare, sort, and filter our data...
		$list_table->prepare_items();
		
		include_once dirname( __FILE__ ) . "/includes/templates/outbox/outbox.php";
	}
	 
}
