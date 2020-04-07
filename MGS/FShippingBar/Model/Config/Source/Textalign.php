<?php

namespace MGS\FShippingBar\Model\Config\Source;

class Textalign implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
		return [
            ['value' => 'Left', 'label' => __('Left')],
            ['value' => 'Center', 'label' => __('Center')],
            ['value' => 'Right', 'label' => __('Right')]
        ];
    }
}
