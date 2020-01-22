# Welcome to the PHP MVC framework to extract Data from CSV and create XML

An MVC has been setup using PHP language (PHP Version used: 7.0). An standard directory structure has been followed for separating application/core/test etc. Folders.
PHP's OOPs concepts are used including namespaces, autoloading and polymorphism.

## Install dependency package
php composer.phar install

## Starting an application

1. Run **composer dump-autoload** to autoload with composer (This will generate the vendor directory and autoload.php file inside of it)
1. Run **composer install** to install the project dependencies as defined in composer.json file ('vendor/' folder will get updated with the dependencies)
1. 'App/' folder is the application directory which consists of the custom code (Controllers/Views/Models etc.)
1. Configure your web server to have the base folder as the web root.
1. Open [App/config.ini](App/config.ini) and update site configuration data.

## Steps to run Project (PHP CLI) - This will generate Output XML file 'files/Data.xml'

1. Run "php cli.php" command

## Command to run Unit Test Cases (phpunit will be installed via composer)

vendor/bin/phpunit

Note: The Code Coverage Report has been generated and kept under "coverage/" folder.

## Command to generate PHP Documentation and stored in "docs/api" folder (Run its index.html file to see the documentation)

./vendor/phpdocumentor/phpdocumentor/bin/phpdoc -d ./App/ -t ./docs/api


See below for more details.

##Command to generate autoloaded files in vendor

composer dump-autoload

## Autoload Dependencies (Add below line in your index.php or cli file)

require __DIR__ . '/vendor/autoload.php';

## Commands to download the project dependency packages from packagist.org

composer install  [To install the packages from packagist.org i.e. run instructions via composer.json - for fresh project setup]
composer update   [To update the packages from packagist.org i.e. to run updated instructions via composer.json]

## Configuration

Configuration settings are stored in the [App/config.ini](App/config.ini) file. You can access the settings in your code like this: `Config::get('show_errors')`. You can add your own configuration settings in here.

## Routing

The [Router](Core/Router.php) translates URLs into controllers and actions.

Routes are added with the `add` method. You can add fixed URL routes, and specify the controller and action, like this:

```php
$router->add('', ['controller' => 'CsvToXml', 'action' => 'index']);
Or, $router->add('csvtoxml/index', ['controller' => 'CsvToXml', 'action' => 'index']);
```

## Controllers

Controllers are stored in the `App/Controllers` folder. E.g.(App/Controllers/CsvToXml.php) included. Controller classes need to be in the `App/Controllers` namespace.

Controller classes contain methods that are the actions. To create an action, add the **`Action`** suffix to the method name e.g. `indexAction()`.

```php
View::render('CsvToXml/index.php', [
    'headings'    => 'CSV to XML Data',
    'data' => [],
]);
```

## Errors

If the `show_errors` configuration setting is set to `true`, full error detail will be shown in the browser if an error or exception occurs. If it's set to `false`, a log file will be generated containing the error details (e.g. logs/2019-12-16.txt).

## Public Files (Path: files - e.g. CSV, Images etc.)

The Input CSV file Data.csv and output XML Data.xml files are kept here. Note that permission is required on Data.xml file to generate with latest data on project run.

## Helper class (App/Utility.php)

Commonly used functions are kept here e.g. compareDates(), arrayToXML() etc.

```php
use App\Config;
Utility::arrayToXML(....);
```

## Desired Server Configurations

php.ini settings (can be increased further based on size of input CSV file):

memory_limit = 512M
max_execution_time = -1