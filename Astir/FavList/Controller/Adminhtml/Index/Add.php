<?php

namespace Astir\FavList\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Add extends Action
{
    public function execute()
    {
        $this->_forward('edit');
    }
}