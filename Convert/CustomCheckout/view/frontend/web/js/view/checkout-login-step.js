define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/model/customer',
        'Convert_CustomCheckout/js/action/login',
        'mage/validation',
        'Magento_Checkout/js/model/authentication-messages',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (
        $,
        ko,
        Component,
        _,
        stepNavigator,
        customer,
        loginAction,
        validation,
        messageContainer,
        fullScreenLoader
    ) {

        'use strict';
        var checkoutConfig = window.checkoutConfig;
        return Component.extend({
            defaults: {
                template: 'Convert_CustomCheckout/checkout-login'
            },

            isVisible: ko.observable(true),
            isLogedIn: customer.isLoggedIn(),
            stepCode: 'login',
            stepTitle: 'Login',
            stepTitleMobile: 'Login',
            element: $('#checkout-step-login'),
            forgotPasswordUrl: checkoutConfig.forgotPasswordUrl,
            takeTestUrl: checkoutConfig.takeTestUrl,
            isGuestCheckoutAllowed: checkoutConfig.isGuestCheckoutAllowed,
            isCustomerLoginRequired: checkoutConfig.isCustomerLoginRequired,
            registerUrl: checkoutConfig.registerUrl,
            autocomplete: checkoutConfig.autocomplete,
            checkoutUrl: checkoutConfig.checkoutUrl,

            /**
             *
             * @returns {*}
             */
            initialize: function () {
                this._super();
                stepNavigator.registerStep(
                    this.stepCode,
                    null,
                    this.stepTitle,
                    this.isVisible,
                    _.bind(this.navigate, this),
                    1
                );
                if(customer.isLoggedIn())
                {
                    if (checkoutConfig.reloadOnBillingAddress != true) {
                        window.location.href = this.checkoutUrl+"#shipping";
                    }
                    
                    // this.navigateToNextStep();
                    // ko.observable(false);
                }
                return this;
            },

            navigate: function (step) {
                step && step.isVisible(true);
            },

            /**
             * navigateToNextStep
             */
            navigateToNextStep: function () {
                stepNavigator.next();
            },

            /**
             * Provide login action.
             *
             * @param {HTMLElement} loginForm
             */
            login: function (loginForm) {
                var loginData = {},
                    formDataArray = $(loginForm).serializeArray();

                formDataArray.forEach(function (entry) {
                    loginData[entry.name] = entry.value;
                });

                if ($(loginForm).validation() &&
                    $(loginForm).validation('isValid')
                ) {
                    fullScreenLoader.startLoader();
                    loginAction(loginData, checkoutConfig.checkoutUrl, undefined, messageContainer).always(function () {
                        fullScreenLoader.stopLoader();
                    });
                }
            }
        });
    }
);