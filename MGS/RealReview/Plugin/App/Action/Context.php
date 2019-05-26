<?php
namespace MGS\RealReview\Plugin\App\Action;

use MGS\RealReview\Model\Customer\Context as CustomerSessionContext;

class Context
{
    protected $customerSession;
    protected $httpContext;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Http\Context $httpContext
    ) {
        $this->customerSession = $customerSession;
        $this->httpContext = $httpContext;
    }

    public function aroundDispatch(
        \Magento\Framework\App\ActionInterface $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $customerId = $this->customerSession->getCustomerId();
        if(!$customerId) {
            $customerId = 0;
        }

        $this->httpContext->setValue(
            CustomerSessionContext::CONTEXT_CUSTOMER_ID,
            $customerId,
            false
        );

        return $proceed($request);
    }
}