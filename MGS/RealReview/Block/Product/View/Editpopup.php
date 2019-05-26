<?php

namespace MGS\RealReview\Block\Product\View;

class Editpopup extends \Magento\Framework\View\Element\Template
{

    public function getProId()
    {
        return $this->getRequest()->getParam('id', false);
    }
}