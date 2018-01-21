<?php
$installer = new Mage_Sales_Model_Resource_Setup('core_setup');
$entities = [
    'quote',
    'quote_address',
    'order'
];
$valueOptions = [
    'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'scale'     => 12,
    'precision' => 4,
    'visible'  => true,
    'required' => false
];

foreach ($entities as $entity) {
    $installer->addAttribute($entity, 'it_insurance', $valueOptions);
}
$installer->endSetup();