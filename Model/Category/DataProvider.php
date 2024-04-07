<?php
namespace Alex\CatalogSliderModule\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{

    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['content'][] = 'custom_image';

        return $fields;
    }
}
