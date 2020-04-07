<?php

namespace MGS\FShippingBar\Model\Config\Source;

class LayoutPosition implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
    {
        return [
        	['value' => 'position_page_top', 'label' => __('Page Top')], 
        	['value' => 'position_page_top_fixed', 'label' => __('Page top, fixed (sticky header)')],
            ['value' => 'position_page_bottom', 'label' => __('Page bottom')],
            ['value' => 'position_page_bottom_fixed', 'label' => __('Page bottom, fixed (sticky footer)')],
            ['value' => 'position_content_top', 'label' => __('Content top')],
            ['value' => 'position_content_bottom', 'label' => __('Content bottom')]
        ];

    }
}