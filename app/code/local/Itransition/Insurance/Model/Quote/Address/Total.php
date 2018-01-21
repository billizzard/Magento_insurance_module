<?php

class Itransition_Insurance_Model_Quote_Address_Total extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    protected $_code = 'it_insurance';

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        $items = $this->_getAddressItems($address);
        if (!count($items)) {
            return $this;
        }

        if ($address->getItInsurance()) {
            $address->setGrandTotal($address->getGrandTotal() + $address->getItInsurance());
            $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getItInsurance());
        }
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amt = $address->getItInsurance();
        $address->addTotal(
            array (
                'code' => $this->getCode(),
                'title'=>Mage::helper('insurance')->__('Shipping Insurance'),
                'value' => $amt
            )
        );
        return $this;
    }
}
