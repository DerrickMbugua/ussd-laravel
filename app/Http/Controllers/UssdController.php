<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class UssdController extends Controller
{

    public function index(Request $request){

// Read the variables sent via POST from our API
$sessionId   = $request->get("sessionId");
$serviceCode = $request->get("serviceCode");
$phoneNumber = $request->get("phoneNumber");
$text        = $request->get("text");

if ($text == "") {
    // This is the first request. Note how we start the response with CON
    $response  = "CON What would you want to check \n";
    $response .= "1. My Account \n";
    $response .= "2. My phone number\n";
    $response .= "3. Go to whatsapp chatbot";

} else if ($text == "1") {
    // Business logic for first level response
    $response = "CON Choose account information you want to view \n";
    $response .= "1. Account number \n";

} else if ($text == "2") {
    // Business logic for first level response
    // This is a terminal request. Note how we start the response with END
    $response = "END Your phone number is ".$phoneNumber;

} else if($text == "1*1") {
    // This is a second level response where the user selected 1 in the first instance
    $accountNumber  = "ACC1001";

    // This is a terminal request. Note how we start the response with END
    $response = "END Your account number is ".$accountNumber;
} else if($text == "3"){
    $response = "END Thank you joining our whatsapp chatbot.\n";
    $sid = "AC38a6c200dee219b0f026f681557a7e82";
$token = "319fbb80dc9ff93912260dafbe78ab46";
$twilio = new Client($sid, $token);

$message = $twilio->messages
                  ->create("whatsapp:+254715153806", // to
                           [
                               "from" => "whatsapp:+14155238886",
                               "body" => "Hello there!"
                           ]
                  );
}

// Echo the response back to the API
header('Content-type: text/plain');
echo $response;
    }

    public function input(Request $request)
    {
       $sessionId   = $request->get('sessionId');
       $serviceCode = $request->get('serviceCode');
       $phoneNumber = $request->get('phoneNumber');
       $text        = $request->get('text');
        // use explode to split the string text response from Africa's talking gateway into an array.
        $ussd_string_exploded = explode("*", $text);
        // Get ussd menu level number from the gateway
        $level = count($ussd_string_exploded);
        if ($text == "") {
            // first response when a user dials our ussd code
            $response  = "CON Welcome to Online Classes at PrimesDevs \n";
            $response .= "1. Register \n";
            $response .= "2. About PrimesDev";
        }
        elseif ($text == "1") {
            // when user respond with option one to register
            $response = "CON Choose which framework to learn \n";
            $response .= "1. Django Web Framework \n";
            $response .= "2. Laravel Web Framework";
        }
        elseif ($text == "1*1") {
            // when use response with option django
            $response = "CON Please enter your first name";
        }
        elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 1 && $level == 3) {
            $response = "CON Please enter your last name";
        }
        elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 1 && $level == 4) {
            $response = "CON Please enter your email";
        }
        elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 1 && $level == 5) {
            // save data in the database
            $response = "END Your data has been captured successfully! Thank you for registering for Django online classes at PrimesDev.";
        }
        elseif ($text == "1*2") {
            // when use response with option Laravel
            $response = "CON Please enter your first name. ";
        }
        elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 3) {
            $response = "CON Please enter your last name";
        }
        elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 4) {
            $response = "CON Please enter your email";
        }
        elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 5) {
            // save data in the database
            $response = "END Your data has been captured successfully! Thank you for registering for Laravel online classes at PrimesDev.";
        }
        elseif ($text == "2") {
            // Our response a user respond with input 2 from our first level
            $response = "END At PrimesDev we try to find a good balance between theory and practical!.";
        }
        // send your response back to the API
        header('Content-type: text/plain');
        echo $response;
    }
}
