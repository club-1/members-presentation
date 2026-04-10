<?php

const WEBRING_JSON = 'webring.json';

if (!file_exists(WEBRING_JSON)) {
	http_response_code(500);
    die;
}

$webring = json_decode(file_get_contents(WEBRING_JSON));
if (empty($webring['urls'])) {
	http_response_code(500);
    die;
}

$target = $webring['urls'][random_int(0, count($webring['urls']) - 1)];
// Using Location header magically set the response code to `302`
// <https://www.php.net/manual/fr/function.header.php>
header('Location: ' . $target);

