<?php 
if(!class_exists('Nuts_Brightlocal_API')) {
	class Nuts_Brightlocal_API {
		public $accessID = 'a8e1a14105a2c7a02d0d1a170695a4f8fec8299d';
		public $accessID_secret = '50bcddd62ce73';
		public $apiURL = 'tools.brightlocal.com';
		public $signature = '';
		public $expiration = '';
		public $batch_id = '';
		public $job_ids = '';
		public $urlRequest = '';
		/*public $url = '';
		public $keyword = '';
		public $country = '';
		public $search_engine = '';	
		*/		
		
		function __construct( $expires = 1800 ) {
			if(is_integer($expires) && !empty($expires) && $expires <= 1800) // expiry can't be more than 30 mins (1800 seconds)
				$this->expiration = time() + $expires;
			else 
				$this->expiration = time() + 1800; // Set to default
			
			// Creating the signature key
			$sig = base64_encode(hash_hmac('sha1', $this->accessID . $this->expiration, $this->accessID_secret, true));
			
			$this->signature = urlencode($sig); // for get requests
			
			// Creating a batch ID
			//$this->batch_id = $this->create_batch_id();
		}	
		
		function batch_request_rankings( $mixed_options ) {
			
			$job_ids = array();
			
			$this->batch_id = $this->create_batch_id();
			
			foreach($mixed_options as $options) {
				if(empty($options)) {
					die('Compulsory option missing');
				}
				
				$default = array(
					'api-key' => $this->accessID,
					'batch-id' => $this->batch_id,
					'sig' => $this->signature,
					'expires' => $this->expiration,
					'google-location' => '',
					'business-names' => array(),
					'postcode' => '',
					'telephone' => '',
					'include-name-only-matches' => 'no',
					'position' => 0
				);
				
				// Merge with non-required parameters
				$options = array_merge($default, $options);			
				
				 if (
					empty($options['api-key']) || empty($options['batch-id']) || 
					empty($options['search-engine']) || empty($options['country']) || 
					empty($options['search-term']) || empty($options['url'])
					) 
				{
					die('Compulsory options invalid');
				}
				
				//Add our mixed ranking (in foreach)
				$job_ids[] = $this->add_request_ranking($options);
			} #foreach
			
			/**
			 * Let's Commit our batches	
			 */
			 
			 if(!empty($job_ids)) {
				 $batch_result = $this->commit_batch();
			 }
			 
			/**
			 * end			 
			 */
			 
			 /**
			 * Let's get our batch results
			 */
			 
			 if(!empty($batch_result)) {
				 $get_result = $this->get_batch_result();
				 
				 return $get_result;			
			 }
			 
			 /**
			 * end
			 */
			 
			// If all fails
			return false;		
		}
				
		public function create_batch_id() {
			// Generate URL-encoded query string
			$http = http_build_query(array('api-key' => $this->accessID));
			
			// Build the URL
			$url = 'http://'. $this->apiURL .'/seo-tools/api/v1/create-batch-id?' . $http;
			$result = $this->curl($url);
			$result = json_decode($result, true);
			
			if (!empty($result['response']['batch-id'])) {
				return $result['response']['batch-id'];
				//return $this->batch_id = $result['response']['batch-id'];
			}
			
			return false;
		}
		
		public function commit_batch() {
			$options = array(
			 	'api-key' => $this->accessID,
				'batch-id' => $this->batch_id,
			 );
			 
			// Generate URL-encoded query string
			$http = http_build_query($options);
			
			// Build the URL for committing batches
			$url = 'http://' . $this->apiURL . '/seo-tools/api/v1/commit-batch?' . $http;
			
			$result = $this->curl($url);
			
			$result = json_decode($result, true);	
			
			if(!empty($result['response']['status']))
				return $result['response']['status'];
			
			return false;
		}
		
		public function get_batch_result() {
			 $query = array(
			 	'api-key' => $this->accessID,
				'batch-id' => $this->batch_id,
			 );
			 
			// Generate URL-encoded query string
			$get_http = http_build_query($query);
			
			// Build the URL for committing batches
			$get_url = 'http://' . $this->apiURL . '/seo-tools/api/v1/get-batch-results?' . $get_http;
			
			$get_result = $this->curl($get_url);
			
			$get_result = json_decode($get_result, true);
			
			return $get_result;
		}
		
		private function add_request_ranking( $options ) {
			
			// Generate URL-encoded query string
			$http = http_build_query($options);
			
			// Build the URL
			$url = 'http://' . $this->apiURL . '/seo-tools/api/v1/search-rank?' . $http;
			
			$this->urlRequest = $url;
			
			//Add one or more requests for data to the batch
			$result = $this->curl($this->urlRequest);
			
			$result = json_decode($result, true);
			
			if (!empty($result['response']['status']) && $result['response']['status'] == 'added') {
				return $result['response']['job-id'];
			}	
		}
		
		private function curl($url) {
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