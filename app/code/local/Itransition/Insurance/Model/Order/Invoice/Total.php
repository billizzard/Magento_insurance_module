<?php
class Itransition_Insurance_Model_Order_Invoice_Total extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order = $invoice->getOrder();
        $cost = $order->getItInsurance();

        if ($cost) {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $cost);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $cost);
        }

        return $this;
    }
}
