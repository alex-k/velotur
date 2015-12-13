<?php
/**
 * Channel Validator for the pecl.php.net channel
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 * @version $Id: PECL.php,v 1.1 2005/03/03 02:37:59 cellog Exp $
 */
/**
 * This is the parent class for all validators
 */
require_once 'PEAR/Validate.php';
/**
 * Channel Validator for the pecl.php.net channel
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 * @version $Id: PECL.php,v 1.1 2005/03/03 02:37:59 cellog Exp $
 */
class PEAR_Validator_PECL extends PEAR_Validate
{
    function validateVersion()
    {
        return true;
    }
}
?>