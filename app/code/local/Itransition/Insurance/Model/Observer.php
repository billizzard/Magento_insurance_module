<?php

class Itransition_Insurance_Model_Observer
{
    /**
     * Change total value after choice shipping method
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function checkoutControllerOnepageSaveShippingMethod(Varien_Event_Observer $observer)
    {
        $isAccepted = (bool) Mage::app()->getRequest()->getParam('s_insurance');
        $isMethod = (bool) Mage::app()->getRequest()->getParam('shipping_method');
        /** @var Itransition_Insurance_Helper_Data $helper */
        $helper  = Mage::helper('insurance');
        $address = $observer->getQuote()->getShippingAddress();

        if ($isMethod) {

            if ($isAccepted && $helper->isInsuranceEnabled()) {
                $shippingMethod = $address->getShippingMethod();

                if ($shippingMethod) {
                    $shippingMethod = explode('_', $shippingMethod)[0];
                    $isEnabled = (bool)$helper->getIsEnabled($shippingMethod);

                    if ($isEnabled) {
                        $type = $helper->getType($shippingMethod);
                        $value = $helper->getValue($shippingMethod);
                        $insuranceCost = $helper->getInsuranceCost($address->getSubtotal(), $type, $value);
                        $quote = $address->getQuote();
                        $quote->setItInsurance($insuranceCost);
                        $address->setItInsurance($insuranceCost);
                    }
                }

            } else {
                $quote = $address->getQuote();
                $quote->setItInsurance(0);
                $address->setItInsurance(0);
            }
        }
        return $this;
    }

    /**
     * Change total after change quantity or shipping method in cart
     * @param $observer
     */
    public function checkoutCartSaveAfter($observer)
    {
        $quote = $observer->getCart()->getQuote();
        $address = $quote->getShippingAddress();
        $shippingMethod = $address->getShippingMethod();
        $helper  = Mage::helper('insurance');

        if ($shippingMethod) {
            $shippingMethod = explode('_', $shippingMethod)[0];
            $isEnabled = (bool)$helper->getIsEnabled($shippingMethod);

            if ($isEnabled) {
                $type = $helper->getType($shippingMethod);
                $value = $helper->getValue($shippingMethod);

                $insuranceCost = $helper->getInsuranceCost($address->getSubtotal(), $type, $value);
                $oldInsurance = $address->getItInsurance();

                $quote->setItInsurance($insuranceCost);
                $address->setItInsurance($insuranceCost);
                $address->setGrandTotal($address->getGrandTotal() + $insuranceCost - $oldInsurance);
                $address->setBaseGrandTotal($address->getBaseGrandTotal() + $insuranceCost - $oldInsurance);
            }
        }
    }
}