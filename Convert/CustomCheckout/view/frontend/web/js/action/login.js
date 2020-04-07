/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/storage',
    'Magento_Ui/js/model/messageList',
    'Magento_Customer/js/customer-data',
    'mage/translate',
    'Magento_Checkout/js/model/step-navigator'
], function ($, storage, globalMessageList, customerData, $t, stepNavigator) {
    'use strict';

    var callbacks = [],

        /**
         * @param {Object} loginData
         * @param {String} redirectUrl
         * @param {*} isGlobal
         * @param {Object} messageContainer
         */
        action = function (loginData, redirectUrl, isGlobal, messageContainer) {
            messageContainer = messageContainer || globalMessageList;

            return storage.post(
                'customer/ajax/login',
                JSON.stringify(loginData),
                isGlobal
            ).done(function (response) {
                if (response.errors) {
                    $('#checkout-step-login').find('.messages').html('');
                    $('<div class="message message-error error"><div>'+response.message+'</div></div>').appendTo($('#checkout-step-login').find('.messages'));
                    callbacks.forEach(function (callback) {
                        callback(loginData);
                    });
                } else {
                    callbacks.forEach(function (callback) {
                        callback(loginData);
                    });
                    customerData.invalidate(['customer']);

                    if (response.redirectUrl) {
                        window.location.href = response.redirectUrl;
                    } else if (redirectUrl) {
                        window.location.href = redirectUrl;
                    } else {
                        location.reload();
                    }
                }
            }).fail(function () {
                $('#checkout-step-login').find('.messages').html('');
                $('<div class="message message-error error"><div>'+$t('Could not authenticate. Please try again later')+'</div></div>').appendTo($('#checkout-step-login').find('.messages'));
                callbacks.forEach(function (callback) {
                    callback(loginData);
                });
            });
        };

    /**
     * @param {Function} callback
     */
    action.registerLoginCallback = function (callback) {
        callbacks.push(callback);
    };

    return action;
});
