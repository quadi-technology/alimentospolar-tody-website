<?php
if (! function_exists ( 'dhvc_is_editor' )) {
	function dhvc_is_editor() {
		return (isset ( $_GET ['vc_action'] ) && $_GET ['vc_action'] === 'vc_inline') || (isset ( $_GET ['vc_inline'] ) || isset ( $_POST ['vc_inline'] ));
	}
}
if (! function_exists ( 'dhvc_is_inline' )) {
	function dhvc_is_inline() {
		return isset ( $_GET ['vceditor'] ) && $_GET ['vceditor'] === 'true';
	}
}
if (! function_exists ( 'dhvc_is_editable' )) {
	function dhvc_is_editable() {
		return isset ( $_GET ['vc_editable'] ) && $_GET ['vc_editable'] === 'true';
	}
}
if(!function_exists('dhvc_form_css_minify')){
	function dhvc_form_css_minify( $css ) {
		$css = preg_replace( '/\s+/', ' ', $css );
		$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
		$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
		$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
		$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
		$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
		return trim( $css );
	}
}
if(!function_exists('dhvc_form_strip_quote')){
	function dhvc_form_strip_quote( $text ) {
		$text = trim( $text );
	
		if ( preg_match( '/^"(.*)"$/', $text, $matches ) )
			$text = $matches[1];
		elseif ( preg_match( "/^'(.*)'$/", $text, $matches ) )
		$text = $matches[1];
	
		return $text;
	}
}

if(!function_exists('dhvc_form_additional_setting')){
	function dhvc_form_additional_setting( $name, $additional_settings, $max = 1  ) {
		$tmp_settings = (array) explode( "\n", $additional_settings );
	
		$count = 0;
		$values = array();
	
		foreach ( $tmp_settings as $setting ) {
			if ( preg_match('/^([a-zA-Z0-9_]+)[\t ]*:(.*)$/', $setting, $matches ) ) {
				if ( $matches[1] != $name )
					continue;
	
				if ( ! $max || $count < (int) $max ) {
					$values[] = trim( $matches[2] );
					$count += 1;
				}
			}
		}
	
		return $values;
	}
}
if(!function_exists('dhvc_form_is_xhr')){
	function dhvc_form_is_xhr() {
		if ( ! isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) )
			return false;
	
		return $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	}
}

function dhvc_get_post_meta($post_id='',$meta = '',$default=null){
	$post_id = empty($post_id) ? get_the_ID() : $post_id;
	if(empty($meta))
		return false;
	$value = get_post_meta($post_id,$meta, true);
	if($value !== '' && $value !== null && $value !== array() && $value !== false)
		return $value;
	return $default;
}

function dhvc_form_is_post_request() {
	return (bool) ( 'POST' === strtoupper( $_SERVER['REQUEST_METHOD'] ) );
}

function dhvc_form_get_user_ip($checkproxy = true){
	if ($checkproxy && isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != null) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else if ($checkproxy && isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != null) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		if (!empty($_SERVER['REMOTE_ADDR']))
			$ip = $_SERVER['REMOTE_ADDR'];
		else
			$ip = '';
	}
	return $ip;
}

function dhvc_form_get_current_url()
{
	$url = 'http';
	if (is_ssl()) {
		$url .= 's';
	}
	$url .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	return $url;
}

function dhvc_form_get_http_referer()
{
	if (isset($_SERVER['HTTP_REFERER'])) {
		return $_SERVER['HTTP_REFERER'];
	}
}

/**
 * Send mail, similar to PHP's mail
 * 
 * @param string|array $to          Array or comma-separated list of email addresses to send message.
 * @param string       $subject     Email subject
 * @param string       $message     Message contents
 * @param string|array $headers     Optional. Additional headers.
 * @param string|array $attachments Optional. Files to attach.
 * @return wp_mail
 */
function dhvc_form_email($to, $subject, $message, $headers = '', $attachments = array()){
	if(dhvc_form_get_option('email_method','default') == 'smtp'){
		$ret = dhvc_form_phpmailer($to, $subject, $message, $headers, $attachments);
	}else{
		$ret = wp_mail($to, $subject, $message, $headers, $attachments);
	}
	return $ret;
}
/**
 * Retrieve get PHPMailer object
 * @return PHPMailer
 */
function dhvc_form_phpmailer($to, $subject, $message, $headers = '', $attachments = array()){
	global $phpmailer;

	// (Re)create it, if it's gone missing
	if ( ! ( $phpmailer instanceof PHPMailer ) ) {
		require_once ABSPATH . WPINC . '/class-phpmailer.php';
		require_once ABSPATH . WPINC . '/class-smtp.php';
		$phpmailer = new PHPMailer( true );
	}
	$phpmailer->ClearAllRecipients();
	$phpmailer->ClearAttachments();
	$phpmailer->ClearCustomHeaders();
	$phpmailer->ClearReplyTos();
	$phpmailer->IsMail();
	$phpmailer->IsSMTP();
	$smtp_host = dhvc_form_get_option('smtp_host');
	$smtp_post = dhvc_form_get_option('smtp_post');
	$smtp_username = dhvc_form_get_option('smtp_username');
	$smtp_password = dhvc_form_get_option('smtp_password');
	$smtp_encryption = dhvc_form_get_option('smtp_encryption');
	if (!empty($smtp_host)) {
		$phpmailer->Host = $smtp_host;
	}
	
	if (!empty($smtp_post)) {
		$phpmailer->Port = $smtp_post;
	}
	
	if (!empty($smtp_username) && !empty($smtp_password)) {
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = $smtp_username;
		$phpmailer->Password = $smtp_password;
	}
	
	if (in_array($smtp_encryption, array('tls', 'ssl'))) {
		$phpmailer->SMTPSecure = $smtp_encryption;
	}
	

	$atts = apply_filters( 'dhvc_form_phpmailer', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) );

	if ( isset( $atts['to'] ) ) {
		$to = $atts['to'];
	}

	if ( isset( $atts['subject'] ) ) {
		$subject = $atts['subject'];
	}

	if ( isset( $atts['message'] ) ) {
		$message = $atts['message'];
	}

	if ( isset( $atts['headers'] ) ) {
		$headers = $atts['headers'];
	}

	if ( isset( $atts['attachments'] ) ) {
		$attachments = $atts['attachments'];
	}

	if ( ! is_array( $attachments ) ) {
		$attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );
	}

	// Headers
	$cc = $bcc = $reply_to = array();

	if ( empty( $headers ) ) {
		$headers = array();
	} else {
		if ( !is_array( $headers ) ) {
			// Explode the headers out, so this function can take both
			// string headers and an array of headers.
			$tempheaders = explode( "\n", str_replace( "\r\n", "\n", $headers ) );
		} else {
			$tempheaders = $headers;
		}
		$headers = array();

		// If it's actually got contents
		if ( !empty( $tempheaders ) ) {
			// Iterate through the raw headers
			foreach ( (array) $tempheaders as $header ) {
				if ( strpos($header, ':') === false ) {
					if ( false !== stripos( $header, 'boundary=' ) ) {
						$parts = preg_split('/boundary=/i', trim( $header ) );
						$boundary = trim( str_replace( array( "'", '"' ), '', $parts[1] ) );
					}
					continue;
				}
				// Explode them out
				list( $name, $content ) = explode( ':', trim( $header ), 2 );

				// Cleanup crew
				$name    = trim( $name    );
				$content = trim( $content );

				switch ( strtolower( $name ) ) {
					// Mainly for legacy -- process a From: header if it's there
					case 'from':
						$bracket_pos = strpos( $content, '<' );
						if ( $bracket_pos !== false ) {
							// Text before the bracketed email is the "From" name.
							if ( $bracket_pos > 0 ) {
								$from_name = substr( $content, 0, $bracket_pos - 1 );
								$from_name = str_replace( '"', '', $from_name );
								$from_name = trim( $from_name );
							}

							$from_email = substr( $content, $bracket_pos + 1 );
							$from_email = str_replace( '>', '', $from_email );
							$from_email = trim( $from_email );

						// Avoid setting an empty $from_email.
						} elseif ( '' !== trim( $content ) ) {
							$from_email = trim( $content );
						}
						break;
					case 'content-type':
						if ( strpos( $content, ';' ) !== false ) {
							list( $type, $charset_content ) = explode( ';', $content );
							$content_type = trim( $type );
							if ( false !== stripos( $charset_content, 'charset=' ) ) {
								$charset = trim( str_replace( array( 'charset=', '"' ), '', $charset_content ) );
							} elseif ( false !== stripos( $charset_content, 'boundary=' ) ) {
								$boundary = trim( str_replace( array( 'BOUNDARY=', 'boundary=', '"' ), '', $charset_content ) );
								$charset = '';
							}

						// Avoid setting an empty $content_type.
						} elseif ( '' !== trim( $content ) ) {
							$content_type = trim( $content );
						}
						break;
					case 'cc':
						$cc = array_merge( (array) $cc, explode( ',', $content ) );
						break;
					case 'bcc':
						$bcc = array_merge( (array) $bcc, explode( ',', $content ) );
						break;
					case 'reply-to':
						$reply_to = array_merge( (array) $reply_to, explode( ',', $content ) );
						break;
					default:
						// Add it to our grand headers array
						$headers[trim( $name )] = trim( $content );
						break;
				}
			}
		}
	}
	// From email and name
	// If we don't have a name from the input headers
	if ( !isset( $from_name ) )
		$from_name = 'WordPress';

	/* If we don't have an email from the input headers default to wordpress@$sitename
	 * Some hosts will block outgoing mail from this address if it doesn't exist but
	 * there's no easy alternative. Defaulting to admin_email might appear to be another
	 * option but some hosts may refuse to relay mail from an unknown domain. See
	 * https://core.trac.wordpress.org/ticket/5007.
	 */

	if ( !isset( $from_email ) ) {
		// Get the site domain and get rid of www.
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		$from_email = 'wordpress@' . $sitename;
	}

	$from_email = apply_filters( 'dhvc_form_phpmailer_from', $from_email );

	/**
	 * Filters the name to associate with the "from" email address.
	 *
	 * @since 2.3.0
	 *
	 * @param string $from_name Name associated with the "from" email address.
	 */
	$from_name = apply_filters( 'dhvc_form_phpmailer_mail_from_name', $from_name );

	$phpmailer->setFrom( $from_email, $from_name, false );

	// Set destination addresses
	if ( !is_array( $to ) )
		$to = explode( ',', $to );

	// Set mail's subject and body
	$phpmailer->Subject = $subject;
	$phpmailer->Body    = $message;

	// Use appropriate methods for handling addresses, rather than treating them as generic headers
	$address_headers = compact( 'to', 'cc', 'bcc', 'reply_to' );

	foreach ( $address_headers as $address_header => $addresses ) {
		if ( empty( $addresses ) ) {
			continue;
		}

		foreach ( (array) $addresses as $address ) {
			try {
				// Break $recipient into name and address parts if in the format "Foo <bar@baz.com>"
				$recipient_name = '';

				if ( preg_match( '/(.*)<(.+)>/', $address, $matches ) ) {
					if ( count( $matches ) == 3 ) {
						$recipient_name = $matches[1];
						$address        = $matches[2];
					}
				}

				switch ( $address_header ) {
					case 'to':
						$phpmailer->addAddress( $address, $recipient_name );
						break;
					case 'cc':
						$phpmailer->addCc( $address, $recipient_name );
						break;
					case 'bcc':
						$phpmailer->addBcc( $address, $recipient_name );
						break;
					case 'reply_to':
						$phpmailer->addReplyTo( $address, $recipient_name );
						break;
				}
			} catch ( phpmailerException $e ) {
				continue;
			}
		}
	}


	// Set Content-Type and charset
	// If we don't have a content-type from the input headers
	if ( !isset( $content_type ) )
		$content_type = 'text/plain';

	/**
	 * Filters the wp_mail() content type.
	 *
	 * @since 2.3.0
	 *
	 * @param string $content_type Default wp_mail() content type.
	 */
	$content_type = apply_filters( 'dhvc_form_phpmailer_content_type', $content_type );

	$phpmailer->ContentType = $content_type;

	// Set whether it's plaintext, depending on $content_type
	if ( 'text/html' == $content_type )
		$phpmailer->IsHTML( true );

	// If we don't have a charset from the input headers
	if ( !isset( $charset ) )
		$charset = get_bloginfo( 'charset' );

	// Set the content-type and charset

	/**
	 * Filters the default wp_mail() charset.
	 *
	 * @since 2.3.0
	 *
	 * @param string $charset Default email charset.
	 */
	$phpmailer->CharSet = apply_filters( 'dhvc_form_phpmailer_charset', $charset );

	// Set custom headers
	if ( !empty( $headers ) ) {
		foreach ( (array) $headers as $name => $content ) {
			$phpmailer->AddCustomHeader( sprintf( '%1$s: %2$s', $name, $content ) );
		}

		if ( false !== stripos( $content_type, 'multipart' ) && ! empty($boundary) )
			$phpmailer->AddCustomHeader( sprintf( "Content-Type: %s;\n\t boundary=\"%s\"", $content_type, $boundary ) );
	}

	if ( !empty( $attachments ) ) {
		foreach ( $attachments as $attachment ) {
			try {
				$phpmailer->AddAttachment($attachment);
			} catch ( phpmailerException $e ) {
				continue;
			}
		}
	}

	// Send!
	try {
		return $phpmailer->Send();
	} catch ( phpmailerException $e ) {
		return false;
	}
	
}

function dhvc_form_email_newline(){
	return apply_filters('dhvc_form_email_newline', "\n");
}

function dhvc_form_validate_email($email){
	return is_email($email);
}

function dhvc_form_get_actions(){
	$action= array('login','register','forgotten','mailchimp','radiobtn_redirect');
	if(defined('DHVC_FORM_SUPORT_WYSIJA')){
		$action[]='mailpoet';
	}
	if(defined('DHVC_FORM_SUPORT_MYMAIL')){
		$action[]='mymail';
	}
	return $action;
}

function dhvc_form_add_messages($form_id,$message='',$type='message'){
	$messages[$type] = $message;
	return update_post_meta($form_id, '_dhvc_form_messages', $messages);
}

function dhvc_form_clear_messages($form_id){
	return delete_post_meta($form_id, '_dhvc_form_messages');
}

function dhvc_form_the_messages($form_id){
	$messages = get_post_meta($form_id,'_dhvc_form_messages',true);
	$html = '';
	if(is_array($messages)){
		$html .= '<div class="dhvc-form-message-list">';
		foreach ($messages as $key=>$message){
			$html .= '<div class="'.$key.'">'.$message.'</div>';
		}
		$html .= '</div>';
	}elseif(is_string($messages)){
		$html .= '<span class="">'.$messages.'</span>';
	}
	dhvc_form_clear_messages($form_id);
	return $html;
}

function dhvc_form_get_mymail_subscribers_list($selected=array()){
	$Subscribers_list=array();
	if(defined('DHVC_FORM_SUPORT_MYMAIL')){
		$lists = mymail('lists')->get();
		if(!empty($lists)){
			foreach( $lists as $list){
				if(!empty($selected) && in_array($list->ID, $selected))
					$Subscribers_list[$list->ID] = $list->name;
				else
					$Subscribers_list[$list->ID] = $list->name;
			}
		}
	}
	return $Subscribers_list;
}

function dhvc_form_get_mailpoet_subscribers_list($selected=array()){
	$Subscribers_list=array();
	if(defined('DHVC_FORM_SUPORT_WYSIJA')){
		$model_list = WYSIJA::get('list','model');
		$lists = $model_list->get(array('name', 'list_id', 'is_public'), array('is_enabled' => 1));
		if(is_array($lists) && !empty($lists)){
			foreach ($lists as $list){
				if(!empty($selected) && in_array($list['list_id'], $selected))
					$Subscribers_list[$list['list_id']] = $list['name'];
				else
					$Subscribers_list[$list['list_id']] = $list['name'];
			}
		}
	}
	return $Subscribers_list;
}

function dhvc_form_format_value($value,$separator='<br/>'){
	$output = '';
	$separator = '<br/>';
	if (is_scalar($value)) {
		$output = nl2br(esc_html((string) $value));
	} else if (is_array($value)) {
		if(isset($value['path'])){
			$output = $value;
		}else{
			$arr = array();
			foreach ($value as $val) {
				if (is_scalar($val)) {
					$arr[] = nl2br(esc_html((string) $val));
				}
			}
			$output = join($separator, $arr);
		}
	}
	
	return $output;
}
function dhvc_form_upload_dir($dir){
	$dhvc_form_dir = '/dhvcform/{year}/{month}';
	$dhvc_form_dir = str_replace(array('{year}', '{month}'), array(date('Y'), date('m')), $dhvc_form_dir);
	return array(
		'path'   => $dir['basedir'] . $dhvc_form_dir,
		'url'    => $dir['baseurl'] . $dhvc_form_dir,
		'subdir' => $dhvc_form_dir,
	) + $dir;
}
function dhvc_form_upload($file){
	
	if(!function_exists('wp_handle_upload'))
		require_once(ABSPATH.'wp-admin/includes/file.php');
	
	add_filter( 'upload_dir', 'dhvc_form_upload_dir' );
	$upload = wp_handle_upload($file, array('test_form'=>false));
	remove_filter( 'upload_dir', 'dhvc_form_upload_dir' );
	if(isset($upload['error'])){
		return $upload['error'];
	}
	$r = array(
		'path' => str_replace(dhvc_form_get_upload_dir(), '', $upload['file']),
		'fullpath' => $upload['file'],
		'url'=>$upload['url'],
		'filename' => basename($upload['file']),
	);
	return $r;
}

function dhvc_form_gen_control_id(){
	global $post;
	$chars = '0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	$token = '';
	$id = time();
	for ($i = 0; $i < 32; ++$i)
	{
		$token .= $chars[(rand(0, $max))];
	}
	return substr(md5($token.$id),0,8);
}

function dhvc_form_has_submit_shortcode($form_id){
	$form_control = get_post_meta($form_id,'_form_control',true);
	if($form_control){
		$form_control_arr = json_decode($form_control);
		if(!empty($form_control_arr) && is_array($form_control_arr)){
			foreach ($form_control_arr as $k=>$control){
				if(!property_exists($control,'tag')){
					continue;
				}
				if($control->tag == 'dhvc_form_submit_button')
					return true;
			}
		}
	}
	return false;
}

function dhvc_form_has_shortcode( $content, $tag){
	global $shortcode_tags;
	return array_key_exists( $tag, $shortcode_tags );
	
	if ( false === strpos( $content, '[' ) ) {
		return false;
	}
	
	if ( array_key_exists($tag,$shortcode_tags)) {
		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) )
			return false;
	
		foreach ( $matches as $shortcode ) {
			if ( $tag === $shortcode[2] )
				return true;
		}
	}
	return false;
}


function dhvc_form_get_option($id,$default=null){
	global $dhvc_form_options;
	
	if ( empty( $dhvc_form_options ) ) {
		$dhvc_form_options = get_option('dhvc_form');
	}
	$value = $default;
	if (isset($dhvc_form_options[$id])) {
		$value =  $dhvc_form_options[$id];
	}
	return apply_filters('dhvc_form_get_option', $value, $id);
}


function dhvc_form_add_js_declaration( $code ) {
	global $dhvc_form_js_declaration;

	if ( empty( $dhvc_form_js_declaration ) ) {
		$dhvc_form_js_declaration = '';
	}

	$dhvc_form_js_declaration .= "\n" . $code . "\n";
}

function dhvc_form_font_awesome(){
	$font_awesome =  array(
			__('None','dhvc-form') =>'',
			'fa fa-adjust' => '\f042',
			'fa fa-adn' => '\f170',
			'fa fa-align-center' => '\f037',
			'fa fa-align-justify' => '\f039',
			'fa fa-align-left' => '\f036',
			'fa fa-align-right' => '\f038',
			'fa fa-ambulance' => '\f0f9',
			'fa fa-anchor' => '\f13d',
			'fa fa-android' => '\f17b',
			'fa fa-angle-double-down' => '\f103',
			'fa fa-angle-double-left' => '\f100',
			'fa fa-angle-double-right' => '\f101',
			'fa fa-angle-double-up' => '\f102',
			'fa fa-angle-down' => '\f107',
			'fa fa-angle-left' => '\f104',
			'fa fa-angle-right' => '\f105',
			'fa fa-angle-up' => '\f106',
			'fa fa-apple' => '\f179',
			'fa fa-archive' => '\f187',
			'fa fa-arrow-circle-down' => '\f0ab',
			'fa fa-arrow-circle-left' => '\f0a8',
			'fa fa-arrow-circle-o-down' => '\f01a',
			'fa fa-arrow-circle-o-left' => '\f190',
			'fa fa-arrow-circle-o-right' => '\f18e',
			'fa fa-arrow-circle-o-up' => '\f01b',
			'fa fa-arrow-circle-right' => '\f0a9',
			'fa fa-arrow-circle-up' => '\f0aa',
			'fa fa-arrow-down' => '\f063',
			'fa fa-arrow-left' => '\f060',
			'fa fa-arrow-right' => '\f061',
			'fa fa-arrow-up' => '\f062',
			'fa fa-arrows' => '\f047',
			'fa fa-arrows-alt' => '\f0b2',
			'fa fa-arrows-h' => '\f07e',
			'fa fa-arrows-v' => '\f07d',
			'fa fa-asterisk' => '\f069',
			'fa fa-backward' => '\f04a',
			'fa fa-ban' => '\f05e',
			'fa fa-bar-chart-o' => '\f080',
			'fa fa-barcode' => '\f02a',
			'fa fa-bars' => '\f0c9',
			'fa fa-beer' => '\f0fc',
			'fa fa-behance' => '\f1b4',
			'fa fa-behance-square' => '\f1b5',
			'fa fa-bell' => '\f0f3',
			'fa fa-bell-o' => '\f0a2',
			'fa fa-bitbucket' => '\f171',
			'fa fa-bitbucket-square' => '\f172',
			'fa fa-bold' => '\f032',
			'fa fa-bolt' => '\f0e7',
			'fa fa-bomb' => '\f1e2',
			'fa fa-book' => '\f02d',
			'fa fa-bookmark' => '\f02e',
			'fa fa-bookmark-o' => '\f097',
			'fa fa-briefcase' => '\f0b1',
			'fa fa-btc' => '\f15a',
			'fa fa-bug' => '\f188',
			'fa fa-building' => '\f1ad',
			'fa fa-building-o' => '\f0f7',
			'fa fa-bullhorn' => '\f0a1',
			'fa fa-bullseye' => '\f140',
			'fa fa-calendar' => '\f073',
			'fa fa-calendar-o' => '\f133',
			'fa fa-camera' => '\f030',
			'fa fa-camera-retro' => '\f083',
			'fa fa-car' => '\f1b9',
			'fa fa-caret-down' => '\f0d7',
			'fa fa-caret-left' => '\f0d9',
			'fa fa-caret-right' => '\f0da',
			'fa fa-caret-square-o-down' => '\f150',
			'fa fa-caret-square-o-left' => '\f191',
			'fa fa-caret-square-o-right' => '\f152',
			'fa fa-caret-square-o-up' => '\f151',
			'fa fa-caret-up' => '\f0d8',
			'fa fa-certificate' => '\f0a3',
			'fa fa-chain-broken' => '\f127',
			'fa fa-check' => '\f00c',
			'fa fa-check-circle' => '\f058',
			'fa fa-check-circle-o' => '\f05d',
			'fa fa-check-square' => '\f14a',
			'fa fa-check-square-o' => '\f046',
			'fa fa-chevron-circle-down' => '\f13a',
			'fa fa-chevron-circle-left' => '\f137',
			'fa fa-chevron-circle-right' => '\f138',
			'fa fa-chevron-circle-up' => '\f139',
			'fa fa-chevron-down' => '\f078',
			'fa fa-chevron-left' => '\f053',
			'fa fa-chevron-right' => '\f054',
			'fa fa-chevron-up' => '\f077',
			'fa fa-child' => '\f1ae',
			'fa fa-circle' => '\f111',
			'fa fa-circle-o' => '\f10c',
			'fa fa-circle-o-notch' => '\f1ce',
			'fa fa-circle-thin' => '\f1db',
			'fa fa-clipboard' => '\f0ea',
			'fa fa-clock-o' => '\f017',
			'fa fa-cloud' => '\f0c2',
			'fa fa-cloud-download' => '\f0ed',
			'fa fa-cloud-upload' => '\f0ee',
			'fa fa-code' => '\f121',
			'fa fa-code-fork' => '\f126',
			'fa fa-codepen' => '\f1cb',
			'fa fa-coffee' => '\f0f4',
			'fa fa-cog' => '\f013',
			'fa fa-cogs' => '\f085',
			'fa fa-columns' => '\f0db',
			'fa fa-comment' => '\f075',
			'fa fa-comment-o' => '\f0e5',
			'fa fa-comments' => '\f086',
			'fa fa-comments-o' => '\f0e6',
			'fa fa-compass' => '\f14e',
			'fa fa-compress' => '\f066',
			'fa fa-credit-card' => '\f09d',
			'fa fa-crop' => '\f125',
			'fa fa-crosshairs' => '\f05b',
			'fa fa-css3' => '\f13c',
			'fa fa-cube' => '\f1b2',
			'fa fa-cubes' => '\f1b3',
			'fa fa-cutlery' => '\f0f5',
			'fa fa-database' => '\f1c0',
			'fa fa-delicious' => '\f1a5',
			'fa fa-desktop' => '\f108',
			'fa fa-deviantart' => '\f1bd',
			'fa fa-digg' => '\f1a6',
			'fa fa-dot-circle-o' => '\f192',
			'fa fa-download' => '\f019',
			'fa fa-dribbble' => '\f17d',
			'fa fa-dropbox' => '\f16b',
			'fa fa-drupal' => '\f1a9',
			'fa fa-eject' => '\f052',
			'fa fa-ellipsis-h' => '\f141',
			'fa fa-ellipsis-v' => '\f142',
			'fa fa-empire' => '\f1d1',
			'fa fa-envelope' => '\f0e0',
			'fa fa-envelope-o' => '\f003',
			'fa fa-envelope-square' => '\f199',
			'fa fa-eraser' => '\f12d',
			'fa fa-eur' => '\f153',
			'fa fa-exchange' => '\f0ec',
			'fa fa-exclamation' => '\f12a',
			'fa fa-exclamation-circle' => '\f06a',
			'fa fa-exclamation-triangle' => '\f071',
			'fa fa-expand' => '\f065',
			'fa fa-external-link' => '\f08e',
			'fa fa-external-link-square' => '\f14c',
			'fa fa-eye' => '\f06e',
			'fa fa-eye-slash' => '\f070',
			'fa fa-facebook' => '\f09a',
			'fa fa-facebook-square' => '\f082',
			'fa fa-fast-backward' => '\f049',
			'fa fa-fast-forward' => '\f050',
			'fa fa-fax' => '\f1ac',
			'fa fa-female' => '\f182',
			'fa fa-fighter-jet' => '\f0fb',
			'fa fa-file' => '\f15b',
			'fa fa-file-archive-o' => '\f1c6',
			'fa fa-file-audio-o' => '\f1c7',
			'fa fa-file-code-o' => '\f1c9',
			'fa fa-file-excel-o' => '\f1c3',
			'fa fa-file-image-o' => '\f1c5',
			'fa fa-file-o' => '\f016',
			'fa fa-file-pdf-o' => '\f1c1',
			'fa fa-file-powerpoint-o' => '\f1c4',
			'fa fa-file-text' => '\f15c',
			'fa fa-file-text-o' => '\f0f6',
			'fa fa-file-video-o' => '\f1c8',
			'fa fa-file-word-o' => '\f1c2',
			'fa fa-files-o' => '\f0c5',
			'fa fa-film' => '\f008',
			'fa fa-filter' => '\f0b0',
			'fa fa-fire' => '\f06d',
			'fa fa-fire-extinguisher' => '\f134',
			'fa fa-flag' => '\f024',
			'fa fa-flag-checkered' => '\f11e',
			'fa fa-flag-o' => '\f11d',
			'fa fa-flask' => '\f0c3',
			'fa fa-flickr' => '\f16e',
			'fa fa-floppy-o' => '\f0c7',
			'fa fa-folder' => '\f07b',
			'fa fa-folder-o' => '\f114',
			'fa fa-folder-open' => '\f07c',
			'fa fa-folder-open-o' => '\f115',
			'fa fa-font' => '\f031',
			'fa fa-forward' => '\f04e',
			'fa fa-foursquare' => '\f180',
			'fa fa-frown-o' => '\f119',
			'fa fa-gamepad' => '\f11b',
			'fa fa-gavel' => '\f0e3',
			'fa fa-gbp' => '\f154',
			'fa fa-gift' => '\f06b',
			'fa fa-git' => '\f1d3',
			'fa fa-git-square' => '\f1d2',
			'fa fa-github' => '\f09b',
			'fa fa-github-alt' => '\f113',
			'fa fa-github-square' => '\f092',
			'fa fa-gittip' => '\f184',
			'fa fa-glass' => '\f000',
			'fa fa-globe' => '\f0ac',
			'fa fa-google' => '\f1a0',
			'fa fa-google-plus' => '\f0d5',
			'fa fa-google-plus-square' => '\f0d4',
			'fa fa-graduation-cap' => '\f19d',
			'fa fa-h-square' => '\f0fd',
			'fa fa-hacker-news' => '\f1d4',
			'fa fa-hand-o-down' => '\f0a7',
			'fa fa-hand-o-left' => '\f0a5',
			'fa fa-hand-o-right' => '\f0a4',
			'fa fa-hand-o-up' => '\f0a6',
			'fa fa-hdd-o' => '\f0a0',
			'fa fa-header' => '\f1dc',
			'fa fa-headphones' => '\f025',
			'fa fa-heart' => '\f004',
			'fa fa-heart-o' => '\f08a',
			'fa fa-history' => '\f1da',
			'fa fa-home' => '\f015',
			'fa fa-hospital-o' => '\f0f8',
			'fa fa-html5' => '\f13b',
			'fa fa-inbox' => '\f01c',
			'fa fa-indent' => '\f03c',
			'fa fa-info' => '\f129',
			'fa fa-info-circle' => '\f05a',
			'fa fa-inr' => '\f156',
			'fa fa-instagram' => '\f16d',
			'fa fa-italic' => '\f033',
			'fa fa-joomla' => '\f1aa',
			'fa fa-jpy' => '\f157',
			'fa fa-jsfiddle' => '\f1cc',
			'fa fa-key' => '\f084',
			'fa fa-keyboard-o' => '\f11c',
			'fa fa-krw' => '\f159',
			'fa fa-language' => '\f1ab',
			'fa fa-laptop' => '\f109',
			'fa fa-leaf' => '\f06c',
			'fa fa-lemon-o' => '\f094',
			'fa fa-level-down' => '\f149',
			'fa fa-level-up' => '\f148',
			'fa fa-life-ring' => '\f1cd',
			'fa fa-lightbulb-o' => '\f0eb',
			'fa fa-link' => '\f0c1',
			'fa fa-linkedin' => '\f0e1',
			'fa fa-linkedin-square' => '\f08c',
			'fa fa-linux' => '\f17c',
			'fa fa-list' => '\f03a',
			'fa fa-list-alt' => '\f022',
			'fa fa-list-ol' => '\f0cb',
			'fa fa-list-ul' => '\f0ca',
			'fa fa-location-arrow' => '\f124',
			'fa fa-lock' => '\f023',
			'fa fa-long-arrow-down' => '\f175',
			'fa fa-long-arrow-left' => '\f177',
			'fa fa-long-arrow-right' => '\f178',
			'fa fa-long-arrow-up' => '\f176',
			'fa fa-magic' => '\f0d0',
			'fa fa-magnet' => '\f076',
			'fa fa-male' => '\f183',
			'fa fa-map-marker' => '\f041',
			'fa fa-maxcdn' => '\f136',
			'fa fa-medkit' => '\f0fa',
			'fa fa-meh-o' => '\f11a',
			'fa fa-microphone' => '\f130',
			'fa fa-microphone-slash' => '\f131',
			'fa fa-minus' => '\f068',
			'fa fa-minus-circle' => '\f056',
			'fa fa-minus-square' => '\f146',
			'fa fa-minus-square-o' => '\f147',
			'fa fa-mobile' => '\f10b',
			'fa fa-money' => '\f0d6',
			'fa fa-moon-o' => '\f186',
			'fa fa-music' => '\f001',
			'fa fa-openid' => '\f19b',
			'fa fa-outdent' => '\f03b',
			'fa fa-pagelines' => '\f18c',
			'fa fa-paper-plane' => '\f1d8',
			'fa fa-paper-plane-o' => '\f1d9',
			'fa fa-paperclip' => '\f0c6',
			'fa fa-paragraph' => '\f1dd',
			'fa fa-pause' => '\f04c',
			'fa fa-paw' => '\f1b0',
			'fa fa-pencil' => '\f040',
			'fa fa-pencil-square' => '\f14b',
			'fa fa-pencil-square-o' => '\f044',
			'fa fa-phone' => '\f095',
			'fa fa-phone-square' => '\f098',
			'fa fa-picture-o' => '\f03e',
			'fa fa-pied-piper' => '\f1a7',
			'fa fa-pied-piper-alt' => '\f1a8',
			'fa fa-pinterest' => '\f0d2',
			'fa fa-pinterest-square' => '\f0d3',
			'fa fa-plane' => '\f072',
			'fa fa-play' => '\f04b',
			'fa fa-play-circle' => '\f144',
			'fa fa-play-circle-o' => '\f01d',
			'fa fa-plus' => '\f067',
			'fa fa-plus-circle' => '\f055',
			'fa fa-plus-square' => '\f0fe',
			'fa fa-plus-square-o' => '\f196',
			'fa fa-power-off' => '\f011',
			'fa fa-print' => '\f02f',
			'fa fa-puzzle-piece' => '\f12e',
			'fa fa-qq' => '\f1d6',
			'fa fa-qrcode' => '\f029',
			'fa fa-question' => '\f128',
			'fa fa-question-circle' => '\f059',
			'fa fa-quote-left' => '\f10d',
			'fa fa-quote-right' => '\f10e',
			'fa fa-random' => '\f074',
			'fa fa-rebel' => '\f1d0',
			'fa fa-recycle' => '\f1b8',
			'fa fa-reddit' => '\f1a1',
			'fa fa-reddit-square' => '\f1a2',
			'fa fa-refresh' => '\f021',
			'fa fa-renren' => '\f18b',
			'fa fa-repeat' => '\f01e',
			'fa fa-reply' => '\f112',
			'fa fa-reply-all' => '\f122',
			'fa fa-retweet' => '\f079',
			'fa fa-road' => '\f018',
			'fa fa-rocket' => '\f135',
			'fa fa-rss' => '\f09e',
			'fa fa-rss-square' => '\f143',
			'fa fa-rub' => '\f158',
			'fa fa-scissors' => '\f0c4',
			'fa fa-search' => '\f002',
			'fa fa-search-minus' => '\f010',
			'fa fa-search-plus' => '\f00e',
			'fa fa-share' => '\f064',
			'fa fa-share-alt' => '\f1e0',
			'fa fa-share-alt-square' => '\f1e1',
			'fa fa-share-square' => '\f14d',
			'fa fa-share-square-o' => '\f045',
			'fa fa-shield' => '\f132',
			'fa fa-shopping-cart' => '\f07a',
			'fa fa-sign-in' => '\f090',
			'fa fa-sign-out' => '\f08b',
			'fa fa-signal' => '\f012',
			'fa fa-sitemap' => '\f0e8',
			'fa fa-skype' => '\f17e',
			'fa fa-slack' => '\f198',
			'fa fa-sliders' => '\f1de',
			'fa fa-smile-o' => '\f118',
			'fa fa-sort' => '\f0dc',
			'fa fa-sort-alpha-asc' => '\f15d',
			'fa fa-sort-alpha-desc' => '\f15e',
			'fa fa-sort-amount-asc' => '\f160',
			'fa fa-sort-amount-desc' => '\f161',
			'fa fa-sort-asc' => '\f0de',
			'fa fa-sort-desc' => '\f0dd',
			'fa fa-sort-numeric-asc' => '\f162',
			'fa fa-sort-numeric-desc' => '\f163',
			'fa fa-soundcloud' => '\f1be',
			'fa fa-space-shuttle' => '\f197',
			'fa fa-spinner' => '\f110',
			'fa fa-spoon' => '\f1b1',
			'fa fa-spotify' => '\f1bc',
			'fa fa-square' => '\f0c8',
			'fa fa-square-o' => '\f096',
			'fa fa-stack-exchange' => '\f18d',
			'fa fa-stack-overflow' => '\f16c',
			'fa fa-star' => '\f005',
			'fa fa-star-half' => '\f089',
			'fa fa-star-half-o' => '\f123',
			'fa fa-star-o' => '\f006',
			'fa fa-steam' => '\f1b6',
			'fa fa-steam-square' => '\f1b7',
			'fa fa-step-backward' => '\f048',
			'fa fa-step-forward' => '\f051',
			'fa fa-stethoscope' => '\f0f1',
			'fa fa-stop' => '\f04d',
			'fa fa-strikethrough' => '\f0cc',
			'fa fa-stumbleupon' => '\f1a4',
			'fa fa-stumbleupon-circle' => '\f1a3',
			'fa fa-subscript' => '\f12c',
			'fa fa-suitcase' => '\f0f2',
			'fa fa-sun-o' => '\f185',
			'fa fa-superscript' => '\f12b',
			'fa fa-table' => '\f0ce',
			'fa fa-tablet' => '\f10a',
			'fa fa-tachometer' => '\f0e4',
			'fa fa-tag' => '\f02b',
			'fa fa-tags' => '\f02c',
			'fa fa-tasks' => '\f0ae',
			'fa fa-taxi' => '\f1ba',
			'fa fa-tencent-weibo' => '\f1d5',
			'fa fa-terminal' => '\f120',
			'fa fa-text-height' => '\f034',
			'fa fa-text-width' => '\f035',
			'fa fa-th' => '\f00a',
			'fa fa-th-large' => '\f009',
			'fa fa-th-list' => '\f00b',
			'fa fa-thumb-tack' => '\f08d',
			'fa fa-thumbs-down' => '\f165',
			'fa fa-thumbs-o-down' => '\f088',
			'fa fa-thumbs-o-up' => '\f087',
			'fa fa-thumbs-up' => '\f164',
			'fa fa-ticket' => '\f145',
			'fa fa-times' => '\f00d',
			'fa fa-times-circle' => '\f057',
			'fa fa-times-circle-o' => '\f05c',
			'fa fa-tint' => '\f043',
			'fa fa-trash-o' => '\f014',
			'fa fa-tree' => '\f1bb',
			'fa fa-trello' => '\f181',
			'fa fa-trophy' => '\f091',
			'fa fa-truck' => '\f0d1',
			'fa fa-try' => '\f195',
			'fa fa-tumblr' => '\f173',
			'fa fa-tumblr-square' => '\f174',
			'fa fa-twitter' => '\f099',
			'fa fa-twitter-square' => '\f081',
			'fa fa-umbrella' => '\f0e9',
			'fa fa-underline' => '\f0cd',
			'fa fa-undo' => '\f0e2',
			'fa fa-university' => '\f19c',
			'fa fa-unlock' => '\f09c',
			'fa fa-unlock-alt' => '\f13e',
			'fa fa-upload' => '\f093',
			'fa fa-usd' => '\f155',
			'fa fa-user' => '\f007',
			'fa fa-user-md' => '\f0f0',
			'fa fa-users' => '\f0c0',
			'fa fa-video-camera' => '\f03d',
			'fa fa-vimeo-square' => '\f194',
			'fa fa-vine' => '\f1ca',
			'fa fa-vk' => '\f189',
			'fa fa-volume-down' => '\f027',
			'fa fa-volume-off' => '\f026',
			'fa fa-volume-up' => '\f028',
			'fa fa-weibo' => '\f18a',
			'fa fa-weixin' => '\f1d7',
			'fa fa-wheelchair' => '\f193',
			'fa fa-windows' => '\f17a',
			'fa fa-wordpress' => '\f19a',
			'fa fa-wrench' => '\f0ad',
			'fa fa-xing' => '\f168',
			'fa fa-xing-square' => '\f169',
			'fa fa-yahoo' => '\f19e',
			'fa fa-youtube' => '\f167',
			'fa fa-youtube-play' => '\f16a',
			'fa fa-youtube-square' => '\f166',
	);
	
	$options = array();
	foreach ($font_awesome as $key=>$content){
		$text_val = ucfirst(str_replace('fa fa-', '', $key));
		$options[$text_val] = $key;
	}
	return apply_filters('dhvc_form_font_awesome',$options);
}

function dhvc_form_get_recaptcha_lang(){
	$lang = array(__('English','dhvc-form')=>'en',__('Portuguese','dhvc-form')=> 'pt',__('French','dhvc-form')=>'fr',__('German','dhvc-form')=>'de',__('Dutch','dhvc-form')=> 'nl',__('Russian','dhvc-form')=>'ru',__('Spanish','dhvc-form')=>'es',__('Turkish','dhvc-form')=>'tr');
	return $lang;
}

function dhvc_form_get_upload_dir(){
	$uploadDir = wp_upload_dir();
	if ($uploadDir['error'] == false) {
		return $uploadDir['basedir'];
	}
	
	return false;
}

function dhvc_form_get_upload_url(){
	$uploadDir = wp_upload_dir();
	return $uploadDir['baseurl'];
}

function dhvc_form_get_forms(){
	$options = array();
	
	return $options;
}

function dhvc_form_get_variables(){
	return apply_filters('dhvc_form_variables', array(
		__('Site URL','dhvc-form')=>"[site_url]",
		__('User IP','dhvc-form')=>"[ip_address]",
		__('User display name','dhvc-form')=>"[user_display_name]",
		__('User email','dhvc-form')=>"[user_email]",
		__('User login','dhvc-form')=>"[user_login]",
		__('Form URL','dhvc-form')=>"[form_url]",
		__('Form ID','dhvc-form')=>"[form_id]",
		__('Form Title','dhvc-form')=>"[form_title]",
		__('Post/page ID','dhvc-form')=>"[post_id]",
		__('Entrie ID','dhvc-form')=>"[entrie_id]",
		__('Post/page title','dhvc-form')=>"[post_title]",
		__('Datetime submitted','dhvc-form')=>"[submitted]",
	));
}

function dhvc_form_get_validation(){
	return apply_filters('dhvc_form_validation', array(
			__('Date (only date)','dhvc-form')=>'dhvc-form-validate-date',
			__('Number (only number)','dhvc-form')=>'dhvc-form-validate-number',
			__('Number or brackets (), dashes – and plus +','dhvc-form')=>'dhvc-form-validate-number2',
			__('Unique','dhvc-form')=>'dhvc-form-validate-unique2',
			__('Unique with fields status, waiting,active or inactive','dhvc-form')=>'dhvc-form-validate-unique-with-status',
			__('Digits (only number, avoid spaces or other characters such as dots or commas)','dhvc-form')=>'dhvc-form-validate-digits',
			__('Alpha (only a-z or A-Z)','dhvc-form')=>'dhvc-form-validate-alpha',
			__('Alphanum (only a-z or A-Z or 0-9)','dhvc-form')=>'dhvc-form-validate-alphanum',
			__('Url (only URL. Protocol is required: http://, https:// or ftp://)','dhvc-form')=>'dhvc-form-validate-url',
			__('Zip (example 90602 or 90602-1234)','dhvc-form')=>'dhvc-form-validate-zip',
			__('Fax (example (123) 456-7890 or 123-456-7890)','dhvc-form')=>'dhvc-form-validate-fax',
			
	));
}

function dhvc_form_print_js_declaration() {
	global $dhvc_form_js_declaration;
	if ( ! empty( $dhvc_form_js_declaration ) ) {
		echo "<script type=\"text/javascript\">\n";
		// Sanitize
		$dhvc_form_js_declaration = wp_check_invalid_utf8( $dhvc_form_js_declaration );
		$dhvc_form_js_declaration = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $dhvc_form_js_declaration );
		$dhvc_form_js_declaration = str_replace( "\r", '', $dhvc_form_js_declaration );
		echo $dhvc_form_js_declaration . "\n</script>\n";
		unset( $dhvc_form_js_declaration );
	}
	return false;
}



function dhvc_form_get_pages($none_field=false){
	$pages = get_pages();
	$options = array();
	
	if($none_field)
		$options['']=__('Select a page...','dhvc-form');

	if(!empty($pages)){
		foreach ($pages as $page){
			$options[$page->ID] = $page->post_title;
		}
	}
	return $options;
}

function dhvc_form_get_posts(){
	$posts = get_posts(array('numberposts'=>-1));
	$options = array();
	if(!empty($posts)){
		foreach ($posts as $post){
			$options[$post->ID] = $post->post_title;
		}
	}
	return $options;
}

function dhvc_form_translate_variable($content,$data=array(),$html = false){
	global $dhvc_form_posted_data;
	if(empty($dhvc_form_posted_data))
		$dhvc_form_posted_data = $data;
	if(!class_exists('DHVCFormEmail'))
		require_once (DHVC_FORM_DIR.'/includes/email.php');
	
	$email = new DHVCFormEmail($content,$dhvc_form_posted_data,$html);
	$content = $email->replace_mail_tags();
	$content = apply_filters('dhvc_form_translate_variable', $content, $data);
	return $content;
}

function encryptor($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    //pls set your unique hashing key
    $secret_key = 'muni';
    $secret_iv = 'muni123';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
    	//decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

