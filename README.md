# surveygizmo-api-php-example
Using spacenate\SurveyGizmoApiWrapper

## Instructions
This example project uses Composer to manage and install packages, such as the spacenate/surveygizmo-api-php package that this example was created for, as well as defuse/php-encryption for encrypting access tokens once they have been acquired.

Visit https://getcomposer.org/ to download and install Composer.

With Composer installed on your machine, download or clone this git repository, and then use Composer to install the dependencies.

	git clone https://github.com/spacenate/surveygizmo-api-php-example.git
	cd surveygizmo-api-php-example
	composer install

Composer will download and install the packages required to a new directory called `vendor/`, and then you are ready to run the example script! PHP's built-in web server is handy for this.

	php -S 127.0.0.1:4000

Then visit http://127.0.0.1:4000/test.php to get started.
