<?php 
namespace THAIHOANG\Staff\Controller\Adminhtml\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Index extends \Magento\Backend\App\Action {
	protected $_resultPageFatory;
	public function __construct(Context $context, PageFactory $pageFactory) {
		parent::__construct($context);
		$this->_resultPageFatory = $pageFactory;
	}
	public function execute() {
		$resultPage = $this->_resultPageFatory->create();
		$resultPage->setActiveMenu("THAIHOANG_Staff::staff");
		$resultPage->getConfig()->getTitle()->prepend(__("Staff Manager"));
		

		// $name=["Bui Cong Thang","Nguyen Van Tien","Chu Van Binh","Nguyen Cao Truong"];
		// $email=["buicongthang@gmail.com","nguyenvantien@gmail.com","chuvanbinh@gmail.com","nguyencaotruong@gmail.com"];
		// $phone=["111","112","113","114","115"];
		// $position=["member","member","member","member"];
		// $avatar=["staff/01.jpg","staff/02.jpg","staff/03.jpg","staff/04.jpg"];
		// for ($i=0; $i < 4; $i++) { 
		// 	$staffModel = $this->_objectManager->create("THAIHOANG\Staff\Model\Staff");
		// 	$staffModel->addData([
		// 		"name"=>$name[$i],
		// 		"email"=>$email[$i],
		// 		"phone"=>$phone[$i],
		// 		"position"=>$position[$i],
		// 		"avatar"=>$avatar[$i],
		// 		"status"=> rand(0,1),
		// 	])->save();
		// }
		
		return $resultPage;
	}
}