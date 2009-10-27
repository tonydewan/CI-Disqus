<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Disqus
 * A Simple Library to Manage interacting with the Disqus API
 * 
 * Requires the cURL library by Philip Sturgeon [http://codeigniter.com/wiki/Curl_library/]
 * See the official API documentation for methods and response formats: http://wiki.disqus.net/API
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	API Interaction
 * @author		Tony Dewan <tonydewan.com/contact>	
 * @version		0.1
 * @license		http://www.opensource.org/licenses/bsd-license.php BSD licensed.
 *
 * @todo		Add some methods that simplify interaction with the API, rather than just mirroring it
 */
class disqus {
	
	public $forum_id = '213016';
	public $forum_key = NULL;
	public $user_api_key  = NULL;
	public $forum_api_key = NULL;
	
	protected $api_base = 'http://disqus.com/api/';
	
	protected $CI;
	protected $loaded = array();
	
    function __construct()
    {
		$this->CI =& get_instance();
		
		log_message('debug', 'Disqus: Library initialized.');
		
		if( $this->CI->config->load('disqus', TRUE, TRUE) ){
		
			log_message('debug', 'Disqus: config loaded from config file.');
			
			$disqus_config = $this->CI->config->item('disqus');
			$this->config($disqus_config);
		}
    }	
	
	/** 
	* Load Config
	* @access	public
	* @param	Array of config variables.
	* @return   Void
	*/	
	public function config($config)
	{	

		foreach ($config as $key => $value)
		{
			$this->$key = $value;
		}

		log_message('debug', 'Disqus: library configured.');
	}
	

	/** 
	* Create Post
	* @access	public
	* @param	String of the thread_id
	* @param	String of the message
	* @param	String of the author_name 
	* @param	String of the author_email
	* @param	String of the parent_post OPTIONAL
	* @param	String of the created_at OPTIONAL
	* @param	String of the author_url OPTIONAL
	* @param	String of the ip_address OPTIONAL
	* @return   The post object just created on success, FALSE on failure.
	*/
	public function create_post($thread_id, $message, $author_name, $author_email, $parent_post = NULL, $created_at = NULL, $author_url = NULL, $ip_address = NULL)
	{
		// you can pass an array of options instead
		if( is_array($thread_id) ){
			$params = $thread_id;
		}else{
			
			$params = array(
				'thread_id' => $thread_id,
				'message' => $message,
				'author_name' => $author_name,
				'author_email' => $author_email
			);
		
			if($parent_post) $params['parent_post'] = $parent_post;
			if($created_at) $params['created_at'] = $created_at;
			if($author_url) $params['author_url'] = $author_url;
			if($ip_address) $params['ip_address'] = $ip_address;

		}
	
		return $this->_request('POST', 'create_post', $params);
	}
	
	/** 
	* Get Forum List
	* @access	public
	* @param	String of the users API key. Defaults to the global user_api_key value
	* @return   A list of objects representing all forums the user owns on success or FALSE on failure
	*/	
	public function get_forum_list($user_api_key = NULL)
	{
		$params['user_api_key'] = ($user_api_key) ? $user_api_key : $this->user_api_key;
				
		return $this->_request('GET', 'get_forum_list', $params);
	}


	/** 
	* Get Forum API Key
	* @access	public
	* @param	String of the users API key. Defaults to the global user_api_key value
	* @param	String of the forum_id. Defaults to the global forum_id value
	* @return   String of API Key or FALSE
	*/
	public function get_forum_api_key($user_api_key = NULL, $forum_id = NULL)
	{
		$params['user_api_key'] = ($user_api_key) ? $user_api_key : $this->user_api_key;
		$params['forum_id'] = ($forum_id) ? $forum_id : $this->forum_id;
				
		return $this->_request('GET', 'get_forum_api_key', $params);
	}


	/** 
	* Get Thread List
	* @access	public
	* @param	String of the forum API key. Defaults to the global forum_key value
	* @return   list (array) of threads or FALSE
	*/
	public function get_thread_list($forum_key = NULL)
	{
		$params['forum_key'] = ($forum_key) ? $forum_key : $this->forum_key;
				
		return $this->_request('GET', 'get_thread_list', $params);
	}


	/** 
	* Get Number of Posts
	* @access	public
	* @param	String of the forum API key. Defaults to the global forum_key value
	* @param	String of list of threads
	* @return   Object mapping each thread_id to a list of two numbers(Visible total, total total), or FALSE
	*/
	public function get_num_posts($forum_key = NULL, $thread_ids = '')
	{
		$params['forum_key'] = ($forum_key) ? $forum_key : $this->forum_key;
		$params['thread_ids'] = $thread_ids;
				
		return $this->_request('GET', 'get_num_posts', $params);
	}


	/** 
	* Get Thread by URL
	* @access	public
	* @param	String of the forum API key. Defaults to the global forum_key value
	* @param	String of the URL
	* @return   A thread object if one was found, otherwise FALSE
	*/
	public function get_thread_by_url($forum_key = NULL, $url = '')
	{
		$params['forum_key'] = ($forum_key) ? $forum_key : $this->forum_key;
		$params['url'] = $url;
				
		return $this->_request('GET', 'get_thread_by_url', $params);
	}
	

	/** 
	* Get Thread Posts
	* @access	public
	* @param	String of the forum API key. Defaults to the global forum_key value
	* @param	String of the thread ID
	* @return   A list of objects representing all posts belonging to the given forum, otherwise FALSE
	*/
	public function get_thread_posts($forum_key = NULL, $thread_id = '')
	{
		$params['forum_key'] = ($forum_key) ? $forum_key : $this->forum_key;
		$params['thread_id'] = $thread_id;
				
		return $this->_request('GET', 'get_thread_posts', $params);
	}
	

	/** 
	* Get Thread Posts
	* @access	public
	* @param	String of the forum API key. Defaults to the global forum_key value
	* @param	String of the thread ID
	* @return   An object with two keys [thread,created], otherwise FALSE
	*/
	public function thread_by_identifier($forum_key = NULL, $title = '', $identifier = '')
	{
		$params['forum_key'] = ($forum_key) ? $forum_key : $this->forum_key;
		$params['title'] = $title;
		$params['identifier'] = $identifier;
				
		return $this->_request('POST', 'thread_by_identifier', $params);
	}


	/** 
	* Update Thread
	* @access	public
	* @param	String of the forum API key. Defaults to the global forum_key value
	* @param	String of the thread ID
	* @param	String of the title OPTIONAL
	* @param	String of the slug OPTIONAL
	* @param	String of the url OPTIONAL
	* @param	Boolean of the allow_comments flag OPTIONAL
	* @return   An object with two keys [thread,created], otherwise FALSE
	*/
	public function update_thread($forum_key = NULL, $thread_id = '', $title = NULL, $slug = NULL, $url = NULL, $allow_comments = NULL)
	{
		$params['forum_key'] = ($forum_key) ? $forum_key : $this->forum_key;
		$params['thread_id'] = $thread_id;

		if($title) $params['title'] = $title;
		if($slug) $params['slug'] = $slug;
		if($url) $params['url'] = $url;
		if($allow_comments) $params['allow_comments'] = $allow_comments;
				
		return $this->_request('POST', 'update_thread', $params);
	}


	
	/** 
	* Wrapper for doing actual API requests
	* @access	protected
	* @param	String of the request type
	* @param	String of the method name
	* @param	array of params
	* @return   FALSE on empty call and when library is already loaded, TRUE when library loaded
	*/	
	private function _request($type = 'GET', $method = NULL, $params = NULL)
	{
		
		if(!$method || !$params) return false;
		
		$this->_load('curl');
		
		switch($type):
			
			case 'GET':
			case 'get':
			
				$param_string = '?';

				foreach ($params as $key => $value)
				{
					$param_string .= $key.'='.$value;
					if (!end($test) == $value) $param_string .= '&';
				}

				$result = $this->CI->curl->simple_get($this->api_base.$method.$param_string);				
			
			break;
			
			case 'POST':
			case 'post':
			
				$result = $this->CI->curl->simple_post($this->api_base.$method, $params);
			
			break;
		
		endswitch;
		
		$result = json_decode($result);
		
		if($result->succeeded == FALSE):
		
			log_message('error', 'Disqus: API request for "'.$method.'" failed with this message :'.$result->message);
			return false;
			
		else:
		
			return $result->message;
		
		endif;
	}


	/** 
	* Function used to prevent multiple load calls for the same CI library
	* Originally from Carabiner.
	* @access	protected
	* @param	String library name
	* @return   FALSE on empty call and when library is already loaded, TRUE when library loaded
	*/
	protected function _load($lib=NULL)
	{
		if($lib == NULL) return FALSE;
		
		if( isset($this->loaded[$lib]) ):
			return FALSE;
		else:
			$this->CI->load->library($lib);
			$this->loaded[$lib] = TRUE;
			log_message('debug', 'Disqus: library '."'$lib'".' loaded');
			return TRUE;
		endif;
	}
}


