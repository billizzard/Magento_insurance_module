<?php

class Itransition_Insurance_Model_Observer
{
    public function checkout_controller_onepage_save_shipping_method(Varien_Event_Observer $observer)
    {
        $isAccepted = (bool) Mage::app()->getRequest()->getParam('s_insurance');
        $isMethod = (bool) Mage::app()->getRequest()->getParam('shipping_method');
        $address = $observer->getQuote()->getShippingAddress();
        if ($isMethod) {
            if ($isAccepted) {
                $shippingMethod = $address->getShippingMethod();
                if ($shippingMethod) {
                    $shippingMethod = explode('_', $shippingMethod)[0];
                    $isEnabled = (bool)Mage::getStoreConfig('carriers/' . $shippingMethod . '/insuranceEnable');
                    if ($isEnabled) {
                        $type = Mage::getStoreConfig('carriers/' . $shippingMethod . '/insuranceType');
                        $value = Mage::getStoreConfig('carriers/' . $shippingMethod . '/insuranceValue');
                        $insuranceCost = Itransition_Insurance_Helper_Data::getInsuranceCost($address->getSubtotal(), $type, $value);
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

    public function adminhtml_block_system_config_init_tab_sections_before($observer) {
        /** @var Mage_Core_Model_Config_Element $section */
        $section = $observer->getSection();

        if ($section->label == 'Shipping Methods') {
            $groups = $section->groups;
            $carriers = Mage::getSingleton('shipping/config')->getAllCarriers();
            foreach ($carriers as $key => $carrier) {
                $groups->{$key}->fields->addChild('insuranceEnable');
                $groups->{$key}->fields->insuranceEnable->addChild('label', Mage::helper('insurance')->__('Include insurance'));
                $groups->{$key}->fields->insuranceEnable->addChild('frontend_type', 'select');
                $groups->{$key}->fields->insuranceEnable->addChild('source_model', 'adminhtml/system_config_source_yesno');
                $groups->{$key}->fields->insuranceEnable->addChild('sort_order', '2005');
                $groups->{$key}->fields->insuranceEnable->addChild('show_in_default', '1');
                $groups->{$key}->fields->insuranceEnable->addChild('show_in_website', '1');
                $groups->{$key}->fields->insuranceEnable->addChild('show_in_store', '0');

                $groups->{$key}->fields->addChild('insuranceType');
                $groups->{$key}->fields->insuranceType->addChild('label', Mage::helper('insurance')->__('Calculation type'));
                $groups->{$key}->fields->insuranceType->addChild('frontend_type', 'select');
                $groups->{$key}->fields->insuranceType->addChild('source_model', 'insurance/source_type');
                $groups->{$key}->fields->insuranceType->addChild('sort_order', '2006');
                $groups->{$key}->fields->insuranceType->addChild('show_in_default', '1');
                $groups->{$key}->fields->insuranceType->addChild('show_in_website', '1');
                $groups->{$key}->fields->insuranceType->addChild('show_in_store', '0');
                $groups->{$key}->fields->insuranceType->addChild('depends');
                $groups->{$key}->fields->insuranceType->depends->addChild('insuranceEnable', '1');

                $groups->{$key}->fields->addChild('insuranceValue');
                $groups->{$key}->fields->insuranceValue->addChild('label', Mage::helper('insurance')->__('Value'));
                $groups->{$key}->fields->insuranceValue->addChild('frontend_type', 'text');
                $groups->{$key}->fields->insuranceValue->addChild('sort_order', '2007');
                $groups->{$key}->fields->insuranceValue->addChild('show_in_default', '1');
                $groups->{$key}->fields->insuranceValue->addChild('show_in_website', '1');
                $groups->{$key}->fields->insuranceValue->addChild('show_in_store', '0');
                $groups->{$key}->fields->insuranceValue->addChild('can_be_empty', '0');
                $groups->{$key}->fields->insuranceValue->addChild('validate', 'required-entry validate-number');
                $groups->{$key}->fields->insuranceValue->addChild('depends');
                $groups->{$key}->fields->insuranceValue->depends->addChild('insuranceEnable', '1');
            }
        }
    }
}