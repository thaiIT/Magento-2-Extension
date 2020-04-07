<?php
namespace THAIHOANG\Staff\Controller\Adminhtml\Index;
class Delete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = "THAIHOANG_Staff::staff_delete";
    public function execute()
    {
       $id = $this->getRequest()->getParam('id');
       if($id) {
           try {
               $model = $this->_objectManager->create('THAIHOANG\Staff\Model\Staff');
               $model->load($id);
               if($model->getId()) {
                   $imageHelper = $this->_objectManager->get('THAIHOANG\Staff\Helper\Image');
                   $imageHelper->removeImage($model->getAvatar());
                   $model->delete();
                   $this->messageManager->addSuccess(__("This staff has been deleted"));
                   return $this->_redirect("*/*/");
               } else {
                   $this->messageManager->addSuccess(__("This staff no longer exists"));
                   return $this->_redirect("*/*/");
               }
           } catch (\Exception $e) {
               $this->messageManager->addError(__($e->getMessage()));
               return $this->_redirect("*/*/");
           }
       } else {
           $this->messageManager->addError(__("We can not find any id to delete"));
           return $this->_redirect("*/*/");
       }
    }
}