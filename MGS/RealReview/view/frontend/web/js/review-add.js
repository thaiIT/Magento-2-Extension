define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'mage/mage',
    'jquery/ui'
], function ($, modal, $t) {
    'use strict';

    $.widget('mgs.reviewAdd', {
        _create: function () {
            var self = this;
            this._ajaxSubmit();
        },

        _ajaxSubmit: function() {
            var self = this,
                form = this.element.find('form'),
                inputElement = form.find('input');

            form.submit(function (e) {
                if (form.validation('isValid')) {
                    $.ajax({
                        url: $(e.target).attr('action'),
                        data: $(e.target).serializeArray(),
                        showLoader: true,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            self._showResponse(response);
                        }
                    });
                }
                return false;
            });
        },

        _showResponse: function(response) {
            var self = this;
            if(response.success == 1) {
                $("#review-message-popup-ajax").addClass('message-success success');
                if (this.options.autoApprove == "yes") {
                    self._loadListReview();
                }
                $('#review-add').html('<div>'+response.message+'</div>');
                $('#review-add').addClass('message success');
            } else {
                $("#review-message-popup-ajax").addClass('message error');
            }
            $('#review-message-popup-ajax .message-confirm-ajax').html(response.message);
            $('body').loader().loader('hide');
            var message_options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                buttons:false,
                modalClass : 'message-ajax'
            };
            modal(message_options, $('#review-message-popup-ajax'));
            $('#review-message-popup-ajax').modal('openModal');

        },

        _loadListReview: function () {
            $('#product-review-container .block-content').addClass('review-ajax-loading');
            var url = this.options.urlListAjax;
            var fromPages = true;
            function processReviews(url, fromPages) {
                $.ajax({
                    url: url,
                    cache: true,
                    dataType: 'html'
                }).done(function (data) {
                    $('#product-review-container').html(data);
                    $('[data-role="product-review"] .pages a').each(function (index, element) {
                        $(element).click(function (event) {
                            processReviews($(element).attr('href'), true);
                            event.preventDefault();
                        });
                    });
                }).complete(function () {
                    if (fromPages == true) {
                        $('html, body').animate({
                            scrollTop: $('#reviews').offset().top - 50
                        }, 300);
                    }
                });
            }
            processReviews(url, fromPages);
        }

    });

    return $.mgs.reviewAdd;
});
