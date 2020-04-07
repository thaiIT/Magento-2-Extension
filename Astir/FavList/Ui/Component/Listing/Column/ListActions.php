<?php

namespace Astir\FavList\Ui\Component\Listing\Column;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ListActions extends Column
{

    protected $urlBuilder;
    private $escaper;

    public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory, UrlInterface $urlBuilder, array $components = [], array $data = [] ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $name = "actions";
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['list_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl('amlist/index/edit', ['id' => $item['list_id']]),
                        'label' => __('Edit')
                    ];
                    $title = $this->getEscaper()->escapeHtml($name);
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl('amlist/index/delete', ['id' => $item['list_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $title),
                            'message' => __('Are you sure you wan\'t to delete a %1 record?', $title),
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }

    private function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }

        return $this->escaper;
    }

}
