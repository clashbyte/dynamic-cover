<?php
/**
 * User: Mikhail
 * Date: 25.10.2017
 * Time: 4:47
 */

define('FONT_BOLD', ROOT.'/images/Fregat_bold.ttf');
define('FONT_NORMAL', ROOT.'/images/Fregat_regular.ttf');

/**
 * Создание изображения
 * @return resource
 */
function GenerateImage() {

	// Создание пустого изображения
	$img = imagecreatetruecolor(1590, 400);
	imagealphablending($img, true);
	$bg = imagecreatefrompng(realpath(ROOT.'/images/base.png'));
	imagecopy($img, $bg, 0, 0, 0, 0, imagesx($img), imagesy($img));

	// Цвета
	$red = imagecolorallocate($img, 198, 40, 36);
	$white = imagecolorallocate($img, 255, 255,255);


	/*
	// Отрисовка текста
	$odd = CalculateWeek();
	DrawFontBox($img,'неделя', 1342, 60, $white, $red, 24, 10, FONT_BOLD);
	DrawFont($img, $odd, 1342, 110, $white, 64, FONT_BOLD);

	// Отрисовка программы
	DrawFontBox($img, 'сегодня на радио', 1342, 230, $white, $red, 24, 10, FONT_BOLD);
	DrawFont($img, 'Название программы', 1342, 270, $white, 48, FONT_BOLD);
	*/

	// Отрисовка текста
	DrawFontBox($img,'неделя', 1342, 210, $white, $red, 24, 10, FONT_BOLD);
	DrawFont($img, CalculateWeek(), 1342, 260, $white, 64, FONT_BOLD);

	// Отрисовка программы
	DrawFontBox($img, 'сегодня', 1342, 40, $white, $red, 24, 10, FONT_BOLD);
	DrawFont($img, CalculateDate(), 1342, 90, $white, 56, FONT_BOLD);


	// Выдача изображения
	return $img;
}

/**
 * Отрисовка текста с тенью
 * @param resource $image Картинка
 * @param string $text Текст
 * @param int $x X
 * @param int $y Y
 * @param int $color Цвет
 * @param int $size Размер
 * @param string $font Шрифт
 */
function DrawFont($image, $text, $x, $y, $color, $size, $font = FONT_NORMAL) {
	$box = imagettfbbox($size, 0, $font, $text);
	$shade = imagecolorallocatealpha($image, 0, 0, 0, 90);
	imagettftext($image, $size, 0, $x - $box[2] + $box[0] + 3, $y + $box[1] - $box[7] + 3, $shade, $font, $text);
	imagettftext($image, $size, 0, $x - $box[2] + $box[0], $y + $box[1] - $box[7], $color, $font, $text);
}

/**
 * Отрисовка текста в коробке
 * @param resource $image
 * @param string $text
 * @param int $x X
 * @param int $y Y
 * @param int $color Цвет
 * @param int $box_color Цвет коробки
 * @param int $size Размер
 * @param int $padding Отступ
 * @param string $font Шрифт
 */
function DrawFontBox($image, $text, $x, $y, $color, $box_color, $size, $padding, $font = FONT_NORMAL) {
	$box = imagettfbbox($size, 0, $font, $text);
	imagefilledrectangle($image, $x, $y, $x - $box[2] + $box[0] - $padding * 4, $y + $box[1] - $box[7] + $padding * 2, $box_color);
	imagettftext($image, $size, 0, $x - $box[2] + $box[0] - $padding * 2, $y + $box[1] - $box[7] + $padding, $color, $font, $text);
}

/**
 * Неделя
 * @return string
 */
function CalculateWeek() {
	$week = date('W');
	$odd = ($week % 2) == 0;
	return $odd ? 'Числитель' : 'Знаменатель';
}

/**
 * Вычисление даты
 */
function CalculateDate() {
	$months = [
		"января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"
	];
	return date('j').' '.$months[(int)date('n')-1];
}

