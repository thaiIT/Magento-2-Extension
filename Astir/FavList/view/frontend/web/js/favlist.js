require([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'html2canvas',
    'pdfmake',
    'mage/url'
], function ($, modal, $t, html2canvasPlugin, pdfmake, mageurl) {
    'use strict';
    $(document).ready(function() {
    	$('body').on('click', '.toogle_this_elm', function() {
    		$(this).toggleClass('is_active');
    		$(this).closest('tr').next().slideToggle();
    	});
    	var checked = false;
	    function checkedAll() {
	        var aa = $('#amlist-table');
	        if (checked === false) {
	            checked = true;
	        } else {
	            checked = false;
	        }
	        var checkboxelm = aa.find('tbody input[type=checkbox]');
	        checkboxelm.each(function() {
	        	checkboxelm.prop('checked', checked);
	        });
	    }

	    $('body').on('click','#checkallinput', function(event) { 
	    	checkedAll();
	    });

    	$('body').on('click','.btn-search-item', function(event) {
	        var query_string = $('.input-search-item').val();
	        var form = $(this).closest('form');
	        if(query_string != "" || query_string === "undefined") {
	        	form.find('.message-custom').remove();
				$('.input-search-item').removeClass('mage-error');
		        $('#amlist-table tbody tr').each(function(){
		            var name_product = $(this).find('.product-name-amlist').html();
		            var code_product = $(this).find('.code-amlist').html();
		            var that = $(this);
		            var Uquery_string= query_string.toUpperCase();
		            if(name_product.indexOf(Uquery_string) != -1){
		            	scrollToDiv(that);
						event.preventDefault();
		            }
		            if(code_product.indexOf(Uquery_string) != -1){
		                scrollToDiv(that);
						event.preventDefault();
		            }
		        });
	        } else {
	        	validateInput(form);
	        }
    	});

    	var options = {
	        type: 'popup',
	        responsive: true,
	        innerScroll: true,
	        modalClass: 'modelmessage_fav confirm'
	    };

    	function updateSuggesstedStock(btn) {
    		var popupUpdate = modal(options, $('#popup-updated-message'));
    		var popupMultiUpdate = modal(options, $('#popup-updatemulti-message'));
    		var suggestedStockValArr = {};
			$("input[name='suggested_stock']").each(function() {
				var suggestedStockVal = $(this).val();
				if (suggestedStockVal != "" && parseInt(suggestedStockVal) > 0) {
					var suggestedStockId = $(this).attr('data-id');
					suggestedStockValArr[suggestedStockId] = suggestedStockVal;
				}
			});
			$.ajax({
	            url: mageurl.build('amlist/index/updateSuggestedStock'),
	            data: {itemData:suggestedStockValArr},
	            type: "POST",
	            dataType: 'json',
	            success: function(successData){
	            	if(btn.hasClass('btn-update-suggestedstock')) {
	            		$('body').trigger('processStop');
	    				$("#popup-updated-message").modal("openModal");
	    				btn.text('Update');
	    				btn.attr("disabled", false);
	    				ajaxReset();
	    			}
	    			if(btn.hasClass('btn-add-multiple')) {
	    				setTimeout(function() {
	    					$('body').trigger('processStop');
	    					$("#popup-updatemulti-message").modal("openModal");
		    				btn.text('Add Multiple');
    						btn.attr("disabled", false);
    						ajaxReset();
	    				}, 5000);
	    			}
	            }
	        });
    	}

    	$('body').on('click', '.btn-update-suggestedstock', function() {
    		$(this).text('Updating...');
    		$(this).attr("disabled", true);
    		$('body').trigger('processStart');
			updateSuggesstedStock($(this));
    	});

    	function saveAll (btn) {
    		var popupAddtocart = modal(options, $('#popup-addtocart-message'));
    		var numberFormCart = $('.favlist-form').length;
    		var index = 0;
    		var checkedQty = false;
    		$('.favlist-form').each(function() {
    			index++;
		    	var qty = $(this).find('input[name=qty]').val();
		    	if (parseInt(qty) > 0 && parseInt(qty) != "") {
		    		checkedQty = true;
		    		$(this).find('button').click();
		    	}
		    });
		    if(checkedQty && btn.hasClass('btn-save-all')) {
		    	btn.text('Adding...');
	    		btn.attr("disabled", true);
		    	btn.trigger('processStart');
		    }
	    	if (index == numberFormCart && checkedQty) {
	    		setTimeout(function() {
	    			if(btn.hasClass('btn-save-all')) {
	    				$('body').trigger('processStop');
	    				$("#popup-addtocart-message").modal("openModal");
	    				btn.text('Add All');
						btn.attr("disabled", false);
	    			}
	    		},5000);
	    	}
    	}

	    $('body').on('click','.btn-save-all',function() {
	    	saveAll($(this));
	    });

	    $('body').on('click','.btn-add-multiple', function() {
	    	$(this).text('Adding...');
    		$(this).attr("disabled", true);
    		$('body').trigger('processStart');
	    	saveAll($(this));
	    	updateSuggesstedStock($(this));
	    });

	    $('body').on('click','.btn-print',function() {
	    	var elmPrint = $('#table-item-detail');
	     	var printWindow = window.open('', '', '');
	     	elmPrint.find('.hide-on-print').addClass('hide-element');
	     	printWindow.document.write('<html><head><title>Folder Details | Physio Supplies, Rehabilitation, Sports Medicine Supplies, Physiotherapy Equipment & Supplies by ASTIR AUSTRALIA</title>');
	     	printWindow.document.write('<link rel="stylesheet" href="'+mageurl.build('')+'style/print_table_items.css">');
    		printWindow.document.write('<style type="text/css">.style1{width: 100%;}</style>');
    		printWindow.document.write('</head><body >');
		    printWindow.document.write(elmPrint.html());
		    printWindow.document.write('</body></html>');
		    printWindow.document.close();
		    setTimeout(function() { 
		    	printWindow.print();
		    }, 10);
        	printWindow.onfocus = function () { 
        		setTimeout(function () { 
        			printWindow.close(); 
        		}, 10); 
        	}
        	elmPrint.find('.hide-on-print').removeClass('hide-element');
	    });

		$('body').on('click','.form-validated-active button',function(event) {
			var form = $(this).closest('form');
			validateInput(form);
		});

		function validateInput(form) {
			var inputElm = form.find('.input-favlist input');
			form.find('.message-custom').remove();
			inputElm.removeClass('mage-error');
			if(inputElm.val() == "" || typeof(inputElm.val()) === "undefined") {
				var messageRequire = "<div class='message-custom'><div class='mage-error' generated='true'>" + $t('This is a required field.') + "</div></div>";
				if (form.hasClass('form-append-error')) {
					form.append(messageRequire);
				} else {
					inputElm.parent().append(messageRequire);
				}
				inputElm.addClass('mage-error');
				scrollToDiv(inputElm);
				event.preventDefault();
			}
		}

		function scrollToDiv(elm) {
			var headerScrool = $('.header-content-section');
			$('html,body').animate({
				scrollTop: elm.offset().top - headerScrool.height() - 50
			}, 'slow');
		}

		function ajaxReset() {
			var id = $('#listid').attr('list_id');
			$.ajax({
	            url: mageurl.build('amlist/index/listDetailFavAjax'),
	            data: {list_id:id},
	            type: 'POST',
	            dataType: 'json',
	            success: function(successData){
	                if(successData.success == 1) {
	                    $('.list-detail-session').html(successData.content).trigger('contentUpdated');
	                } else {
	                	$('.list-detail-session').html(successData.message).trigger('contentUpdated');
	                }
	            }
	        });
		}
    });
});
