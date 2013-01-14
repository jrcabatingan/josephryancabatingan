<?php 
if(!class_exists('nutshell_campaign_add')) {
	class nutshell_campaign_add {
		static $page;
		
		function __construct() {
			self::$page = 'campaign-add';
			add_shortcode( 'nuts_campaign_add', array(__CLASS__,'shortcode') );
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
$nutshell_campaign_add = new nutshell_campaign_add();
?>