define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'mage/mage',
    'jquery/ui'
], function ($, modal, $t) {
    'use strict';

    $.widget('mgs.reviewEditPopup', {
        options: {
            reviewedit: '#review-form-edit'
        },

        _create: function () {
            var self = this,
                reviewedit_options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: this.options.popupTitle,
                    buttons: false,
                    modalClass : 'review-edit-popup'
                };

            modal(reviewedit_options, this.element);
            var ajax = this.options.useAjax;
            $('body').on('click', '.review-edit a', function() {
                if (ajax == "yes") {
                    self._loadData();
                } else {
                    $(self.options.reviewedit).modal('openModal');
                    self._setStyleCss(self.options.innerWidth);
                }
                return false;
            });
            if (ajax == "yes") {
                this._ajaxSubmit();
            }
            this._resetStyleCss();
        },

        _loadData: function () {
            var self = this;
            $.ajax({
                url: 'mgsrealreview/index_ajax/loadEditPopupData',
                type: "POST",
                dataType: 'json',
                data: {product_id: this.options.productId},
                showLoader: true,
                success: function(response){
                    if(response.success == 1) {
                        $("#review-form-edit").html(response.content);
                        $(self.options.reviewedit).modal('openModal');
                        self._setStyleCss(self.options.innerWidth);
                    } else {
                        self.element.modal('closeModal');
                        self._showResponse(response);
                    }
                }
            });
        },

        _setStyleCss: function(width) {
            width = width || 400;
            if (window.innerWidth > 786) {
                this.element.parent().parent('.modal-inner-wrap').css({'max-width': width+'px'});
            }
        },

        _resetStyleCss: function() {
            var self = this;
            $( window ).resize(function() {
                if (window.innerWidth <= 786) {
                    self.element.parent().parent('.modal-inner-wrap').css({'max-width': 'initial'});
                } else {
                    self._setStyleCss(self.options.innerWidth);
                }
            });
        },

        _ajaxSubmit: function() {
            var self = this,
                form = this.element.find('form');
            $(document).on('submit',form,function (e) {
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
                return false;
            });
        },

        _showResponse: function(response) {
            var self = this;
            if(response.success == 1) {
                $("#review-message-popup-ajax").addClass('message-success success');
                self._loadListReview();
            } else {
                $("#review-message-popup-ajax").addClass('message-error error');
            }
            $('#review-message-popup-ajax .message-confirm-ajax').html(response.message);
            $('body').loader().loader('hide');
            self.element.modal('closeModal');
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
            var page = $.trim($('#customer-reviews .review-items+.review-toolbar .item.current strong.page span:not(.label)').text());
            var url = this.options.urlListAjax+'/?p='+page;
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

    return $.mgs.reviewEditPopup;
});
