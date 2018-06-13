<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<pre>";
print_r($_GET);
echo "</pre><br>";

$access_token = $_GET["token"];
$api = 'https://api.telegram.org/bot' . $access_token;


function sendMessage($chat_id, $text, $reply_markup = false, $parse_mode = false, $preview = false) {
	global $api;
	
	$message = ['chat_id' => $chat_id, 'text' => $text, 'disable_web_page_preview' => true];
	if ($reply_markup !== false) $message["reply_markup"] = json_encode($reply_markup);
	if ($parse_mode !== false) $message["parse_mode"] = $parse_mode;
	
	if ($preview) unset($message['disable_web_page_preview']);

	$postdata = http_build_query($message);

	$opts = ['http' =>
	    [
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'timeout' => 1,
	        'content' => $postdata
	    ]
	];

	$context  = stream_context_create($opts);

	$result = @file_get_contents("$api/sendMessage", false, $context);
}

echo "sending...";

$r = sendMessage($_GET["to"], $_GET["text"]);
if ($r) echo "<br>OK";
else echo "<br>error";

?>
