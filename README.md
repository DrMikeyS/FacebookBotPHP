# FacebookBotPHP

A few simple PHP classes to help you quickly build an app for [Facebooks Messenger API](https://developers.facebook.com/docs/messenger-platform/quickstart). 

The classes also offer a simple wrapper for sending requests to [Wit.AI](https://wit.ai/) to help deal with natural language requests.

##Install

To get going just include the class file FacebookBotPHP.php into your app and follow the code below. You'll also need to set your access tokens for Facebook and Wit.AI, you can get them from their development consoles.


##Sample code

```
<?php

include 'FacebookBotPHP.php';
facebookBot::$postData = file_get_contents('php://input');
facebookBot::$get = $_GET;

facebookBot::$token = '';
witAI::$token = '';

//Verification of site with facebook
facebookBot::verifyWebsite();

//Get a message sent to your webhook
$recievedMessage = facebookBot::getMessage();

//Get the facebook ID of the sender
$recievedMessageSender =  facebookBot::getSender();

//Send a simple response
facebookBot::sendMessage($recievedMessageSender, "Hey, thanks for getting in touch");

//Send a more complex message  - the Elements pointer should be an array of objects with the format as below
$elements = [
    (object) [
                        'title' => "Title text",
                        'image_url' => "https://www.google.co.uk/images/nav_logo242.png",
                        'subtitle' => "Subtitle text",
                        'buttons' => array((object) [
                            "type"=> "web_url",
                            "url"=> "http://google.com/",
                            "title"=>"Website Link Button"
                                    ])
            ]
    ];
facebookBot::sendFormattedMessage($recievedMessageSender, $elements);

//Get a response from Wit.ai natural text API
$witResponse = getWitAIResponse($recievedMessage);
```

