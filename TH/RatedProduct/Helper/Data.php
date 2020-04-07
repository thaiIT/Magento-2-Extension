<?php
namespace TH\RatedProduct\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper

{
    protected $imageHelper;
    protected $_objectmanager;
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\ObjectManagerInterface $objectmanager
    ) {
        $this->imageHelper = $imageHelper;
        $this->_objectmanager = $objectmanager;
        parent::__construct($context);
    }
     
    public function getImageProduct($product) {
        return $this->imageHelper
                    ->init($product, 'new_products_content_widget_grid')
                    ->setImageFile($product->getFile())
                    ->resize(100, 100)
                    ->getUrl();
    }

    public function subtext($text,$num) {
        if (strlen($text) <= $num) {
            return $text;
        }
        $text= substr($text, 0, $num);
        if ($text[$num-1] == ' ') {
            return trim($text)."...";
        }
        $x  = explode(" ", $text);
        $sz = sizeof($x);
        if ($sz <= 1)   {
            return $text."...";
        }
        $x[$sz-1] = '';
        return trim(implode(" ", $x))."...";
    }
}