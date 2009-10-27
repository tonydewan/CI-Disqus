<?php

class disqus_test extends Controller {

	function disqus_test()
	{
		parent::Controller();
		
		$this->load->library('disqus');
		
		
	}
	
	function index()
	{
	}
	
	function ci_unit()
	{
		$this->load->library('unit_test');
		$this->unit->use_strict(TRUE);
		
		$this->unit->run( $this->disqus->display_thread(), 'is_string', 'display_thread()');
		$this->unit->run( $this->disqus->display_thread('container'), 'is_string', 'display_thread() with param');
		
		$this->unit->run( $this->disqus->js_params(), 'is_null', 'js_params()');
		$this->unit->run( $this->disqus->js_params(array('disqus_url'=>'http://example.com/')), 'is_string', 'js_params() with params');
		
		$this->disqus->config(array('js_params'=>array('disqus_developer' => '0')));
		$this->unit->run( $this->disqus->js_params(), 'is_string', 'js_params() with predefined params');
		
		$this->unit->run( $this->disqus->get_post_count_by_url('url'), 'is_bool', 'get_post_count_by_url()'); // should be false
		//$this->unit->run( $this->disqus->get_post_count_by_url('http://arealdisqusthreadurl.com'), 'is_string', 'get_post_count_by_url_with() real url'); // should be string
				
		echo $this->unit->report();
	}
}

/* End of file disqus_test.php */
/* Location: ./system/application/controllers/disqus_test.php */