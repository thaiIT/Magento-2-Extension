<?php
namespace Kiwicommerce\Shippingbar\Block\Cart;

use Magento\Framework\View\Element\Template;

class Sidebar extends Template
{
    /**
    * @var \Kiwicommerce\Jobs\Helper\Data
    */
    private $helper;

    /**
    * Sidebar constructor.
    * @param Template\Context $context
    * @param \Kiwicommerce\Jobs\Helper\Data $helper
    * @param array $data
    */
    public function __construct(
       Template\Context $context,
       \Kiwicommerce\Shippingbar\Helper\Data $helper,
       array $data = []
    ) {
       parent::__construct($context, $data);
       $this->helper = $helper;
    }

    public function getConfigForShippingBar()
    {
       return $this->helper->getPriceForShippingBar();
    }
}