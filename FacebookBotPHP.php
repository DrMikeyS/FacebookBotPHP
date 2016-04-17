<?php

class facebookBot {

    public $get;
    public $postData;
    public $token;

    function __construct() {
        $this->get = isset($_GET) ? $_GET : null;
        $this->postData = preg_replace('/"id":(\d+)/', '"id":"$1"', file_get_contents('php://input'));
    }

    public static function verifyWebsite() {
        $verify_token = $this->get['verify_token'];
        if (isset($verify_token)) {
            $challenge = $this->get['hub_challenge'];
            if ($verify_token == "verification_token") {
                print $challenge;
            } elseif ($verify_token != "verification_token") {
                print 'Error, wrong validation token';
            }
        }
    }

    public static function getMessage() {
        $output = json_decode($this->postData);
        return $output->entry[0]->messaging[0]->message->text;
    }

    public static function getSender() {
        
        $output = json_decode($this->postData);
        return $output->entry[0]->messaging[0]->sender->id;
    }

    public static function sendFormattedMessage($recipient, $elements) {
        $json = '{
                "recipient":{"id":"' . $recipient . '"},
                "message":{
                  "attachment":{
                    "type":"template",
                    "payload":{
                      "template_type":"generic",
                      "elements":' . json_encode($elements) . '
                    }
                  }
                  }}';

        $options = array(
            'http' => array(
                'method' => 'POST',
                'content' => $json,
                'header' => "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
            )
        );
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . $this->token;
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $json;
    }

    public static function sendMessage($recipient, $message) {
        $json = '{
                "recipient":{"id":"' . $recipient . '"},
                "message":{
                  "text":"' . $message . '"
                }
              }';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'content' => $json,
                'header' => "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
            )
        );
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . $this->token;
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $json;
    }

}

class witAI {

    public $token;

    function getWitAIResponse($q) {
        $access_token = $this->token;

        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => "Authorization: Bearer " . $access_token . "\r\n" .
                "Accept: appliation/vnd.wit.20141022+json\r\n"
            )
        );
        $context = stream_context_create($options);

        $url = 'https://api.wit.ai/message?q=' . urlencode($q);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result);

        return $result;
    }

}
