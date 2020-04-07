<?php

namespace Astir\FavList\Ui\Component\Listing\Column;

use \Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Customer\Model\CustomerFactory;

class FirstName extends Column
{
    protected $customerFactory;

    public function __construct(ContextInterface $context,UiComponentFactory $uiComponentFactory,array $components = [],array $data = [],CustomerFactory $customerFactory)
    {
        $this->customerFactory = $customerFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])){
            $name = $this->getData('name');
            foreach($dataSource['data']['items'] as &$item){
                $customerModel = $this->customerFactory->create()->load($item['customer_id']);
                $item[$name] = $customerModel->getFirstname() ? $customerModel->getFirstname() : '';
            }
        }
        return $dataSource;
    }

}