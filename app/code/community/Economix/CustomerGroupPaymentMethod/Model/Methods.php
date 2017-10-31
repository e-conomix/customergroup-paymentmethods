<?php

/**
 * @author Andreas Schrammel <schrammel@e-conomix.at>
 * @package Economix_CustomerGroupPaymentMethod
 * @copyright Copyright (c) 2017 E-CONOMIX GmbH (https://www.e-conomix.at)
 */
class Economix_CustomerGroupPaymentMethod_Model_Methods extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('economix_customergrouppaymentmethod/methods');
    }
}
