<?php 
/**
 * See /plugins/login-framework/ for the complete functionality
 
 * Allows you to add logins and registration from a page plus widget. Covers up wp-login.php.
 * See /plugins/login-framework/lib/login.class.php for bootstrap theming updgrade!
 
 * Courtesy of JonathonByrd's WordPress-Login-and-Registration plugin
 * GitHub: https://github.com/Jonathonbyrd/WordPress-Login-and-Registration
 * Blog: http://redrokk.com/blog
 */
 if(!class_exists('nutshell_login')) {
	class nutshell_login {
		static $page;
		
		function __construct() {
			self::$page = 'login';
			add_shortcode( 'nuts_login', array(__CLASS__,'shortcode') );
		}
		function shortcode( $atts, $content ) {
			extract( shortcode_atts( array(
				'add_url' => '#love',
			), $atts ) );
			
			return self::html( $add_url );
		}
		function html( $url ) {	
			ob_start();
				include_once  NUTS_HTML . '/' . self::$page . '.php' ;	
				$html = ob_get_contents();
			ob_end_clean();	
			
			return $html;
		}
		function scripts() {
		}
	}
}
$nutshell_login = new nutshell_login();
?>