<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

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




function aboutMe(string $user)
{
    return "/home/$user/PRESENTATION.md";
}

foreach (USERS as $user) {
    $aboutMe = aboutMe($user);
    if (file_exists($aboutMe) && !empty($aboutMe)) {
        $md = file_get_contents($aboutMe);
        $result = $converter->convert($md);
        $content = $result->getContent();
        if ($result instanceof RenderedContentWithFrontMatter) {
            $frontMatter = $result->getFrontMatter();
            $memberName = $frontMatter['name'] ?? $user;
        }

        include('template.php');
    }
}

?>