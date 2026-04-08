<?php

const WEBRING_JSON = 'webring.json';
const ERROR_TARGET = 'https://club1.fr';

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
header('Location: ' . $target);

