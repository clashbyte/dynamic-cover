<?php
/**
 * User: Mikhail
 * Date: 25.10.2017
 * Time: 4:45
 */


// Корневая папка
define('ROOT', __DIR__);

// Инклуды
require 'lib/vk.php';
require 'lib/image.php';

// Создание картинки
$img = GenerateImage();

// Загрузка изображения
Upload($img);

// Вывод картинки
header('Content-type: image/jpeg');
imagejpeg($img, null, 100);
