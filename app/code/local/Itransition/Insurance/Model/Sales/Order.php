<?php
class Itransition_Insurance_Model_Sales_Order extends Mage_Sales_Model_Order {

    public function getShippingDescription()
    {
        $insurance = $this->getItInsurance();
        $desc = Mage::helper('insurance')->__('Insurance') . ' - ' . $insurance . ' ' . $this->getOrderCurrencyCode() . '; ';
        $desc .= parent::getShippingDescription();
        return (string) $desc;
    }
}