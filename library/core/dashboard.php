<?php 
if(!class_exists('nutshell_dashboard')) {
	class nutshell_dashboard {
		static $page;
		
		function __construct() {
			self::$page = 'dashboard';
			add_shortcode( 'nuts_user_dashboard', array(__CLASS__,'shortcode') );
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
$nutshell_dashboard = new nutshell_dashboard();
?>