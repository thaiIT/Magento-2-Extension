<?php

namespace Astir\FavList\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Astir\FavList\Model\FavListFactory;
use Astir\FavList\Model\ItemFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Save extends Action
{

    protected $_favListFactory;
    protected $_itemFactory;
    protected $_request;
    protected $_date;

    public function __construct(Action\Context $context, FavListFactory $favListFactory, ItemFactory $itemFactory, RequestInterface $request, DateTime $date)
    {
        $this->_favListFactory = $favListFactory;
        $this->_itemFactory = $itemFactory;
        $this->_request = $request;
        $this->_date = $date;
        parent::__construct($context);
    }

    public function execute()
    {
        $request = $this->getRequest();
        if($request->getPost()) {
            $listModel = $this->_favListFactory->create();
            $listId = $request->getParam('list_id');
            $formData = $request->getPostValue();
            $urlRedirect="*/*/add";
            if($listId) {
                $listModel->load($listId);
                $urlRedirect="*/*/edit/id/$listId";
                $listModel->setData($formData);
                $this->itemReset($listId);
            } else {
                $listModel->setData($formData)->setCreatedAt($this->_date->gmtDate());
            }
            try {
                $listModel->save();
                if($productIds = $formData['amlist']['product_ids']) {
                   $this->addNewItem($listModel->getListId(),$productIds);
                }
                $this->messageManager->addSuccess(__('The folder has been saved.'));
                $this->_getSession()->setFormData(false);
                if($request->getParam("back")) {
                    return $this->_redirect("*/*/edit",["id"=>$listModel->getListId(),"_current"=>true]);
                }
                return $this->_redirect("*/*/");
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the folder.'));
            }
        }
        return $this->_redirect("*/*/");
    }

    public function itemReset($listId) {
        $iCollection = $this->_itemFactory->create()->getCollection()->addFieldToFilter('list_id', $listId);
        foreach ($iCollection as $item) {
            $this->_itemFactory->create()->load($item->getItemId())->delete();
        }

    }

    public function addNewItem($listId, $productIds) {
        $productIdsArr = json_decode($productIds,JSON_PRETTY_PRINT);
        foreach ($productIdsArr as $d) {
            $i = $this->_itemFactory->create();
            $i->setListId($listId);
            $i->setProductId($d['key']);
            $i->setQty($d['value']);
            $buyRequest = ['product' => (int) $d['key']];
            $i->setBuyRequest(serialize($buyRequest));
            $i->save();
        }
    }
}
