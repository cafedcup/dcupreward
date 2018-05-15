<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = '4Qu7kgrFlDwTEszsj7jmLBOiQZlJ8VPm0Cl6cgPBD68TguSuDKlCO7fb/hQojMXf9elSUa6VQ6iAm0SiVmUxQlRbnOFN38rCMclfZ/2EfLH1O4mzPPEG8RiF3yv99r2+aRHOS+usOHxGQ882dov5owdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['source']['userId'];
			$name = $event['source']['displayName'];
			
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			
			$result = curl_exec($ch);
			curl_close($ch);
			
			echo $result . "\r\n";
		}
	}
}
echo "OK";

# This function reads your DATABASE_URL config var and returns a connection
# string suitable for pg_connect. Put this in your app.
function pg_connection_string_from_database_url() {
  extract(parse_url($_ENV["DATABASE_URL"]));
  return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
}

# Here we establish the connection. Yes, that's all.
$pg_conn = pg_connect(pg_connection_string_from_database_url());

# Now let's use the connection for something silly just to prove it works:
$result = pg_query($pg_conn, "SELECT * FROM customer_tbl WHERE schemaname='public'");

print "<pre>\n";
if (!pg_num_rows($result)) {
  print("Your connection is working, but your database is empty.\nFret not. This is expected for new apps.\n");
} else {
  print "Tables in your database:\n";
  while ($row = pg_fetch_row($result)) { print("- $row[0]\n"); }
}
print "\n";


$access_token = '4Qu7kgrFlDwTEszsj7jmLBOiQZlJ8VPm0Cl6cgPBD68TguSuDKlCO7fb/hQojMXf9elSUa6VQ6iAm0SiVmUxQlRbnOFN38rCMclfZ/2EfLH1O4mzPPEG8RiF3yv99r2+aRHOS+usOHxGQ882dov5owdB04t89/1O/w1cDnyilFU=';
$channelSecret = '225c1cb58f767eaf6b61053c1346727f';
$idPush = $text;

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$response = $bot->getProfile($idPush);
if ($response->isSucceeded()) {
    $profile = $response->getJSONDecodedBody();
    $name = $profile['displayName'];
}

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('Hello ' . $name);
$response = $bot->pushMessage($idPush, $textMessageBuilder);
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

echo "END";
