<?php
class Itransition_Insurance_Block_Insurance extends Mage_Core_Block_Template
{
    public function getInsuranceData()
    {
        $info = [];
        /** @var Itransition_Insurance_Helper_Data $helper */
        $helper = Mage::helper('insurance');
        $data = Mage::getModel('checkout/session')->getQuote()->getData();
        $statuses = Mage::getModel('insurance/source_type')->toArray();
        $typePostfixes = $helper->getTypePostfix($data['quote_currency_code']);
        $activeMethods = Mage::getSingleton('shipping/config')->getActiveCarriers();

        foreach ($activeMethods as $key => $activeMethod) {
            $isEnabled = $helper->getIsEnabled($key);
            $type = $helper->getType($key);
            $value = $helper->getValue($key);
            $cost = $helper->getInsuranceCost($data['base_subtotal'], $type, $value);
            $message = $this->__('Cost') . ': ' . $cost . ' ' . $data['quote_currency_code'];

            if (isset($statuses[$type]) && isset($typePostfixes[$type])) {
                $message .= ' (' . $this->__($statuses[$type]) . ': ' . $value . ' ' . $typePostfixes[$type] . ')';
            }

            $info[] = [
                'method' => $key,
                'isEnabled' => $isEnabled,
                'message' => $message
            ];
        }

        return $info;
    }
}