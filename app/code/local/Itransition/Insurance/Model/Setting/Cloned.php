<?php

class Itransition_Insurance_Model_Setting_Cloned extends Mage_Core_Model_Config_Data
{
    public function getPrefixes()
    {
        $prefixes = array();
        $carriers = Mage::getSingleton('shipping/config')->getAllCarriers();

        /** @var  $carrier Mage_Shipping_Model_Carrier_Abstract */
        foreach ($carriers as $key => $carrier) {
            $prefixes[] = array(
                'field' => sprintf('field_%s_', $carrier->getCarrierCode()),
                'label' => $carrier->getConfigData('title'),
            );
        }
        
        return $prefixes;
    }
}
