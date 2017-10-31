<?php

/**
 * @author Andreas Schrammel <schrammel@e-conomix.at>
 * @package Economix_CustomerGroupPaymentMethod
 * @copyright Copyright (c) 2017 E-CONOMIX GmbH (https://www.e-conomix.at)
 */
class Economix_CustomerGroupPaymentMethod_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Ensure that disable default payment methods flag is stored correctly on save
     *
     * @param Varien_Event_Observer $observer
     */
    public function storeDefaultDisableFlagToCustomerGroup(Varien_Event_Observer $observer)
    {
        $customerGroup = $observer->getEvent()->getObject();
        $disableDefault = Mage::app()->getRequest()
            ->getParam('economix_customergroup_paymentmethod_disable_default_methods');
        $customerGroup->setEconomixCustomergroupPaymentmethodDisableDefaultMethods($disableDefault);
    }
    
    /**
     * After saving user group, store the selected payment methods for this user group
     *
     * @param Varien_Event_Observer $observer
     */
    public function storePaymentMethodsToCustomerGroup(Varien_Event_Observer $observer)
    {
        $customerGroup = $observer->getEvent()->getObject();
        $customerGroupId = $customerGroup->getId();
        $paymentMethods = Mage::app()->getRequest()->getParam('economix_customergroup_paymentmethod');
        
        // Check already stored payment methods
        $presentMethods = Mage::helper('economix_customergrouppaymentmethod')
            ->getSelectedPaymentMethodsCollectionForCustomerGroup($customerGroupId);
        
        // Delete them
        foreach ($presentMethods as $presentMethod) {
            $presentMethod->delete();
        }
        
        // Store actual selected ones
        foreach ($paymentMethods as $methodCode) {
            $method = Mage::getModel('economix_customergrouppaymentmethod/methods');
            $method->setCustomerGroupId($customerGroupId);
            $method->setPaymentMethodCode($methodCode);
            $method->save();
        }
    }
    
    /**
     * Filter payment methods shown to customer dependent on his customer group.
     * Default methods are only disabled if the flag is set to true and at least one custom payment method is selected.
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterPaymentMethod(Varien_Event_Observer $observer)
    {
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $customerGroup = Mage::getModel('customer/group')->load($customerGroupId);
        $groupMethodsCollection = Mage::helper('economix_customergrouppaymentmethod')
            ->getSelectedPaymentMethodsCollectionForCustomerGroup($customerGroupId);
        
        $disableDefaultMethods = $customerGroup->getEconomixCustomergroupPaymentmethodDisableDefaultMethods();
        
        $groupMethods = array();
        foreach ($groupMethodsCollection as $groupMethod) {
            $groupMethods[] = $groupMethod->getPaymentMethodCode();
        }
        
        $method = $observer->getEvent()->getMethodInstance();
        
        $result = $observer->getEvent()->getResult();
        if (in_array($method->getCode(), $groupMethods)) {
            $result->isAvailable = true;
        } elseif (count($groupMethods) > 0 && $disableDefaultMethods) {
            $result->isAvailable = false;
        }
    }
}
