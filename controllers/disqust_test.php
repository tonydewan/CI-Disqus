<?php

class disqus_test extends Controller {

	function disqus_test()
	{
		parent::Controller();
		
		$this->load->helper('url');
		$this->load->library('disqus');
		
		$this->test_user_key = '';
		$this->test_forum_key = '';
		$this->test_forum_id = '';
		$this->test_forum_shortname = '';
		$this->test_thread_id = '';
		$this->test_thread_identifier = '';
		$this->test_thread_url = '';
		
	}
	
	function index()
	{
		// Eventually I want to write a little bit more friendly, custom test suite. 
		// For now, CI built in unit will have to do
		redirect('/disqus_test/ci_unit/');
	}
	
	function ci_unit()
	{
		
		if($this->test_user_key == '' || $this->test_thread_url == '' || $this->test_forum_key == '' || $this->test_forum_id == '' || $this->test_forum_shortname == '' || $this->test_thread_id == '' || $this->test_thread_identifier == ''):
			$data['message'] = '<p>No tests run. To get accurate test results, you will need to set all the test variables in controllers/disqus_test.php constructor';
			$this->load->view('test', $data);
			return false;
		endif;
		
		$data['message'] = '<p>As of CI 1.72, Some tests show as failed when they are really passed.  See this <a href="http://codeigniter.com/bug_tracker/bug/9451/">bug report</a> for problem and fix.';
		
		$this->load->library('unit_test');
		$this->unit->use_strict(TRUE);
		
		$this->unit->run( $this->disqus->display_thread(), 'is_string', 'display_thread()');
		$this->unit->run( $this->disqus->display_thread('container'), 'is_string', 'display_thread() with param');
		
		$this->unit->run( $this->disqus->js_params(), 'is_null', 'js_params()');
		$this->unit->run( $this->disqus->js_params(array('disqus_url'=>'http://example.com/')), 'is_string', 'js_params() with params');
		
		$this->disqus->config(array('js_params'=>array('disqus_developer' => '0')));
		$this->unit->run( $this->disqus->js_params(), 'is_string', 'js_params() with predefined params');
		
		$this->unit->run( $this->disqus->get_post_count_by_url('url'), 'is_false', 'get_post_count_by_url()'); // should be false
		$this->unit->run( $this->disqus->get_post_count_by_url($this->test_thread_url), 'is_numeric', 'get_post_count_by_url() with real url'); // should be string

		$this->unit->run( $this->disqus->get_post_count_by_identifier(NULL), 'is_false', 'get_post_count_by_identifier()'); // should be false
		$this->unit->run( $this->disqus->get_post_count_by_identifier('identifier'), 'is_numeric', 'get_post_count_by_identifier() real identifier'); // should be string
		
		$this->unit->run( $this->disqus->get_forum_list(NULL), 'is_array', 'get_forum_list() with global key');
		$this->unit->run( $this->disqus->get_forum_list($this->test_user_key), 'is_array', 'get_forum_list() with real key');
		$this->unit->run( $this->disqus->get_forum_list('notarealkey'), 'is_false', 'get_forum_list() with fake key');
		
		$this->unit->run( $this->disqus->get_forum_api_key(NULL, NULL), 'is_string', 'get_forum_api_key() with global key');
		$this->unit->run( $this->disqus->get_forum_api_key($this->test_user_key), 'is_string', 'get_forum_api_key() with real key');
		$this->unit->run( $this->disqus->get_forum_api_key('notarealkey'), 'is_false', 'get_forum_api_key() with fake key');
		
		$this->unit->run( $this->disqus->get_thread_list(NULL), 'is_string', 'get_thread_list() with global key');
		$this->unit->run( $this->disqus->get_thread_list($this->test_forum_key), 'is_string', 'get_thread_list() with real key');
		$this->unit->run( $this->disqus->get_thread_list('notarealkey'), 'is_false', 'get_thread_list() with fake key');

		$this->unit->run( $this->disqus->get_num_posts(NULL, $this->test_thread_id), 'is_numeric', 'get_num_posts() with global key, real forum id');
		$this->unit->run( $this->disqus->get_num_posts(NULL, 'notarealid'), 'is_false', 'get_num_posts() with global key, fake forum id');
		$this->unit->run( $this->disqus->get_num_posts($this->test_forum_key, $this->test_thread_id), 'is_numeric', 'get_num_posts() with real key, real id');
		$this->unit->run( $this->disqus->get_num_posts($this->test_forum_key, 'notarealid'), 'is_false', 'get_num_posts() with real key, fake id');
		$this->unit->run( $this->disqus->get_num_posts('notarealkey', $this->test_thread_id), 'is_false', 'get_num_posts() with fake key, real id');
		$this->unit->run( $this->disqus->get_num_posts('notarealkey', 'notarealid'), 'is_false', 'get_num_posts() with fake key, fake id');
		
		$this->unit->run( $this->disqus->get_thread_by_url(NULL, $this->test_thread_url), 'is_object', 'get_thread_by_url() with global key, real url');
		$this->unit->run( $this->disqus->get_thread_by_url(NULL, 'notarealid'), 'is_null', 'get_thread_by_url() with global key, fake url');
		$this->unit->run( $this->disqus->get_thread_by_url($this->test_forum_key, $this->test_thread_url), 'is_object', 'get_thread_by_url() with real key, real url');
		$this->unit->run( $this->disqus->get_thread_by_url($this->test_forum_key, 'notarealid'), 'is_null', 'get_thread_by_url() with real key, fake url');
		$this->unit->run( $this->disqus->get_thread_by_url('notarealkey', $this->test_thread_url), 'is_false', 'get_thread_by_url() with fake key, real url');
		$this->unit->run( $this->disqus->get_thread_by_url('notarealkey', 'notarealid'), 'is_false', 'get_thread_by_url() with fake key, fake url');
		
		$this->unit->run( $this->disqus->get_thread_posts(NULL, $this->test_thread_id), 'is_array', 'get_thread_posts() with global key, real id');
		$this->unit->run( $this->disqus->get_thread_posts(NULL, 'notarealid'), 'is_false', 'get_thread_posts() with global key, fake id');
		$this->unit->run( $this->disqus->get_thread_posts($this->test_forum_key, $this->test_thread_id), 'is_array', 'get_thread_posts() with real key, real id');
		$this->unit->run( $this->disqus->get_thread_posts($this->test_forum_key, 'notarealid'), 'is_false', 'get_thread_posts() with real key, fake id');
		$this->unit->run( $this->disqus->get_thread_posts('notarealkey', $this->test_thread_id), 'is_false', 'get_thread_posts() with fake key, real id');
		$this->unit->run( $this->disqus->get_thread_posts('notarealkey', 'notarealid'), 'is_false', 'get_thread_posts() with fake key, fake id');
				
		$this->unit->run( $this->disqus->thread_by_identifier(NULL, $this->test_thread_identifier), 'is_object', 'thread_by_identifier() with global key, real identifier');
		$this->unit->run( $this->disqus->thread_by_identifier(NULL, 'notarealid'), 'is_false', 'thread_by_identifier() with global key, fake identifier');
		$this->unit->run( $this->disqus->thread_by_identifier($this->test_forum_key, $this->test_thread_identifier), 'is_object', 'thread_by_identifier() with real key, real identifier');
		$this->unit->run( $this->disqus->thread_by_identifier($this->test_forum_key, 'notarealid'), 'is_false', 'thread_by_identifier() with real key, fake identifier');
		$this->unit->run( $this->disqus->thread_by_identifier('notarealkey', $this->test_thread_identifier), 'is_false', 'thread_by_identifier() with fake key, real identifier');
		$this->unit->run( $this->disqus->thread_by_identifier('notarealkey', 'notarealid'), 'is_false', 'thread_by_identifier() with fake key, fake identifier');

		$this->unit->run( $this->disqus->update_thread(NULL, $this->test_thread_id, 'Title'), 'is_object', 'update_thread() with global key, real identifier');
		$this->unit->run( $this->disqus->update_thread(NULL, 'notarealid', 'Title'), 'is_object', 'update_thread() with global key, fake identifier');
		$this->unit->run( $this->disqus->update_thread($this->test_forum_key, $this->test_thread_id, 'Title'), 'is_object', 'update_thread() with real key, real identifier');
		$this->unit->run( $this->disqus->update_thread($this->test_forum_key, 'notarealid', 'Title'), 'is_object', 'update_thread() with real key, fake identifier');
		$this->unit->run( $this->disqus->update_thread('notarealkey', $this->test_thread_id, 'Title'), 'is_object', 'update_thread() with fake key, real identifier');
		$this->unit->run( $this->disqus->update_thread('notarealkey', 'notarealid', 'Title'), 'is_false', 'update_thread() with fake key, fake identifier');

		$data['results'] = $this->unit->report();
		$this->load->view('test', $data);
	}
}

/* End of file disqus_test.php */
/* Location: ./system/application/controllers/disqus_test.php */