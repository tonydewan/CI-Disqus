CI Disqus
==========

This is a simple CodeIgniter library for interacting with the Disqus API. In this version, the 
library merely matches the Disqus API methods exactly.

Usage: 
------
All methods described in the Disqus API documentation are available here.
More info about the [Disqus API](http://wiki.disqus.net/API)

	$this->disqus->get_forum_list(USER_API_KEY);

You can also pass an array of values to the config method to make then globally available.  Appropriate
ones include: forum_id, forum_key, user_api_key, forum_api_key

	$this->disqus->config(array(
		'forum_id' => $forum_id,
		'forum_key' => $forum_key,
		'user_api_key' => $user_api_key,
		'forum_api_key' => $forum_api_key
	));


Requirements:
-------------

Requires [CodeIgniter-cURL](http://github.com/philsturgeon/codeigniter-curl) by [philsturgeon](http://github.com/philsturgeon)