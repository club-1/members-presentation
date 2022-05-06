<?php

require 'config.php';



function aboutMe(string $user)
{
    return "/home/$user/ABOUTME.md";
}

$main = "";
foreach (USERS as $user) {
    $aboutMe = aboutMe($user);
    if (file_exists($aboutMe) && !empty($aboutMe)) {
        $md = file_get_contents($aboutMe);
        $content = $md;
        include('template.php');
    }
}

?>