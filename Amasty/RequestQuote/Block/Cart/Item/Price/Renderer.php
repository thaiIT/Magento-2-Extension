<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Block\Cart\Item\Price;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Amasty\RequestQuote\Model\Source\Status;

class Renderer extends \Magento\Checkout\Block\Item\Price\Renderer
{
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->priceCurrency->getCurrencySymbol();
    }

    /**
     * @param $price
     * @param bool $delimiter
     *
     * @return float
     */
    public function convertPrice($price, $delimiter = true)
    {
        $currency = null;
        $options = [];
        if ($this->getItem()->getQuote()->getStatus() != Status::CREATED) {
            $currency = $this->getItem()->getQuote()->getCurrency()->getQuoteCurrencyCode();
        } else {
            $options['symbol'] = '';
        }

        if (!$delimiter) {
            $options['format'] = '###0.00';
        }

        $formattedPrice = $this->priceCurrency
            ->getCurrency($this->getItem()->getQuote()->getStore(), $currency)
            ->formatPrecision($price, PriceCurrencyInterface::DEFAULT_PRECISION, $options, false);
        if (!$delimiter) {
            $formattedPrice = str_replace(',', '.', $formattedPrice);
        }

        return $formattedPrice;
    }

    /**
     * @return float|string
     */
    public function getInputPrice()
    {
        $price = !$this->getItem()->hasCustomPrice()
            ? $this->priceCurrency->convert($this->getItem()->getProduct()->getFinalPrice())
            : $this->getItem()->getCalculationPriceOriginal();

        return $price ? $this->convertPrice($price, false) : '';
    }
}
