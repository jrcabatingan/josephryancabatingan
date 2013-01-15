<?php 
/**
 * Taken originally from wp-login.php
 */
 
// Redirect to https login if forced to use SSL
if ( force_ssl_admin() && ! is_ssl() ) {
	if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
		wp_redirect( set_url_scheme( $_SERVER['REQUEST_URI'], 'https' ) );
		exit();
	} else {
		wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		exit();
	}
}

//
// Main
// Starting point. I did not include the header() because we are sending it to the page template.

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login'; var_dump($action);

#TODO Replace this with our (nutshell) Error Class API
$errors = new WP_Error();

if ( isset($_GET['key']) )
	$action = 'resetpass';
	
// validate action so as to default to the login screen
if ( !in_array( $action, array( 'postpass', 'logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'register', 'login' ), true ) && false === has_filter( 'login_form_' . $action ) )
	$action = 'login';

#TODO: Not sure if this has value
/**
 * Sets the headers to prevent caching for the different browsers. 
 */
//nocache_headers();

//Set a cookie now to see if they are supported by the browser.
setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
if ( SITECOOKIEPATH != COOKIEPATH )
	setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);
	
$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);

switch ($action) {
	case 'login' :
	default:
	
	$secure_cookie = '';
	$interim_login = isset($_REQUEST['interim-login']);
	$customize_login = isset( $_REQUEST['customize-login'] );
	if ( $customize_login )
		wp_enqueue_script( 'customize-base' );

	// If the user wants ssl but the session is not ssl, force a secure cookie.
	if ( !empty($_POST['log']) && !force_ssl_admin() ) {
		$user_name = sanitize_user($_POST['log']);
		if ( $user = get_user_by('login', $user_name) ) {
			if ( get_user_option('use_ssl', $user->ID) ) {
				$secure_cookie = true;
				force_ssl_admin(true);
			}
		}
	}

	if ( isset( $_REQUEST['redirect_to'] ) ) {
		$redirect_to = $_REQUEST['redirect_to'];
		// Redirect to https if user wants ssl
		if ( $secure_cookie && false !== strpos($redirect_to, 'wp-admin') )
			$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
	} else {
		$redirect_to = admin_url();
	}

	$reauth = empty($_REQUEST['reauth']) ? false : true;

	// If the user was redirected to a secure login form from a non-secure admin page, and secure login is required but secure admin is not, then don't use a secure
	// cookie and redirect back to the referring non-secure admin page. This allows logins to always be POSTed over SSL while allowing the user to choose visiting
	// the admin via http or https.
	if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
		$secure_cookie = false;

	$user = wp_signon('', $secure_cookie);

	$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user);
	
	if ( !is_wp_error($user) && !$reauth ) {
		if ( $interim_login ) {
			$message = '<p class="message">' . __('You have logged in successfully.') . '</p>';
			login_header( '', $message );
		}
		
		if ( ( empty( $redirect_to ) || $redirect_to == 'wp-admin/' || $redirect_to == admin_url() ) ) {
			// If the user doesn't belong to a blog, send them to user admin. If the user can't edit posts, send them to their profile.
			if ( is_multisite() && !get_active_blog_for_user($user->ID) && !is_super_admin( $user->ID ) )
				$redirect_to = user_admin_url();
			elseif ( is_multisite() && !$user->has_cap('read') )
				$redirect_to = get_dashboard_url( $user->ID );
			elseif ( !$user->has_cap('edit_posts') )
				$redirect_to = admin_url('profile.php');
		}
		wp_safe_redirect($redirect_to);
		exit();		
	}
	
	$errors = $user;
	// Clear errors if loggedout is set.
	if ( !empty($_GET['loggedout']) || $reauth )
		$errors = new WP_Error();
		
	// If cookies are disabled we can't log in even with a valid user+pass
	if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
		$errors->add('test_cookie', __("<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress."));
		
	// Some parts of this script use the main login form to display a message
	if		( isset($_GET['loggedout']) && true == $_GET['loggedout'] )
		$errors->add('loggedout', __('You are now logged out.'), 'message');
	elseif	( isset($_GET['registration']) && 'disabled' == $_GET['registration'] )
		$errors->add('registerdisabled', __('User registration is currently not allowed.'));
	elseif	( isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'] )
		$errors->add('confirm', __('Check your e-mail for the confirmation link.'), 'message');
	elseif	( isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'] )
		$errors->add('newpass', __('Check your e-mail for your new password.'), 'message');
	elseif	( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )
		$errors->add('registered', __('Registration complete. Please check your e-mail.'), 'message');
	elseif	( $interim_login )
		$errors->add('expired', __('Your session has expired. Please log-in again.'), 'message');
	elseif ( strpos( $redirect_to, 'about.php?updated' ) )
		$errors->add('updated', __( '<strong>You have successfully updated WordPress!</strong> Please log back in to experience the awesomeness.' ), 'message' );
		
	//Nutshell's Login Header
	nuts_login_header(__('Log In'), '', $errors);	
		
	// Clear any stale cookies.
	if ( $reauth )
		wp_clear_auth_cookie();
	
	#TODO Fill this with our own login header;
	//login_header(__('Log In'), '', $errors);
	
	if ( isset($_POST['log']) )
		$user_login = ( 'incorrect_password' == $errors->get_error_code() || 'empty_password' == $errors->get_error_code() ) ? esc_attr(stripslashes($_POST['log'])) : '';
		
	$rememberme = ! empty( $_POST['rememberme'] );
?>
<div id="loginscreen">
    <form name="loginform" id="loginform" method="post" action="<?php //echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); #TODO replace the default url once you everything working. ?>">
        <fieldset>
            <label for="user_login"><?php _e('Username/Email Address:'); ?></label>
                <div class="input-prepend">
                <span class="add-on"><i class="icon-envelope"></i></span>
                <input id="user_login" name="user_login" class="input-large" type="text" placeholder="Enter Username or Email..">
                </div>
            
            <label for="user_pass"><?php _e('Password:'); ?></label>
                <div class="input-prepend">
                <span class="add-on"><i class="icon-lock"></i></span>
                <input id="user_pass" name="user_pass" class="input-large" type="password" placeholder="Enter Password...">
                </div>
            <label for="rememberme" class="checkbox">
                <input id="rememberme" name="rememberme" type="checkbox"> <?php _e('Remember Me'); ?>
            </label>
            <?php /*<button id="wp-submit" name="wp-submit" type="submit" class="btn btn-primary"><?php esc_attr_e('Login');?></button>*/ ?>
            <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary" value="<?php esc_attr_e('Log In'); ?>" />
            
            <?php 
			#TODO Move this HTML outside the php block once the login is working. Check wp-login.php for referece.
			//<span class="help-block"><a href="#">Forgot Password?</a></span>
			?>
           
        </fieldset>
    </form>
</div>
<?php 
} // switch
function nuts_login_header($title = 'Log In', $message = '', $wp_error = '') {
	global $error, $interim_login, $current_site, $action;

	// Don't index any of these forms
	add_action( 'login_head', 'wp_no_robots' );

	if ( empty($wp_error) )
		$wp_error = new WP_Error();

	// Shake it!
	$shake_error_codes = array( 'empty_password', 'empty_email', 'invalid_email', 'invalidcombo', 'empty_username', 'invalid_username', 'incorrect_password' );
	$shake_error_codes = apply_filters( 'shake_error_codes', $shake_error_codes );

	if ( $shake_error_codes && $wp_error->get_error_code() && in_array( $wp_error->get_error_code(), $shake_error_codes ) )
		add_action( 'login_head', 'wp_shake_js', 12 );

	?>
    <?php 
	//Not necessary
	/*
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php bloginfo('name'); ?> &rsaquo; <?php echo $title; ?></title>
	*/
	?>
	<?php
	//Not necessary
	/*
	wp_admin_css( 'wp-admin', true );
	wp_admin_css( 'colors-fresh', true );
	*/
	//Bootstrap will do this
	/*if ( wp_is_mobile() ) { ?>
		<meta name="viewport" content="width=320; initial-scale=0.9; maximum-scale=1.0; user-scalable=0;" /><?php
	}*/

	do_action( 'login_enqueue_scripts' );
	do_action( 'login_head' );

	if ( is_multisite() ) {
		$login_header_url   = network_home_url();
		$login_header_title = $current_site->site_name;
	} else {
		$login_header_url   = __( 'http://wordpress.org/' );
		$login_header_title = __( 'Powered by WordPress' );
	}

	$login_header_url   = apply_filters( 'login_headerurl',   $login_header_url   );
	$login_header_title = apply_filters( 'login_headertitle', $login_header_title );

	// Don't allow interim logins to navigate away from the page.
	if ( $interim_login )
		$login_header_url = '#';

	$classes = array( 'login-action-' . $action, 'wp-core-ui' );
	if ( wp_is_mobile() )
		$classes[] = 'mobile';
	if ( is_rtl() )
		$classes[] = 'rtl';
	$classes = apply_filters( 'login_body_class', $classes, $action );
	?>    
	<?php 
	//Not needed.
	#TODO: Check variables for future use.
	/*</head>
	<body class="login <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div id="login">
		<h1><a href="<?php echo esc_url( $login_header_url ); ?>" title="<?php echo esc_attr( $login_header_title ); ?>"><?php bloginfo( 'name' ); ?></a></h1>*/
	?>
	<?php

	unset( $login_header_url, $login_header_title );

	$message = apply_filters('login_message', $message);
	if ( !empty( $message ) )
		echo $message . "\n";

	// In case a plugin uses $error rather than the $wp_errors object
	if ( !empty( $error ) ) {
		$wp_error->add('error', $error);
		unset($error);
	}

	if ( $wp_error->get_error_code() ) {
		$errors = '';
		$messages = '';
		foreach ( $wp_error->get_error_codes() as $code ) {
			$severity = $wp_error->get_error_data($code);
			foreach ( $wp_error->get_error_messages($code) as $error ) {
				if ( 'message' == $severity )
					$messages .= '	' . $error . "<br />\n";
				else
					$errors .= '	' . $error . "<br />\n";
			}
		}
		if ( !empty($errors) )
			echo '<div id="login_error">' . apply_filters('login_errors', $errors) . "</div>\n";
		if ( !empty($messages) )
			echo '<p class="message">' . apply_filters('login_messages', $messages) . "</p>\n";
	}
} // End of login_header()
?>
