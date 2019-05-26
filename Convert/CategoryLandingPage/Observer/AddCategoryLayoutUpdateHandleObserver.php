<?php
namespace Convert\CategoryLandingPage\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Registry;

class AddCategoryLayoutUpdateHandleObserver implements ObserverInterface
{

    const LAYOUT_LANDING_CATEGORY_PAGE = 'catalog_category_view_landing';

    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function execute(EventObserver $observer)
    {
        $category = $this->registry->registry('current_category');
        if ($category) {
            $event = $observer->getEvent();
            $isLanding = $category->getData('is_landing');
            if ($isLanding) {
                $layout = $event->getData('layout');
                $layoutUpdate = $layout->getUpdate();
                $layoutUpdate->addHandle(self::LAYOUT_LANDING_CATEGORY_PAGE);
            }
        }
    }
}