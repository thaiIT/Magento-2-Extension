var config = {
    map: {
        '*': {
         "Magento_Checkout/js/model/step-navigator": "Convert_CustomCheckout/js/model/step-navigator"
        }
    },
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/shipping': {
                'Convert_CustomCheckout/js/view/shipping-payment-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'Convert_CustomCheckout/js/view/shipping-payment-mixin': true
            }
        }
    }
};