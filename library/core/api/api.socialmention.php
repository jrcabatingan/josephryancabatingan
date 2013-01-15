<?php 
if(!class_exists('Nuts_Social_Mention_API')) {
	class Nuts_Social_Mention_API {
		public $accessID = 'ob931x';
		public $apiURL = 'api.socialmention.com';
		
		public function request( $query, $out = 'json', $retweet = true, $sentiment = '+8', $strict = false) {
		}
	}
}
?>