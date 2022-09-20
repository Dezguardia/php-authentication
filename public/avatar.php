<?php

use Entity\UserAvatar;
use Entity\Exception\EntityNotFoundException;

try {
    $userId = $_GET['userId'];
    $userAvatar=UserAvatar::findByID($userId);
    header('Content-Type: image/png');
    echo $userAvatar->getAvatar();
} catch (EntityNotFoundException) {
    header('Content-Type: image/png');
    echo file_get_contents("img/default_avatar.png");
} catch(TypeError) {
}
