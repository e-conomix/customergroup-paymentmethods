<?php

/**
 * @author Andreas Schrammel <schrammel@e-conomix.at>
 * @package Economix_CustomerGroupPaymentMethod
 * @copyright Copyright (c) 2017 E-CONOMIX GmbH (https://www.e-conomix.at)
 */
class Economix_CustomerGroupPaymentMethod_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Returns an array containing all payment methods available in this installation in following format:
     * array(
     *  array('value' => <i>paymentMethodCode</i>, 'label' => <i>paymentMethodTitle</i>)
     *  array('value' => <i>paymentMethodCode</i>, 'label' => <i>paymentMethodTitle</i>)
     *  ...
     * )
     *
     * @return array
     */
    public function getAllPaymentMethods()
    {
        $paymentMethods = Mage::getModel('payment/config')->getAllMethods();
        
        $methodList = array();
        foreach ($paymentMethods as $paymentCode => $paymentMethod) {
            $paymentTitle = Mage::getStoreConfig('payment/' . $paymentCode . '/title');
            $methodList[] = array('value' => $paymentCode, 'label' => $this->__($paymentTitle));
        }
        
        return $methodList;
    }
    
    /**
     * Returns a collection of all customergroup-paymentmethod mappings for a given customer group
     *
     * @param int $customerGroupId
     * @return Economix_CustomerGroupPaymentMethod_Model_Resource_Methods_Collection|Mage_Eav_Model_Entity_Collection_Abstract|Varien_Data_Collection_Db
     */
    public function getSelectedPaymentMethodsCollectionForCustomerGroup($customerGroupId)
    {
        return Mage::getModel('economix_customergrouppaymentmethod/methods')
            ->getCollection()
            ->addFieldToFilter('customer_group_id', $customerGroupId)
            ->load();
    }
    
    /**
     * Returns an array containing all selected payment method codes for a given customer group
     *
     * @param int $customerGroupId
     * @return array
     */
    public function getSelectedPaymentMethodsForCustomerGroup($customerGroupId)
    {
        $methods = array();
        
        foreach ($this->getSelectedPaymentMethodsCollectionForCustomerGroup($customerGroupId) as $method) {
            $methods[] = $method->getPaymentMethodCode();
        }
        
        return $methods;
    }
}