<?php
/**
 * PHP CLI file.
 *
 * PHP version 7.0
 *
 * @category PHPMVC
 * @package  PHPMVC_CSV2XML
 * @author   Saurabh Verma <saurabh.verma@test.com>
 * @license  Open Software License ("OSL")
 * @link     https://www.google.com
 */

if (php_sapi_name() !== 'cli') {
    exit;
}
/**
* Composer
*/
require __DIR__ . '/vendor/autoload.php';


/**
* Error and Exception handling
*/
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

use App\Controllers\CsvToXml;
use App\Config;

if (($argc > 1) && in_array($argv[1], array('--help', '-help', '-h', '-?'))) {
?>

This is a command line PHP script.

  Usage:
  <?php echo $argv[0]; ?> <option>

  <option> can be some word you would like
  to print out. With the --help, -help, -h,
  or -? options, you can get this help.

<?php
} else {
    /**
     * Calling Controller Action function.
     */
    $dataController = new CsvToXml('');

    print "\n*********** Result: ***********\n";
    $result = $dataController->indexAction();

    // Show confirmation message.
    if ($result) {
        $result = "Desired XML File has been generated successfully (Refer: "
        . Config::get('public.output_file_path') . "')\n\n";
    }

    print $result;
}