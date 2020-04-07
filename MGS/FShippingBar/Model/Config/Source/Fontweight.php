<?php

namespace MGS\FShippingBar\Model\Config\Source;

class Fontweight implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
		return [
            ['value' => '100', 'label' => __('Thin, 100')],
            ['value' => '300', 'label' => __('Light, 300')],
            ['value' => '400', 'label' => __('Regular, 400')],
            ['value' => '500', 'label' => __('Medium, 500')],
            ['value' => '700', 'label' => __('Bold, 700')],
            ['value' => '900', 'label' => __('Black, 900')]
        ];
    }
}
