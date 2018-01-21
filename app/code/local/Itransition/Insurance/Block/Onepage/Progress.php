<?php
class Itransition_Insurance_Block_Onepage_Progress extends Mage_Checkout_Block_Onepage_Progress
{
    public function getInsurance()
    {
        $i = $this->getQuote()->getItInsurance();
        return ($i > 0) ? $this->formatPrice($i) : 0;
    }
}