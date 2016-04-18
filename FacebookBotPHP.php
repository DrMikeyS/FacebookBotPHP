<?php

facebookBot::$postData = file_get_contents('php://input');
facebookBot::$get = $_GET;
class facebookBot {

    public static $get;
    public static $postData;
    public static $token;

    
    public static function verifyWebsite() {
        
        if (isset(self::$get['verify_token'])) {
            $verify_token = self::$get['verify_token'];
            $challenge = self::$get['hub_challenge'];
            if ($verify_token == "verification_token") {
                print $challenge;
            } elseif ($verify_token != "verification_token") {
                print 'Error, wrong validation token';
            }
        }
    }

    public static function getMessage() {
        $output = json_decode(self::$postData);
        return $output->entry[0]->messaging[0]->message->text;
    }

    public static function getSender() {
        $output = json_decode(preg_replace('/"id":(\d+)/', '"id":"$1"',self::$postData ));
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
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . self::$token;
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
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . self::$token;
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $json;
    }

}

class witAI {

    public static $token;

    function getWitAIResponse($q) {
        $access_token = self::$token;

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
