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
 * To get Mgmt Fee and Calculate Maturity Amount for Policy Type 'C'
 * (via Polymorphism).
 *
 * @category PHPMVC
 * @package  PHPMVC_CSV2XML
 * @author   Saurabh Verma <saurabh.verma@test.com>
 * @license  Open Software License ("OSL")
 * @link     https://www.php.net
 */
class PolicyC implements PolicyInterface
{
    /**
     * The date the policy was taken out
     *
     * @var date 
     */
    private $_policyStartDate;
    
    /**
     * Membership rights, Y=Yes, N=No
     *
     * @var string 
     */
    private $_membership;
    /**
     * Management fee
     * 
     * @var float
     */
    private $mgmtFee = 0.07;
    /**
     * Helper function to deduce the Management Fee Percentage (3, 5 or 7%)
     * and get the "Actual Management Fee value" based on set criterion.
     *
     * @return float
     */
    public function getMgmtFee()
    {
        $mgmtFee = 0.00;
        if (!empty($this->_policyStartDate)) {
            $dtCompare = Utility::compareDates(
                $this->_policyStartDate,
                Config::get('policy_st_date_threshold')
            );

            if (($dtCompare >= 0) && ($this->_membership == 'Y')) {
                // Policy was taken out on or after 01/01/1990
                // and has membership rights.
                $mgmtFee = 0.07;
            }
        }
        return $mgmtFee;
    }
    /**
     * Helper function to deduce the Management Fee Percentage (3, 5 or 7%)
     * and get the "Actual Management Fee value" based on set criterion.
     *
     * @return float
     */
    public function getDiscretionaryBonus($discretionaryBonus)
    {
        if (!empty($this->_policyStartDate)) {
            $dtCompare = Utility::compareDates(
                $this->_policyStartDate,
                Config::get('policy_st_date_threshold')
            );

            if (($dtCompare >= 0) && ($this->_membership == 'Y')) {
                // Policy was taken out on or after 01/01/1990
                // and has membership rights.
                $discretionaryBonusAmount = $discretionaryBonus;
            } else {
                $discretionaryBonusAmount = 0;
            }
        } else {
            $discretionaryBonusAmount = 0;
        }

        return $discretionaryBonusAmount;
    }
    /**
     * Helper function to calculate the maturity value of the Policy.
     * Formula: ( ((premiums – management fee)
     *          + discretionary bonus if qualifying) * uplift )
     *
     * @param array $inputData Contains Parsed CSV Input data
     *
     * @return float
     */
    public function calcMaturity(array $inputData)
    {
        $maturity = 0.00;
        if (is_array($inputData)) {
            $policyStartDate = isset($inputData[1]) ? $inputData[1] : null;
            $premiums = isset($inputData[2]) ? $inputData[2] : 0;
            $membership = isset($inputData[3]) ? $inputData[3] : 'N'; // Y or N.
            $this->_policyStartDate = $policyStartDate;
            $this->_membership = $membership;
            $discretionaryBonus = $this->getDiscretionaryBonus(isset($inputData[4]) ? $inputData[4] : 0);
            $upliftPercentage = isset($inputData[5]) ? $inputData[5] : 0;
            $maturity = ($premiums-($premiums*$this->mgmtFee)+$discretionaryBonus)*(($upliftPercentage+100)/100);

            $maturity = number_format(
                (float) $maturity,
                2
            );
        }
        return $maturity;
    }
}
