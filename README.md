# surveygizmo-api-php-example
Using spacenate\SurveyGizmoApiWrapper

## Instructions
This example project uses Composer to manage and install PHP packages. Visit https://getcomposer.org/ to download and install Composer.

With Composer installed on your machine, clone this git repository and use Composer to install dependencies, like so:

    git clone https://github.com/spacenate/surveygizmo-api-php-example.git &&
    cd surveygizmo-api-php-example &&
    composer install

Composer will download and install the required packages to a new directory called `vendor/`.

This example is specifically for using OAuth to authenticate API requests on a user's behalf. To run the example, you will need to edit `example.php` and update the `$oauth_config` array at the top of the script with your consumer key and secret.

    $oauth_config = array(
        'consumer_key'               => 'YOUR-KEY-HERE',
        'consumer_secret'            => 'YOUR-SECRET-HERE',

Now you are ready to run the example script! PHP's built-in web server is handy for this:

    php -S 127.0.0.1:4000

Then visit http://127.0.0.1:4000/example.php to get started. If step 1 works correctly, you will immediately be redirected to SurveyGizmo to authenticate and allow the OAuth request to be granted.

After your OAuth request has been granted, you'll be redirected back to the local example script with a new set of temporary tokens in the query string. In step 2, these temporary tokens are traded for non-expiring access tokens.

Once access tokens have been acquired, the example script encrypts the tokens. The encryption key would usually be stored in a config somewhere, and the encrypted tokens in a database, but for the purposes of this example both key and encrypted tokens are written to local files.

With tokens acquired, you can now proceed to make requests to the SurveyGizmo API, as demonstrated in step 'done'. Happy programming.

