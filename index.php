<?php

umask(0002);

require __DIR__ . '/vendor/autoload.php';

const CACHE_FILE    = 'cache.html';
const CACHE_JSON    = 'cache.json';
const WEBRING_JSON  = 'webring.json';
const DEFAULT_COLOR = '#35a0d6';
$needRender         = false;
$renderedCounter    = 0;

if (file_exists(CACHE_JSON)) {
    $cache = json_decode(file_get_contents(CACHE_JSON));
} else {
    $needRender = true;
}

if (!file_exists(CACHE_FILE)) {
    $needRender = true;
}

$users = array_diff(scandir('/home'), array('..', '.'));
$webringUrls = [];

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
use League\CommonMark\MarkdownConverter;

// Define your configuration, if needed
$config = [
    'html_input' => 'strip',
    'external_link' => [
        'internal_hosts' => '', // TODO: Don't forget to set this!
        'open_in_new_window' => true,
        'html_class' => 'external-link',
        'nofollow' => '',
        'noopener' => 'external',
        'noreferrer' => 'external',
    ],
    'default_attributes' => [
        Image::class => [
            'loading' => 'lazy',
        ],
    ],
];

// Configure the Environment with all the CommonMark parsers/renderers
$environment = new Environment($config);
$environment->addExtension(new CommonMarkCoreExtension());
$environment->addExtension(new FrontMatterExtension());
$environment->addExtension(new ExternalLinkExtension());
$environment->addExtension(new DefaultAttributesExtension());

// Instantiate the converter engine and start converting some Markdown!
$converter = new MarkdownConverter($environment);




function presentationDir(string $user)
{
    return "/home/$user/PRESENTATION.md";
}

// Check if cache file is outdated
if (!$needRender) {
    $presentationCounter = 0;
    $renderTimestamp = filemtime(CACHE_FILE);
    foreach ($users as $user) {
        $presentation = presentationDir($user);
        if (file_exists($presentation) && !empty($presentation)) {
            $timestamp = filemtime($presentation);
            if (is_int($timestamp)) {
                if ($timestamp >= $renderTimestamp) {
                    $needRender = true;
                    break;
                }
            }
            $presentationCounter ++;
        }
    }
    if (!$needRender) {
        $needRender = $presentationCounter !== $cache->renderedCounter;
    }
}

// Render the user's div. Used in template.php
function renderUsers()
{
    global $converter;
    global $users;
    global $renderedCounter;
    global $webringUrls;
    shuffle($users);
    foreach ($users as $user) {
        $presentation = presentationDir($user);
        if (file_exists($presentation) && !empty($presentation)) {
            $md = file_get_contents($presentation);
            $name = $user;
            $color = DEFAULT_COLOR;
            try {
                $result = $converter->convert($md);
                $content = $result->getContent();
                if ($result instanceof RenderedContentWithFrontMatter) {
                    $frontMatter = $result->getFrontMatter();
                    if (!empty($frontMatter['name'])) {
                        $name = $frontMatter['name'];
                    }
                    if (!empty($frontMatter['color'])) {
                        $color = $frontMatter['color'];
                    }
                    if (!empty($frontMatter['websites'])) {
                        $urls = $frontMatter['websites'];
                        if (is_string($urls)) {
                            $webringUrls[] = $urls;
                        } elseif (is_array($urls)) {
                            $webringUrls = array_merge($webringUrls, array_values($urls));
                        }
                    }
                }
            } catch (RuntimeException $e) {
                $message = $e->getMessage();
                $content = "render error : $message";
            }
            $userId = $user;
            include 'templateUser.php';
            $renderedCounter ++;
        }
    }
}

if ($needRender) {
    
    ob_start();

    include 'template.php';

    $render = ob_get_contents();
    ob_end_clean();

    if (is_string($render)) {
        file_put_contents(CACHE_FILE, $render);
        file_put_contents(CACHE_JSON, json_encode(['renderedCounter' => $renderedCounter]));
        file_put_contents(WEBRING_JSON, json_encode(['urls' => $webringUrls]);
    }
}

include CACHE_FILE;

