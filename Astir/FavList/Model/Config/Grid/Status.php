<?php

namespace Astir\FavList\Model\Config\Grid;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface 
{
	public function toOptionArray(){
		$option = [
			[
				'label' => __('Yes'),
				'value' => 1
			],
			[
				'label' => __('No'),
				'value' => 0
			]
		];
		return $option;
	}
}