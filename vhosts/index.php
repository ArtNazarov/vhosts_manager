<?php

define("SERV", 'localhost');
define("USER", 'vhosts');
define("PASS", 'your_pass');
define("DBNAME", 'vhosts');
define('VHOSTS_PATH', 'C:/xampp/apache/conf/extra/httpd-vhosts.conf');



function connect(){
$mysqli = new mysqli(SERV, USER, PASS, DBNAME);

// О нет!! переменная connect_errno существует, а это значит, что соединение не было успешным!
if ($mysqli->connect_errno) {
    // Соединение не удалось. Что нужно делать в этом случае? 
    // Можно отправить письмо администратору, отразить ошибку в журнале, 
    // информировать пользователя об ошибке на экране и т.п.
    // Вам не нужно при этом раскрывать конфиденциальную информацию, поэтому
    // просто попробуем так:
    echo "Извините, возникла проблема на сайте";

    // На реальном сайте этого делать не следует, но в качестве примера мы покажем 
    // как распечатывать информацию о подробностях возникшей ошибки MySQL
    echo "Ошибка: Не удалась создать соединение с базой MySQL и вот почему: \n";
    echo "Номер ошибки: " . $mysqli->connect_errno . "\n";
    echo "Ошибка: " . $mysqli->connect_error . "\n";
    
    // Вы можете захотеть показать что-то еще, но мы просто выйдем
    exit;
};
return $mysqli;
}

function read_base($mysqli){
$sql = "SELECT * FROM vhosts ORDER BY domain";
if (!$result = $mysqli->query($sql)) {
    echo "Извините, возникла проблема в работе сайта.";
    exit;
}
$list = [];
while ($record = $result->fetch_assoc()) {
    $domain = $record['domain'];
	$path = $record['path'];
	array_push($list, ['domain'=>$domain, 'path'=>$path]);
}
$result->free();
return $list;
}

function actions_helper($domain){
	$delete = "<a href='/vhosts/index.php?action=delete&domain=$domain'>Delete</a>";
	$edit = "<a href='/vhosts/index.php?action=edit&domain=$domain'>Edit</a>";
	return "<td>$delete</td><td>$edit</td>";
}

function print_list($list){
	$html = "<table width='100%' border='1'>";
	for($i=0;$i<count($list);$i++){
		$domain = $list[$i]['domain'];
		$path = $list[$i]['path'];
		$actions = actions_helper($domain);
		$html .= "<tr><td>$domain</td><td>$path</td>$actions</tr>";
	};
	return $html . '</table>';
}

function read_template(){
	$contents = file_get_contents(__DIR__ . '/vhost-item.tpl');
	$pre = file_get_contents(__DIR__ . '/vhosts-pre.tpl');
	return $pre . "\r\n" . $contents;
}

function set_item($template, $domain, $path){
	$result = str_replace('%domain%', $domain, $template);
	$result = str_replace('%path%', $path, $result);
	return $result;
}

function get_conf($list){
	$template = read_template();
	$conf = '';
	for ($i=0; $i<count($list); $i++){
		$domain = $list[$i]['domain'];
		$path = $list[$i]['path'];
		$conf = $conf . "\r\n" . set_item($template, $domain, $path);
	}
	return $conf;
}

function delete_domain($mysqli, $domain){
	$sql = 'DELETE FROM vhosts WHERE domain=?';
	if (!($stmt = $mysqli->prepare($sql))) {
		echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
		};
		
	if (!$stmt->bind_param("s", $domain)) {
		echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
	};
	
	
}

function domain_params($mysqli, $domain){
	$sql = 'SELECT domain, path FROM vhosts WHERE domain=? LIMIT 1';
	if (!($stmt = $mysqli->prepare($sql))) {
		echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
		};
		
	if (!$stmt->bind_param("s", $domain)) {
		echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
	};
	
	 $stmt->bind_result($domain, $path);
	 $stmt->fetch();
	 return ['domain'=>$domain, 'path'=>$path];
	
}

function update_domain($mysqli, $domain, $path){
		if (!($stmt = $mysqli->prepare("UPDATE vhosts SET domain = ?, path = ? WHERE domain=?"))) {
		echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
		};
		
	if (!$stmt->bind_param("sss", $domain, $path, $domain)) {
		echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
	};
}

function output_index($list){
	$index_tpl = file_get_contents(__DIR__ . '/index.tpl');
	$result = str_replace('%contents%', print_list($list), $index_tpl);
	$conf = get_conf($list);
	$result = str_replace('%vhosts%', $conf, $result);
	file_put_contents(__DIR__ . '/vhosts.conf', $conf);
	return $result;
}

function send_html($html){
	header('Content-Type: text/html; charset=utf-8');
	echo $html;
}

function get_route(){
	$default = 'view';
	if (isset($_GET['action'])) { $action = $_GET['action']; } else {$action = $default; };
	return $action;
}

function mainpage_link(){
	echo '<a href="/vhosts/index.php?action=view">[посмотреть]</a>';
};

$mysqli = connect();

$list = read_base($mysqli);

$action = get_route();

switch ($action) {
	case 'view' : {
			send_html(output_index($list));
			break;
	}
	case 'new' :
	{
		send_html(file_get_contents(__DIR__ . '/new_vhosts.tpl'));
		break;
	}
	case 'add' : {
		$domain = $_POST['domain'];
		$path = $_POST['path'];
		/* подготавливаемый запрос, первая стадия: подготовка */
		if (!($stmt = $mysqli->prepare("INSERT INTO vhosts(domain, path) VALUES (?, ?)"))) {
		echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
		};
		
	if (!$stmt->bind_param("ss", $domain, $path)) {
		echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
	};
	
	mainpage_link();
	break;
	}
	case 'delete' : {
		$domain = $_GET['domain'];
		delete_domain($mysqli, $domain);
		mainpage_link();
		break;
	}
	case 'edit' :
	{
		$domain = $_GET['domain'];
		$params = domain_params($mysqli, $domain);
		$template = file_get_contents(__DIR__ . '/edit.tpl'); 
		$result = str_replace('%domain%', $domain, $template);
		$result = str_replace('%path%', $params['path'], $result);
		send_html($result);
		break;
	}
	case 'update' :
	{
		 $domain = $_POST['domain'];
		 $path = $_POST['path'];
		 update_domain($mysqli, $domain, $path);
		 mainpage_link();
	}
	case 'write_vhosts' :
	{
		 file_put_contents(VHOSTS_PATH, file_get_contents(__DIR__ . '/vhosts.conf'));
		 mainpage_link();
	}
	default :
	{
		header('HTTP/1.0 404 Not Found');
	};
}

$mysqli->close();


?>