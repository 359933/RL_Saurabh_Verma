<?php
/**
 * Front controller invokes valid route.
 *
 * PHP version 7.0
 *
 * @category PHPMVC
 * @package  PHPMVC_CSV2XML
 * @author   Saurabh Verma <saurabh.verma@test.com>
 * @license  Open Software License ("OSL")
 * @link     https://www.php.net
 */
/**
 * Restricting access vi Browser. Run 'test_cli' on command line
 */
if (php_sapi_name() !== 'cli') {
    die('<strong>No direct access is allowed. Please run via CLI.</strong>');
}
