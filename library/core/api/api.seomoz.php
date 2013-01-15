<?php 
if(!class_exists('Nuts_SEOmoz_API')) {
	class Nuts_SEOmoz_API {
		public $accessID = "member-3d63555a33";
		public $secretKey = "75421076afdc1bbc933461744e8a0e1e";
		public $apiURL = "lsapi.seomoz.com";
		public $objectURL = '';
		public $expiration = '';
		public $linefeed = '';
		public $binaryKey = '';
		public $urlSafeKey = '';
		public $urlRequest = '';
		public $bigflag = '';
		function __construct( $expires = 300 ) {
			if(is_integer($expires))
				$this->expiration = time() + $expires;
			else 
				$this->expiration = time();
			
			// A new linefeed is necessary between your AccessID and Expires.
			$this->linefeed = $this->accessID."\n".$this->expiration;
			
			// Get the "raw" or binary output of the hmac hash.
			$this->binaryKey = hash_hmac('sha1', $this->linefeed, $this->secretKey, true);
			
			// We need to base64-encode it and then url-encode that.
			$this->urlSafeKey = urlencode(base64_encode($this->binaryKey));
		}
		
		public function request( $object = "www.searchcreatively.com", $cols = "1" ) {
			if(!empty($object)) {
				$this->objectURL = esc_url($object); // insert authenticate $object
			} else {
				return 'ERROR: A valid url is required.';
			}
			
			$this->bigflag = $cols;
			
			$url = "http://" . $this->apiURL . "/linkscape/url-metrics/" . urlencode($this->objectURL) . "?Cols=" . $cols . "&AccessID=" . $this->accessID . "&Expires=" . $this->expiration . "&Signature=" . $this->urlSafeKey;
			
			$this->urlRequest = $url;
			
			return $this->curl($url);
		}
		private function curl( $url ) {
			// We can easily use Curl to send off our request.
			$options = array(
				CURLOPT_RETURNTRANSFER => true
			);
			 
			$ch = curl_init($url);
			curl_setopt_array($ch, $options);
			$content = curl_exec($ch);
			curl_close($ch);
			 
			return $content;
			
		}
	}
}
//$Nuts_SEOmoz_API = new Nuts_SEOmoz_API();
/*
// you can obtain you access id and secret key here: http://www.seomoz.org/api/keys
$accessID = "ACCESS_ID_HERE";
$secretKey = "SECRET_KEY_HERE";
 
// Set your expires for several minutes into the future.
// Values excessively far in the future will not be honored by the Mozscape API.
$expires = time() + 300;
 
// A new linefeed is necessary between your AccessID and Expires.
$stringToSign = $accessID."\n".$expires;
 
// Get the "raw" or binary output of the hmac hash.
$binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
 
// We need to base64-encode it and then url-encode that.
$urlSafeSignature = urlencode(base64_encode($binarySignature));
 
// This is the URL that we want link metrics for.
$objectURL = "www.seomoz.org";
 
// Add up all the bit flags you want returned.
// Learn more here: http://apiwiki.seomoz.org/categories/api-reference
$cols = "103079215108";
 
// Now put your entire request together.
// This example uses the Mozscape URL Metrics API.
$requestUrl = "http://lsapi.seomoz.com/linkscape/url-metrics/".urlencode($objectURL)."?Cols=".$cols."&AccessID=".$accessID."&Expires=".$expires."&Signature=".$urlSafeSignature;
 
// We can easily use Curl to send off our request.
$options = array(
    CURLOPT_RETURNTRANSFER => true
    );
 
$ch = curl_init($requestUrl);
curl_setopt_array($ch, $options);
$content = curl_exec($ch);
curl_close($ch);
 
print_r($content);
*/