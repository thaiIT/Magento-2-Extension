<?php

namespace THAIHOANG\Staff\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class SaveData implements ObserverInterface
{
    protected $_logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $model = $observer->getModel();
        $this->_logger->info("Staff Edited: " . $model->getName());
    }
}