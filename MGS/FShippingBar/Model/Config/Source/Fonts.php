<?php

namespace MGS\FShippingBar\Model\Config\Source;

class Fonts implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
		return [
            ['value' => 'Roboto', 'label' => __('Roboto')],
            ['value' => 'Open+Sans', 'label' => __('Open Sans')],
            ['value' => 'Slabo+27px', 'label' => __('Slabo 27px')],
            ['value' => 'Lato', 'label' => __('Lato')],
            ['value' => 'Oswald', 'label' => __('Oswald')],
            ['value' => 'Roboto+Condensed', 'label' => __('Roboto Condensed')],
            ['value' => 'Source+Sans+Pro', 'label' => __('Source Sans Pro')],
            ['value' => 'Montserrat', 'label' => __('Montserrat')],
            ['value' => 'Raleway', 'label' => __('Raleway')],
            ['value' => 'PT+Sans', 'label' => __('PT Sans')],
            ['value' => 'Roboto+Slab', 'label' => __('Roboto Slab')],
            ['value' => 'Merriweather', 'label' => __('Merriweather')]
        ];
    }
}
