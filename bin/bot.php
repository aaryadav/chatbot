<?php
require dirname(__DIR__) . "/vendor/autoload.php";

$yourApiKey = "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";

$wsclient = new WebSocket\Client("ws://127.0.0.1:8080/", [
    'timeout' => 5 * 60 * 60,
]);

while (true) {
    $data = json_decode($wsclient->receive(), true);
    $user_id = $data['userId'];
    $msg = $data['msg'];
    $dt = date("d-m-Y h:i:s");

    // send message to bot
    $client = OpenAI::client($yourApiKey);

    $result = $client->completions()->create([
        'model' => 'text-davinci-003',
        'temperature' => 0.3,
        'max_tokens' => 100,
        'prompt' => $msg,
    ]);

    $data = [
        'userId' => 1,
        'msg' => $result['choices'][0]['text'],
        'dt' => $dt
    ];

    $wsclient->send(json_encode($data));
}
?>