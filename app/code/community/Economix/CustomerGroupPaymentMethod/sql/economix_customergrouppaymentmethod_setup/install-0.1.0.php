<?php
/**
 * @author Andreas Schrammel <schrammel@e-conomix.at>
 * @package Economix_CustomerGroupPaymentMethod
 * @copyright Copyright (c) 2017 E-CONOMIX GmbH (https://www.e-conomix.at)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$conn->addColumn(
    $installer->getTable('customer/customer_group'),
    'economix_customergroup_paymentmethod_disable_default_methods',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned' => true,
        'nullable' => false,
        'default'  => '0',
        'comment'  => 'Disable default enabled payment methods'
    )
);

$conn->createTable(
    $this->getConnection()->newTable(
        $this->getTable('economix_customergrouppaymentmethod/economix_customergroup_paymentmethod')
    )
        ->addColumn(
            'id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'nullable' => false,
                'primary'  => true,
            )
        )
        ->addColumn(
            'customer_group_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
                'unsigned' => true,
            ),
            'Customer Group ID'
        )
        ->addColumn(
            'payment_method_code',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(
                'nullable' => false,
            ),
            'Payment Method Code'
        )
        ->addIndex(
            $installer->getIdxName(
                'economix_customergrouppaymentmethod/economix_customergroup_paymentmethod',
                array('customer_group_id', 'payment_method_code')
            ),
            array('customer_group_id', 'payment_method_code'),
            array('type' => 'unique')
        )
        ->addForeignKey(
            $installer->getFkName(
                'economix_customergrouppaymentmethod/economix_customergroup_paymentmethod',
                'customer_group_id',
                'customer/customer_group',
                'customer_group_id'
            ),
            'customer_group_id',
            $installer->getTable('customer/customer_group'),
            'customer_group_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Bind payment method to user group')
);

$installer->endSetup();
