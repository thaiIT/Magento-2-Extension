define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'Magento_Ui/js/modal/confirm',
    'mage/mage',
    'jquery/ui'
], function ($, modal, $t, confirmation) {
    'use strict';

    $.widget('mgs.reviewDelete', {

        _create: function () {
            var self = this;
            var reviewdelete = $('#review-remove > a');
            $('#product-review-container').on('click', '#review-remove > a', function() {
                var reviewId = $(this).data('reviewid');
                var urlReload = $(this).data('url');
                confirmation({
                    content: $t("Do you want remove your review?"),
                    modalClass: "confirm-delete-review",
                    actions: {
                        confirm: function(){
                            self._ajaxSubmit(reviewId,urlReload);
                        },
                        cancel: function(){
                            return false;
                        },
                        always: function(){}
                    }
                });
                return false;
            });
        },

        _ajaxSubmit: function(reviewId,urlReload) {
            var self = this;
            $.ajax({
                url: 'mgsrealreview/index_ajax/delete',
                data: {review_id:reviewId},
                type: "POST",
                showLoader: true,
                dataType: 'json',
                success: function(response){
                    self._showResponse(response,urlReload);
                }
            });
        },

        _showResponse: function(response,urlReload) {
            var self = this;
            console.log('respon');
            if(response.success == 1) {
                $("#review-message-popup-ajax").addClass('message-success success');
                self._loadListReview(urlReload);
                $('#review-add div').html($t('Please reload page to add new review.'))
            } else {
                $("#review-message-popup-ajax").addClass('message-error error');
            }
            $('#review-message-popup-ajax .message-confirm-ajax').html(response.message);
            var optionsMessage = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                buttons:false,
                modalClass : 'message-ajax'
            };
            modal(optionsMessage, $('#review-message-popup-ajax'));
            $('#review-message-popup-ajax').modal('openModal');

        },

        _loadListReview: function (urlReload) {
            $('#product-review-container .block-content').addClass('review-ajax-loading');
            var url = urlReload;
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

    return $.mgs.reviewDelete;
});
