<?php
/**
 * User: Mikhail
 * Date: 25.10.2017
 * Time: 4:46
 */

/**
 * Загрузка
 * @param resource $image
 */
function Upload($image) {

	// Чтение токена
	$token = @file_get_contents(ROOT.'/token.txt');
	if(!$token) {
		echo 'Unable to load token file!';
		die();
	}

	// Получение инфо по группе
	$group = ApiQuery('groups.getById', [
		'access_token'  => $token
	])[0]->gid;

	// Получение сервера
	$server = ApiQuery('photos.getOwnerCoverPhotoUploadServer', [
		'access_token'  => $token,
		'group_id'      => $group,
		'crop_x'        => 0,
		'crop_y'        => 0,
		'crop_x2'       => imagesx($image),
		'crop_y2'       => imagesy($image)
	])->upload_url;

	// Загрузка файла
	$response = ApiSendImage($server, $image);

	// Выдача
	$complete = ApiQuery('photos.saveOwnerCoverPhoto', [
		'access_token'  => $token,
		'hash'          => $response->hash,
		'photo'         => $response->photo,
	]);

	var_dump($complete);
	die();
}


function ApiQuery($method, $params = []) {

	// Запрос
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/'.$method.'?'.http_build_query($params));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);

	// Выдача данных
	$d = json_decode($output, false);
	if(!isset($d->response)) {
		echo 'Error in '.$method.'!';
		die();
	}
	return $d->response;
}


function ApiSendImage($link, $image) {

	// Сохранение картинки
	imagejpeg($image, ROOT.'/temp.jpeg', 100);

	// Запрос
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, [
		'photo' => new \CurlFile(ROOT.'/temp.jpeg', 'image/jpeg', 'temp.jpeg')
	]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);

	// Удаление
	@unlink(ROOT.'/temp.jpeg');

	// Выдача данных
	$d = json_decode($output, false);
	if(!isset($d->hash)) {
		echo 'Error in uploading!';
		die();
	}
	return $d;
}