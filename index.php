<?php

require __DIR__ . '/vendor/autoload.php';
-include __DIR__ . '/config.php';

const CACHE_FILE = 'cache.html';
$updated = false;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;

// Configure the Environment with all the CommonMark parsers/renderers
$environment = new Environment();
$environment->addExtension(new CommonMarkCoreExtension());

// Add the extension
$environment->addExtension(new FrontMatterExtension());

// Instantiate the converter engine and start converting some Markdown!
$converter = new MarkdownConverter($environment);


if (is_array(USERS) && !empty(USERS)) {
    $users = USERS;
} else {
    $users = array_diff(scandir('/home'), array('..', '.'));
}

function presentationDir(string $user)
{
    return "/home/$user/PRESENTATION.md";
}

if (file_exists(CACHE_FILE)) {
    $renderTimestamp = filemtime(CACHE_FILE);
    foreach ($users as $user) {
        $presentation = presentationDir($user);
        if (file_exists($presentation) && !empty($presentation)) {
            $timestamp = filemtime($presentation);
            if (is_int($timestamp)) {
                if ($timestamp >= $renderTimestamp) {
                    $updated = true;
                    break;
                }
            }
        }
    }
}

function renderUsers()
{
    global $converter;
    global $users;
    shuffle($users);
    foreach ($users as $user) {
        $presentation = presentationDir($user);
        if (file_exists($presentation) && !empty($presentation)) {
            $md = file_get_contents($presentation);
            $name = $user;
            try {
                $result = $converter->convert($md);
                $content = $result->getContent();
                if ($result instanceof RenderedContentWithFrontMatter) {
                    $frontMatter = $result->getFrontMatter();
                    if (!empty($frontMatter['name'])) {
                        $name = $frontMatter['name'];
                    }
                }
            } catch (RuntimeException $e) {
                $message = $e->getMessage();
                $content = "render error : $message";
            }
            $userId = $user;
            include 'templateUser.php';
        }
    }
}

if ($updated) {
    
    ob_start();

    include 'template.php';

    $render = ob_get_contents();
    ob_end_clean();

    if (is_string($render)) {
        file_put_contents(CACHE_FILE, $render);
    }
}

include 'cache.html';


?>