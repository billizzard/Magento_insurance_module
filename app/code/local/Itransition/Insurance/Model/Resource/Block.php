<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12.1.18
 * Time: 11.45
 */

class  Itransition_Insurance_Model_Resource_Block extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('insurance/block', 'block_id');
    }
}