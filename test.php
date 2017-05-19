<?php
// Require the autoload file, so PHP can autoload packages installed via Composer
require 'vendor/autoload.php';

// URI this test script can be accessed at. PHP's built-in web server is handy for this
// php -S 127.0.0.1:4000
$test_page_URI = 'http://127.0.0.1:4000/test.php';

// Consumer key and secret obtained via SurveyGizmo OAuth Application Registration form
// Visit https://app.surveygizmo.com/account/restful-register while logged in to SurveyGizmo
$oauth_config = array(
    'consumer_key'               => 'aaaa0000aaaa0000aaaa0000',
    'consumer_secret'            => 'bbbbb1111bbbb11111bbb1111',
    'oauth_callback'             => $test_page_URI
);

// Create wrapper object
$sg = new spacenate\SurveyGizmoApiWrapper();

// Add OAuth configuration
$sg->oauth->configure($oauth_config);

// Get the current step we are on
if (isset($_GET['done'])) {
    $current_step = 'done';
} else if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
    $current_step = 2;
} else {
    $current_step = 1;
}

if ($current_step === 1) {
    // Get a request token from SurveyGizmo -- Note that this is returned as an associative array!
    $result = $sg->oauth->getRequestToken();

    // Redirect User to SurveyGizmo Authorize page to authorize access
    if (isset($result["oauth_token"])) {
        header('Location: https://restapi.surveygizmo.com/head/oauth/authenticate?oauth_token=' . $result['oauth_token']);
        die;
    } else {
        die('Uh oh! Failed to get a request token from SurveyGizmo. Check your consumer key and secret.');
    }
}

if ($current_step === 2) {
    // Grab parameters included when User is sent to callback URL
    $oauth_token = $_GET['oauth_token'];
    $oauth_verifier= $_GET['oauth_verifier'];

    // Exchange for access token and secret -- Note that this is returned as an associative array!
    $result = $sg->oauth->getAccessToken($oauth_token, $oauth_verifier);

    // Yay credentials! Note that at this time, SurveyGizmo OAuth tokens *CANNOT* be revoked, so store these encrypted in a safe place
    $access_token = $result['oauth_token'];
    $access_token_secret = $result['oauth_token_secret'];

    $key = Defuse\Crypto\Key::createNewRandomKey();
    file_put_contents('private.key', $key->saveToAsciiSafeString());

    $ciphertext = Defuse\Crypto\Crypto::encrypt($access_token.'---SEPARATOR---'.$access_token_secret, $key);
    file_put_contents('database.txt', $ciphertext);

    header('Location: ' . $test_page_URI . '?done=1');
    die;
}

if ($current_step === 'done') {
    $key = file_get_contents('private.key');
    $ciphertext = file_get_contents('database.txt');

    $key = Defuse\Crypto\Key::loadFromAsciiSafeString($key);
    $decrypted_string = Defuse\Crypto\Crypto::decrypt($ciphertext, $key);

    list($access_token, $access_token_secret) = explode('---SEPARATOR---', $decrypted_string);

    // To use an OAuth access token and secret, use the setCredentials() method, specifying the "oauth" type
    $sg->setCredentials($access_token, $access_token_secret, $type = 'oauth');

    if (!$sg->testCredentials()) {
        echo '💩 something went wrong';
        die;
    }

    $surveyList = json_decode($sg->Survey->getList());

    echo "Success! You have {$surveyList->total_count} surveys.";
}

