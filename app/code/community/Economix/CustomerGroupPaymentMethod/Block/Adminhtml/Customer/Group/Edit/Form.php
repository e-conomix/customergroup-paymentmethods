<?php

/**
 * @author Andreas Schrammel <schrammel@e-conomix.at>
 * @package Economix_CustomerGroupPaymentMethod
 * @copyright Copyright (c) 2017 E-CONOMIX GmbH (https://www.e-conomix.at)
 */
class Economix_CustomerGroupPaymentMethod_Block_Adminhtml_Customer_Group_Edit_Form extends Mage_Adminhtml_Block_Customer_Group_Edit_Form
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $form = $this->getForm();
    
        $this->addPaymentFieldset($form);
        
        $customerGroup = Mage::registry('current_group');
        if (Mage::getSingleton('adminhtml/session')->getCustomerGroupData()) {
            $form->addValues(Mage::getSingleton('adminhtml/session')->getCustomerGroupData());
            Mage::getSingleton('adminhtml/session')->setCustomerGroupData(null);
        } else {
            $form->addValues($customerGroup->getData());
        }
        
        $methods = Mage::helper('economix_customergrouppaymentmethod')
            ->getSelectedPaymentMethodsForCustomerGroup($customerGroup->getId());
        
        $form->addValues(array('economix_customergroup_paymentmethod' => $methods));
    }
    
    /**
     * Add fieldset for payment method
     *
     * @param $form
     */
    protected function addPaymentFieldset($form)
    {
        $fieldSet = $form->addFieldset(
            'payment_method',
            array('legend' => $this->__('Payment Methods'))
        );
        
        $fieldSet->addField(
            'economix_customergroup_paymentmethod_disable_default_methods',
            'select',
            array(
                'name'     => 'economix_customergroup_paymentmethod_disable_default_methods',
                'label'    => $this->__('Disable default methods'),
                'title'    => $this->__('Disable default methods'),
                'note'     => $this->__(
                    '"Yes" will disable all default enabled payment methods, ' .
                    'enabling <b>only</b> following selected payment methods'
                ),
                'values'   => array(
                    0 => $this->__('No'),
                    1 => $this->__('Yes')
                ),
                'class'    => 'required-entry',
                'required' => true
            )
        );
        
        $fieldSet->addField(
            'economix_customergroup_paymentmethod',
            'multiselect',
            array(
                'name'     => 'economix_customergroup_paymentmethod',
                'label'    => $this->__('Available Methods'),
                'title'    => $this->__('Available Methods'),
                'note'     => $this->__(
                    'Select available payment methods for this group<br /><b>ATTENTION:</b> ' .
                    'Selected methods have to be configured in System / Configuration / Sales / Payment Methods'
                ),
                'values'   => Mage::helper('economix_customergrouppaymentmethod')->getAllPaymentMethods(),
                'required' => false
            )
        );
    }
}
