CI Disqus
==========

This is a simple CodeIgniter library for interacting with the Disqus API.

Usage: 
------
All methods described in the Disqus API documentation are available in this library. Get more info about the API in the [API docs](http://wiki.disqus.net/API). I've also added a few methods for displaying your actual Disqus threads:

* The *js_params()* method will spit out a script block with all the parameters set as global JavaScript parameters.  See the [documentation](http://wiki.disqus.net/JSEmbed/).

	`echo $this->disqus->js_params(array('disqus_developer'=>'1', 'disqus_url'=>base_url().'blog/post/'.$post->shortname));`

* The *display_thread()* method will display the script to pull in the thread for the current request.

	`echo $this->disqus->display_thread();`


I've also added methods to make things a bit simpler:

* *get_post_count_by_url()*

	`$this->get_post_count_by_url('http://myblog.com/url/post/');`


* *get_post_count_by_identifier()*

	`$this->get_post_count_by_identifier('identifier');`
	
That will return a string with the number of comments for a given Disqus thread URL, and FALSE if the URL doesn't exist as a Disqus thread. 


There are four global variables you can set. They are:

* forum_id
* forum_shortname
* user_api_key
* forum_api_key

You can use the included config file, or just pass an array of values to the config method:

	$this->disqus->config(array(
		'forum_id' => $forum_id,
		'forum_shortname' => $forum_shortname,
		'user_api_key' => $user_api_key,
		'forum_api_key' => $forum_api_key
	));

Note that config values cascade.  That is, a call to the config method will override the config file, and passing values to API methods will override global values set in either the config file or directly through the method.

Requirements:
-------------
* PHP5
* CodeIgniter 1.72+
* [CodeIgniter-cURL](http://github.com/philsturgeon/codeigniter-curl) by [philsturgeon](http://github.com/philsturgeon)