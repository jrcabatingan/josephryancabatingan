<?php 
if(!class_exists('nutshell_campaign_single')) {
	class nutshell_campaign_single {
		static $page;
		
		function __construct() {
			self::$page = 'campaign-single';
			add_shortcode( 'nuts_campaign_single', array(__CLASS__,'shortcode') );
			add_action( 'wp_head',  array(__CLASS__,'head') );
		}
		function shortcode( $atts, $content ) {		
			return self::html();
		}
		function html() {	
			ob_start();
				include_once  NUTS_HTML . '/' . self::$page . '.php' ;	
				$html = ob_get_contents();
			ob_end_clean();	
			
			return $html;
		}
		function head() {
			wp_enqueue_style( 'nutshell-campaign-single' );
		}
	}
}
$nutshell_campaign_single = new nutshell_campaign_single();
?>