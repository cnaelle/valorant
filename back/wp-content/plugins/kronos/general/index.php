<?php

// Disallow all in robots.txt if it's not a production environment
add_filter('robots_txt', function ($output, $public) {
	if ($_ENV['APP_ENV'] != 'prod' && $_ENV['APP_ENV'] != 'production') {
		return "Disallow: /";
	}
	return $output;
}, 9999999, 2);
