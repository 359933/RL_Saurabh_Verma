<?php
/**
 * Controller file.
 *
 * PHP version 7.0
 *
 * @category PHPMVC
 * @package  PHPMVC_CSV2XML
 * @author   Saurabh Verma <saurabh.verma@test.com>
 * @license  Open Software License ("OSL")
 * @link     https://www.php.net
 */

namespace App\Controllers;

use App\Config;
use App\Utility;

// To execute script for unlimited time.
set_time_limit(0);

/**
 * To extract Data from CSV and generate processed XML file.
 * It also uses Polymorphic struction via PolicyInterface interface.
 *
 * @category PHPMVC
 * @package  PHPMVC_CSV2XML
 * @author   Saurabh Verma <saurabh.verma@test.com>
 * @license  Open Software License ("OSL")
 * @link     https://www.php.net
 */
class CsvToXml extends \Core\Controller
{

    /**
     * Action to generate XMl from CSV data.
     *
     * @param string $csv Input CSV File Path
     *
     * @return boolean
     */
    public function indexAction($csv = null)
    {
        try {
            $result = null;
            $xmldata = [];

            if (empty($csv)) {
                $csv = Config::get('public.input_file_path');
            }

            // Read CSV Data.
            // Note: Second param is to Exclude column headers of CSV.
            $csvRows = Utility::readCSV($csv, true);

            // Generate XML in desired format (Policy Number & Maturity Value).
            if (!empty($csvRows)) {
                foreach ($csvRows as $_row => $_rowData) {
                    $maturityValue = 0.00;

                    // Construct Policy class name and check its existence.
                    $policyType = isset($_rowData[0][0])
                            ? $_rowData[0][0] : null;

                    $policyClassName = "\App\Controllers\Policy" . $policyType;
                    if (class_exists($policyClassName)) {
                        // Get a new Policy object (Polymorphism).
                        $policyClass = new $policyClassName();
                        $maturityValue = $policyClass->calcMaturity($_rowData);
                    }

                    $xmldata[] = [ 'policy_number' => $_rowData[0],
                        'maturity' => $maturityValue];

                    // Pausing the running script for few micro-seconds.
                    if (($_row % 100) == 0) {
                        usleep(5000);
                    }
                }

                // Generate XMl using above result array.
                $result = Utility::arrayToXML(
                    $xmldata,
                    Config::get('public.output_file_path'), 'Data'
                );
            }
        } catch (\Exception $e) {
            return false;
            // die("Exception:: " . $e->getMessage());
        }

        return $result;
    }
}
