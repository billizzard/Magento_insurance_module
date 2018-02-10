<?php
class Itransition_Insurance_Block_Sales_Order_Creditmemo_Totals  extends Mage_Sales_Block_Order_Creditmemo_Totals
{
    protected $_code = 'it_insurance';

    protected function _initTotals()
    {
        parent::_initTotals();

        Mage::helper('insurance')->addTotals($this);

        return $this;
    }
}
