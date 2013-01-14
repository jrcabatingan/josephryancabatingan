<?php 
if(!class_exists('Nuts_Ahrefs_API')) {
	class Nuts_Ahrefs_API {
		public $accessID = '8ef3f16a43c3ffee113809eb8bec30ab';
		public $apiURL = 'api.ahrefs.com';
		public $mode = '';
		public $filequery = '';
		public $target = '';
		public $output = '';
		public $urlRequest = '';
		
		public function request( $target, $filequery = 'get_backlinks_count.php', $mode = 'domain', $output = 'json' ) {
			
			if(!empty($target)) 
				$this->target = $target;
			else 
				return 'ERROR: A valid url is required.';			
			
			if(!empty($filequery))
				$this->filequery = $filequery;
			else
				$this->filequery = 'get_backlinks_count.php'; // Fall back to default
				
			if(!empty($mode))
				$this->mode = $mode;
			else
				$this->mode = 'domain'; // Fall back to default
				
			if(!empty($output))
				$this->output = $output;
			else
				$this->output = 'json'; // Fall back to default
				
			// Build the URL
			$url = 'http://' . $this->apiURL . '/' . $this->filequery  . '?target=' . $this->target . '&mode=' . $this->mode . '&output=' . $this->output . '&AhrefsKey=' . $this->accessID;
			
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
?>