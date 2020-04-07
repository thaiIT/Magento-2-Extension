<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Test\Unit\Observer\Frontend;

use Amasty\RequestQuote\Observer\Frontend\HidePrice;
use Amasty\RequestQuote\Test\Unit\Traits\ObjectManagerTrait;
use Amasty\RequestQuote\Test\Unit\Traits\ReflectionTrait;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;

/**
 * Class HidePriceTest
 *
 * @see HidePrice
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * phpcs:ignoreFile
 */
class HidePriceTest extends \PHPUnit\Framework\TestCase
{
    use ObjectManagerTrait;
    use ReflectionTrait;

    /**
     * @var HidePrice
     */
    private $model;

    /**
     * @covers HidePrice::execute
     *
     * @dataProvider executeDataProvider
     *
     * @throws \ReflectionException
     */
    public function testExecute($itemsData, $resultsData)
    {
        $hidePriceProvider = $this->createMock(\Amasty\RequestQuote\Model\HidePrice\Provider::class);
        $hidePriceProvider->expects($this->any())->method('isHidePrice')->willReturnCallback(function ($product) {
            return $product->getHidePrice();
        });
        $this->model = $this->getObjectManager()->getObject(HidePrice::class, ['hidePriceProvider' => $hidePriceProvider]);

        $items = [];
        foreach ($itemsData as $itemData) {
            $itemData['product'] = $this->getObjectManager()->getObject(Product::class, ['data' => $itemData['product']]);
            $items[$itemData['id']] = $this->getObjectManager()->getObject(DataObject::class, [
                'data' => $itemData
            ]);
        }

        $observer = $this->getObjectManager()->getObject(Observer::class, ['data' => [
            'items' => $items
        ]]);

        $this->model->execute($observer);

        foreach ($resultsData as $itemId => $result) {
            $this->assertEquals($result, $items[$itemId]->getCustomPrice());
        }
    }

    /**
     * Data provider for execute test
     * @return array
     */
    public function executeDataProvider()
    {
        return [
            [
                [
                    [
                        'id' => 2,
                        'custom_price' => 10,
                        'original_custom_price' => 10,
                        'product' => [
                            'hide_price' => true
                        ]
                    ],
                    [
                        'id' => 3,
                        'custom_price' => 10,
                        'original_custom_price' => 10,
                        'product' => [
                            'hide_price' => false
                        ]
                    ]
                ],
                [
                    2 => 0,
                    3 => 10
                ]
            ]
        ];
    }
}
