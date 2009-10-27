CI Disqus
==========

This is a simple CodeIgniter library for interacting with the Disqus API.

Usage: 
------
All methods described in the Disqus API documentation are available here. More info about the [Disqus API](http://wiki.disqus.net/API)

	$this->disqus->get_forum_list(USER_API_KEY);


I've added a few methods for displaying your actual Disqus threads:

The *js_params()* method will spit out a script block with all the params set as global JavaScript params.  See the [documentation](http://wiki.disqus.net/JSEmbed/)

	echo $this->disqus->js_params(array('disqus_developer'=>'1', 'disqus_url'=>base_url().'blog/post/'.$post->shortname));


The *display_thread()* method will display the script to pull in the thread for the current request.

	echo $this->disqus->display_thread();


I've also added methods to make things a bit simpler.  So far, there is only one: 
*get_post_count_by_url()*

	$this->get_post_count_by_url('http://myblog.com/url/post/');

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

Requires [CodeIgniter-cURL](http://github.com/philsturgeon/codeigniter-curl) by [philsturgeon](http://github.com/philsturgeon)