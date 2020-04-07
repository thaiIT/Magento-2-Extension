<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Block\Adminhtml\Quote;

use Amasty\RequestQuote\Model\Source\Status;
use Magento\Directory\Model\Currency;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Store\Model\ScopeInterface;

class AbstractQuote extends \Magento\Backend\Block\Widget
{
    /**
     * @var \Amasty\RequestQuote\Model\Quote\Backend\Session
     */
    private $quoteSession;
    
    /**
     * @var \Magento\Sales\Helper\Admin
     */
    private $adminHelper;

    /**
     * @var \Amasty\RequestQuote\Helper\Data
     */
    private $configHelper;

    /**
     * @var OrderCollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\RequestQuote\Model\Quote\Backend\Session $quoteSession,
        \Magento\Sales\Helper\Admin $adminHelper,
        \Amasty\RequestQuote\Helper\Data $configHelper,
        OrderCollectionFactory $orderCollectionFactory,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->adminHelper = $adminHelper;
        $this->quoteSession = $quoteSession;
        $this->configHelper = $configHelper;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return \Magento\Sales\Helper\Admin
     */
    public function getAdminHeler()
    {
        return $this->adminHelper;
    }

    /**
     * @return \Amasty\RequestQuote\Model\Quote\Backend\Session
     */
    public function getSession()
    {
        return $this->quoteSession;
    }

    /**
     * @return \Amasty\RequestQuote\Api\Data\QuoteInterface
     */
    public function getQuote()
    {
        return $this->getSession()->getQuote();
    }

    /**
     * @return mixed
     */
    public function getPriceDataObject()
    {
        $obj = $this->getData('price_data_object');
        if ($obj === null) {
            return $this->getQuote();
        }
        return $obj;
    }

    /**
     * @param string $code
     * @param bool $strong
     * @param string $separator
     * @return string
     */
    public function displayPriceAttribute($code, $strong = false, $separator = '<br/>')
    {
        return $this->getAdminHeler()->displayPriceAttribute($this->getPriceDataObject(), $code, $strong, $separator);
    }

    /**
     * @param float $basePrice
     * @param float $price
     * @param bool $strong
     * @param string $separator
     * @return string
     */
    public function displayPrices($basePrice, $price, $strong = false, $separator = '<br/>')
    {
        return $this->getAdminHeler()->displayPrices(
            $this->getPriceDataObject(),
            $basePrice,
            $price,
            $strong,
            $separator
        );
    }

    /**
     * @return array
     */
    public function getQuoteTotalData()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getQuoteInfoData()
    {
        return [];
    }

    /**
     * @param mixed $store
     * @return string
     */
    public function getTimezoneForStore($store)
    {
        return $this->_localeDate->getConfigTimezone(
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store->getCode()
        );
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getExpiredDate()
    {
        return $this->getDateByKey('expired_date');
    }

    public function getReminderDate()
    {
        return $this->getDateByKey('reminder_date');
    }

    /**
     * @param $key
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getDateByKey($key)
    {
        $result = __('N/A');
        if ($this->getQuote()->getData($key)) {
            $result = $this->formatDate(
                $this->getQuote()->getData($key),
                \IntlDateFormatter::MEDIUM,
                true,
                $this->getTimezoneForStore($this->getQuote()->getStore())
            );
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isExpiredShow()
    {
        return $this->configHelper->getExpirationTime() !== null &&
            in_array($this->getQuote()->getStatus(), [Status::APPROVED, Status::EXPIRED]);
    }

    /**
     * @return bool
     */
    public function isReminderShow()
    {
        return $this->configHelper->getReminderTime() !== null &&
            in_array($this->getQuote()->getStatus(), [Status::APPROVED, Status::EXPIRED]);
    }

    /**
     * @return string
     */
    public function getAccountCreated()
    {
        $createdAt = $this->getQuote()->getCustomer()->getCreatedAt();

        return $this->formatDate(
            $createdAt,
            \IntlDateFormatter::MEDIUM,
            true,
            $this->getTimezoneForStore($this->getQuote()->getStore())
        );
    }

    /**
     * @return string
     */
    public function getAccountTotals()
    {
        /** @var OrderCollection $collection */
        $collection = $this->orderCollectionFactory->create()
            ->addFieldToFilter('state', ['neq' => \Magento\Sales\Model\Order::STATE_CANCELED])
            ->addFieldToFilter('customer_id', ['eq' => $this->getQuote()->getCustomerId()])
            ->removeAllFieldsFromSelect()
            ->addExpressionFieldToSelect(
                'base_grand_total',
                'SUM({{base_grand_total}})',
                'base_grand_total'
            );
        $result = $collection->fetchItem()->getBaseGrandTotal();

        return $this->convertPrice($result);
    }

    /**
     * @param $price
     * @return float|string
     */
    public function convertPrice($price)
    {
        $storeId = $this->getQuote()->getStoreId();

        return $this->priceCurrency->convertAndFormat(
            $price,
            false,
            null,
            $storeId,
            $this->configHelper->getDisplayCurrency($storeId)
        );
    }
}
