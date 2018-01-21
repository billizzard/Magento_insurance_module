<?php
class Itransition_Insurance_Helper_Data extends Mage_Core_Helper_Abstract {

    public static function getInsuranceCost($total, $type, $value)
    {
        switch ($type) {
            case Itransition_Insurance_Model_Source_Type::ABSOLUTE:
                return $value;
                break;

            case Itransition_Insurance_Model_Source_Type::PERCENT:
                return round($total / 100 * $value, 2);
                break;
        }

        return 0;
    }

    public function addTotals($class)
    {
        $order = $class->getOrder();
        $amount = $order->getItInsurance();
        $class->addTotalBefore(
            new Varien_Object(
                [
                    'code' => $class->getCode(),
                    'value' => $amount,
                    'base_value' => $amount,
                    'label' => $class->helper('insurance')->__('Insurance')
                ],
                'grand_total'
            )
        );
    }

    public function getTypePostfix($absolutePostfix)
    {
        return [
            Itransition_Insurance_Model_Source_Type::ABSOLUTE => $absolutePostfix,
            Itransition_Insurance_Model_Source_Type::PERCENT => '%',
        ];
    }

    public function getIsEnabled($shippingMethod)
    {
        return (bool) Mage::getStoreConfig('carriers/' . $shippingMethod . '/insuranceEnable');
    }

    public function getType($shippingMethod)
    {
        return Mage::getStoreConfig('carriers/' . $shippingMethod . '/insuranceType');
    }

    public function getValue($shippingMethod)
    {
        return Mage::getStoreConfig('carriers/' . $shippingMethod . '/insuranceValue');
    }


}