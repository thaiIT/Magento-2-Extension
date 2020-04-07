<?php

namespace MGS\FShippingBar\Block\Adminhtml\Fields;

class Fonts extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $output = parent::_getElementHtml($element);
        $htmlId = $element->getHtmlId();
		$output.="<script type='text/javascript'>
			require([
				'jquery'
			], function(jQuery){
				(function($) {
					$('#".$htmlId."').change(function  () {
						$('<link />', {href: '//fonts.googleapis.com/css?family=' + $('#".$htmlId."').val(), rel: 'stylesheet', type:  'text/css'}).appendTo('head');
					}).keyup(function () {
						$('<link />', {href: '//fonts.googleapis.com/css?family=' + $('#".$htmlId."').val(), rel: 'stylesheet', type: 'text/css'}).appendTo('head');
					}).keydown(function () {
						$('<link />', {href: '//fonts.googleapis.com/css?family=' + $('#".$htmlId."').val(), rel: 'stylesheet', type: 'text/css'}).appendTo('head');
					});
					$('#".$htmlId."').trigger('change');
				})(jQuery);
			});
		</script>";
        return $output;
    }
}
