<?php

const WEBRING_JSON = 'webring.json';
const ERROR_TARGET = 'https://club1.fr';

if (!file_exists(WEBRING_JSON)) {
    header('Location: ' . ERROR_TARGET);
    die;
}

$webring = json_decode(file_get_contents(WEBRING_JSON));
if (empty($webring['urls'])) {
    header('Location: ' . ERROR_TARGET);
    die;
}

$target = $webring['urls'][random_int(0, count($webring['urls']) - 1)];
header('Location: ' . $target);

