define([
    'jquery',
    'uiComponent',
    'ko',
    'Magento_Customer/js/customer-data'
], function ($, Component, ko, customerData) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'MGS_FShippingBar/fsbheader'
            },
            initialize: function () {
                this._super();
                this.cart = customerData.get('cart');
            },
            before_achieved_goal: function() {
                var ccart = customerData.get('cart');
                var mgsBefore = ccart().before_achieved_goal;
                if(mgsBefore) {
                    mgsBefore = mgsBefore.replace("{{ruleGoalLeft}}",'<span class="goal">'+ccart().awayfromdhippingprice+'</span>');
                }
                return mgsBefore;
            },
            empty_goal: function() {
                var ccart = customerData.get('cart');
                var mgsEmptyGoal= ccart().empty_goal;
                if(mgsEmptyGoal) {
                    mgsEmptyGoal = mgsEmptyGoal.replace("{{ruleGoal}}",'<span class="goal">'+ccart().awayfromdhippingprice+'</span>');
                }
                return mgsEmptyGoal;
            }
        });
    }
);