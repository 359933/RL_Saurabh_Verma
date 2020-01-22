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
 * @link     https://www.google.com
 */
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use App\Controllers\CsvToXml;
use App\Config;
use App\Utility;

/**
 * Unit Test for CsvToXml Controller actions.
 *
 * @category PHPMVC
 * @package  PHPMVC_CSV2XML
 * @author   Saurabh Verma <saurabh.verma@test.com>
 * @license  Open Software License ("OSL")
 * @link     https://www.php.net
 */
class CsvToXmlTest extends TestCase
{

    /**
     * Object for CsvToXml controller.
     *
     * @var \App\Controllers\CsvToXml
     */
    protected $controller;

    /**
     * The method setUp() and tearDown() are called before and
     * after every test method and are used to ensure that we have
     * a clean slate for each one.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->controller = new CsvToXml('');
        parent::setUp();
    }

    /**
     * Function tearDown() is called after every test method
     * and is used to ensure that we have a clean slate.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->controller = null;
    }
    
    /**
     * Test function for calculating Maturity.
     *
     * @return void
     */
    public function testIndexAction()
    {
        $this->assertTrue($this->controller->indexAction());
    }

    /**
     * Test function for calculating Maturity with test CSV file.
     *
     * @expectedException PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testIndexActionIsValid()
    {
        $this->expectException($this->controller->indexAction('test.csv'));
    }
    
    /**
     * Test function for generating XML.
     *
     * @return void
     */
    public function testUtility()
    {
        $xmldata[] = ['policy_number' => 'A100001', 'maturity' => '14980'];
        // Generate XMl using above result array.
        $this->assertTrue(
            Utility::arrayToXML(
                $xmldata,
                Config::get('public.output_file_path'), 'Data'
            )
        );
    }
    
    /**
     * Test function for config exception.
     *
     * @expectedException PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testConfigException()
    {
        $config = $this->getMockBuilder('Config')->getMock();
        $this->expectException(clone $config);
    }

    /**
     * Test function for calculating Maturity with test CSV data.
     *
     * @return void
     */
    public function testCalcMaturityPolicyA()
    {        
        // Test Maturity Value.
        $data = ["A100001", "01/06/1986", 10000, "Y", 1000, 40];
        $policyClassName = "\App\Controllers\PolicyA";

        if (class_exists($policyClassName)) {
            // Get a new Policy object (Polymorphism).
            $policyClass = new $policyClassName();
           
            // Assert function to test whether expected 
            // value is equal to actual or not.
            $this->assertEquals( 
                '14,980.00',
                $policyClass->calcMaturity($data),
                "Actual Maturity value is same as expected for Policy A100001."
            );
        }
    }

    /**
     * Test function for calculating Maturity with test CSV data.
     *
     * @return void
     */
    public function testCalcMaturityPolicyB()
    {        
        // Test Maturity Value.
        $data = ["B100001", "01/01/1995", 12000, "Y", 2000, 41];
        $policyClassName = "\App\Controllers\PolicyB";

        if (class_exists($policyClassName)) {
            // Get a new Policy object (Polymorphism).
            $policyClass = new $policyClassName();
           
            // Assert function to test whether expected 
            // value is equal to actual or not.
            $this->assertEquals( 
                '18,894.00',
                $policyClass->calcMaturity($data),
                "Actual Maturity value is same as expected for Policy B100001."
            );
        }
    }

    /**
     * Test function for calculating Maturity with test CSV data.
     *
     * @return void
     */
    public function testCalcMaturityPolicyC()
    {        
        // Test Maturity Value.
        $data = ["C100003", "01/01/1990", 17000, "Y", 3000, 46];
        $policyClassName = "\App\Controllers\PolicyC";

        if (class_exists($policyClassName)) {
            // Get a new Policy object (Polymorphism).
            $policyClass = new $policyClassName();
           
            // Assert function to test whether expected 
            // value is equal to actual or not.
            $this->assertEquals( 
                '27,462.60',
                $policyClass->calcMaturity($data),
                "Actual Maturity value is same as expected for Policy C100003."
            );
        }
    }

    /**
     * Test function for calculating Maturity with test CSV data (true value).
     *
     * @return void
     */
    public function testCalcMaturityIsTrue()
    {
        $data = ["A0001", "test", 0, "Y", 0, 10];
        
        // Construct Policy class name and check its existence.
        $policyType = isset($data[0][0])
                ? $data[0][0] : null;

        $policyClassName = "\App\Controllers\Policy" . $policyType;
        if (class_exists($policyClassName)) {
            // Get a new Policy object (Polymorphism).
            $policyClass = new $policyClassName();
           
            // Assert function to test whether expected 
            // value is equal to actual or not.
            $this->assertEquals(
                0.00,
                $policyClass->calcMaturity($data),
                "Actual Maturity value is same as expected."
            );
        }
    }

    /**
     * Test function for calculating Maturity with test CSV data (false value).
     *
     * @return void
     */
    public function testCalcMaturityIsFalse()
    {
        $data = ["A0001", "test", 0, "Y", 0, 10];
        
        // Construct Policy class name and check its existence.
        $policyType = isset($data[0][0])
                ? $data[0][0] : null;

        $policyClassName = "\App\Controllers\Policy" . $policyType;
        if (class_exists($policyClassName)) {
            // Get a new Policy object (Polymorphism).
            $policyClass = new $policyClassName();
           
            // Assert function to test whether expected 
            // value is equal to actual or not.
            $this->assertNotEquals(
                10000,
                $policyClass->calcMaturity($data)
            );
        }
    }

    /**
     * Test function for checking output xml file write permission.
     *
     * @return void
     */
    public function testFileWriting()
    {
        $filepath = Config::get('public.output_file_path');
        $this->assertTrue(is_writable($filepath));
    }

    /**
     * Test function for checking a valid output XML file.
     *
     * @return void
     */
    public function testValidXML()
    {
        $xml = new \XMLReader();
        $filepath = Config::get('public.output_file_path');
        $xml->open($filepath);

        $this->assertFalse($xml->isValid());
    }

    /**
     * Test function for valid policy type.
     *
     * @expectedException PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testPolicyInterfaceException()
    {
        $policyClassName = "\App\Controllers\PolicyD";
        $this->expectException(!class_exists($policyClassName));
    }

    /**
     * Test function for fetching CSV data (with column headers).
     *
     * @return void
     */
    public function testUtilityReadCSVIsValid()
    {
        $csv = Utility::readCSV(Config::get('public.input_file_path'), true);
        $this->assertNotEquals([], $csv);
    }

    /**
     * Test function for generating XML via CSV.
     *
     * @return void
     */
    public function testUtilityArrayToXML()
    {
        $xmldata = [];
        $xml = Utility::arrayToXML(
            $xmldata,
            Config::get('public.output_file_path'), 'TestData'
        );
        $this->assertNull($xml);
    }
    
    /**
     * Test Array To XML exception.
     *
     * @expectedException PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testUtilityArrayToXMLException()
    {
        $xmldata = ['john'];
        $xml = Utility::arrayToXML(
            $xmldata,
            Config::get('public.output_file_path'), 'doe'
        );
        $this->expectException($xml);
    }
}
