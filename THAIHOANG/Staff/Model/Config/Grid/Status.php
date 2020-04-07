<?php
namespace THAIHOANG\Staff\Model\Config\Grid;
use Magento\Framework\Data\OptionSourceInterface;
class Status implements OptionSourceInterface 
{
	public function toOptionArray(){
		$option = [
			[
				'label' => __('Enabled'),
				'value' => 1
			],
			[
				'label' => __('Disabled'),
				'value' => 0
			]
		];
		return $option;
	}
}