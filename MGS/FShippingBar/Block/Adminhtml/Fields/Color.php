<?php

namespace MGS\FShippingBar\Block\Adminhtml\Fields;

class Color extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $output = parent::_getElementHtml($element);
		$output.="
		<script type='text/javascript'>
			require([
				'jquery'
			], function(jQuery){
				(function($) {
					$('#".$element->getHtmlId()."').attr('data-hex', true).mColorPicker();
				})(jQuery);
			});
		</script>";
        return $output;
    }
}
