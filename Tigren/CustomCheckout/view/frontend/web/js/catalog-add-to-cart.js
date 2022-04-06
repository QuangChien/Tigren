/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/translate',
    'underscore',
    'Magento_Catalog/js/product/view/product-ids-resolver',
    'Magento_Catalog/js/product/view/product-info-resolver',
    'jquery-ui-modules/widget'
], function ($, $t, _, idsResolver, productInfoResolver,customerData) {
    'use strict';

    $.widget('mage.catalogAddToCart', {
        options: {
            processStart: null,
            processStop: null,
            bindSubmit: true,
            minicartSelector: '[data-block="minicart"]',
            messagesSelector: '[data-placeholder="messages"]',
            productStatusSelector: '.stock.available',
            addToCartButtonSelector: '.action.tocart',
            addToCartButtonDisabledClass: 'disabled',
            addToCartButtonTextWhileAdding: '',
            addToCartButtonTextAdded: '',
            addToCartButtonTextDefault: '',
            productInfoResolver: productInfoResolver
        },

        /** @inheritdoc */
        _create: function () {
            if (this.options.bindSubmit) {
                this._bindSubmit();
            }
            $(this.options.addToCartButtonSelector).attr('disabled', false);
        },

        /**
         * @private
         */
        _bindSubmit: function () {
            var self = this;

            if (this.element.data('catalog-addtocart-initialized')) {
                return;
            }

            this.element.data('catalog-addtocart-initialized', 1);
            this.element.on('submit', function (e) {
                e.preventDefault();
                self.submitForm($(this));
            });
        },

        /**
         * @private
         */
        _redirect: function (url) {
            var urlParts, locationParts, forceReload;

            urlParts = url.split('#');
            locationParts = window.location.href.split('#');
            forceReload = urlParts[0] === locationParts[0];

            window.location.assign(url);

            if (forceReload) {
                window.location.reload();
            }
        },

        /**
         * @return {Boolean}
         */
        isLoaderEnabled: function () {
            return this.options.processStart && this.options.processStop;
        },

        /**
         * get base url
         * @returns {string}
         */
        getBaseUrl: function () {
            return window.location.origin
        },

        /**
         * delte Quote Item
         */
        deleteQuoteItem: function () {
            const self = this;
            const urlDelete = self.getBaseUrl() + '/custom_checkout/quote/deletequoteitem';
            $.ajax({
                url: urlDelete,
                type: 'DELETE',
                dataType: 'json',
                success: function (result) {
                    if (result.success) {
                        window.location.reload()
                    }
                },
            });
        },

        /**
         * popup
         */
        popupModal: function () {
            const self = this;
            $('<div />').html('The product you add is a single purchase product or your cart has a single purchase product!\n' +
                'Please delete all orders or go to checkout page.')
                .modal({
                    title: 'Notice!',
                    autoOpen: true,
                    closed: function () {
                        // on close
                    },
                    buttons: [
                        {
                            text: 'Clear Cart',
                            click: function () {
                                self.deleteQuoteItem();
                            }
                        },
                        {
                            text: 'Go to Checkout',
                            click: function () {
                                window.location = window.checkout.checkoutUrl
                            }
                        }
                    ]
                });
        },

        /**
         * get Quote Item Total
         * @param {jQuery} form
         */
        getQuoteItemTotal: function (form) {
            const self = this;
            const urlQuote = self.getBaseUrl() + '/custom_checkout/quote/getquoteitem';
            $.ajax({
                url: urlQuote,
                type: 'GET',
                dataType: 'json',
                success: function (result) {
                    if (result.data > 0) {
                        const productId = form.attr('simple-product-id');
                        if (Object.values(result.productIds).includes(productId) && result.productIds.length < 2) {
                            self.ajaxSubmit(form);
                        } else {
                            self.popupModal();
                        }
                    } else {
                        self.ajaxSubmit(form);
                    }
                },
            });
        },

        /**
         *
         * @param {jQuery} form
         */
        checkMultipleOrderInQuote: function (form) {
            const self = this;
            const url = self.getBaseUrl() + '/custom_checkout/quote/getmultipleorderofquoteitem'
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (result) {
                    if (result.data.indexOf('0') >= 0) {
                        self.popupModal();
                    } else {
                        self.ajaxSubmit(form);
                    }
                },
            });
        },

        /**
         * Handler for the form 'submit' event
         *
         * @param {jQuery} form
         */
        submitForm: function (form) {
            // this.disableAddToCartButton(form);
            const self = this;
            const productId = form.attr('simple-product-id');
            const url = self.getBaseUrl() + '/rest/all/V1/tigren/product/';
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {
                    prdId: productId
                },
                success: function (result) {
                    const data = JSON.parse(result)
                    if (data.multiple_order === '0') {
                        self.getQuoteItemTotal(form);
                    } else {
                        self.checkMultipleOrderInQuote(form);
                    }
                },
            });
        },

        /**
         * @param {jQuery} form
         */
        ajaxSubmit: function (form) {
            var self = this,
                productIds = idsResolver(form),
                productInfo = self.options.productInfoResolver(form),
                formData;

            $(self.options.minicartSelector).trigger('contentLoading');
            self.disableAddToCartButton(form);
            formData = new FormData(form[0]);

            $.ajax({
                url: form.attr('action'),
                data: formData,
                type: 'post',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                /** @inheritdoc */
                beforeSend: function () {
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStart);
                    }
                },

                /** @inheritdoc */
                success: function (res) {
                    var eventData, parameters;

                    $(document).trigger('ajax:addToCart', {
                        'sku': form.data().productSku,
                        'productIds': productIds,
                        'productInfo': productInfo,
                        'form': form,
                        'response': res
                    });

                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStop);
                    }

                    if (res.backUrl) {
                        eventData = {
                            'form': form,
                            'redirectParameters': []
                        };
                        // trigger global event, so other modules will be able add parameters to redirect url
                        $('body').trigger('catalogCategoryAddToCartRedirect', eventData);

                        if (eventData.redirectParameters.length > 0 &&
                            window.location.href.split(/[?#]/)[0] === res.backUrl
                        ) {
                            parameters = res.backUrl.split('#');
                            parameters.push(eventData.redirectParameters.join('&'));
                            res.backUrl = parameters.join('#');
                        }

                        self._redirect(res.backUrl);

                        return;
                    }

                    if (res.messages) {
                        $(self.options.messagesSelector).html(res.messages);
                    }

                    if (res.minicart) {
                        $(self.options.minicartSelector).replaceWith(res.minicart);
                        $(self.options.minicartSelector).trigger('contentUpdated');
                    }

                    if (res.product && res.product.statusText) {
                        $(self.options.productStatusSelector)
                            .removeClass('available')
                            .addClass('unavailable')
                            .find('span')
                            .html(res.product.statusText);
                    }
                    self.enableAddToCartButton(form);

                },

                /** @inheritdoc */
                error: function (res) {
                    $(document).trigger('ajax:addToCart:error', {
                        'sku': form.data().productSku,
                        'productIds': productIds,
                        'productInfo': productInfo,
                        'form': form,
                        'response': res
                    });
                },

                /** @inheritdoc */
                complete: function (res) {
                    if (res.state() === 'rejected') {
                        location.reload();
                    }
                }
            });
        },

        /**
         * @param {String} form
         */
        disableAddToCartButton: function (form) {
            var addToCartButtonTextWhileAdding = this.options.addToCartButtonTextWhileAdding || $t('Adding...'),
                addToCartButton = $(form).find(this.options.addToCartButtonSelector);

            addToCartButton.addClass(this.options.addToCartButtonDisabledClass);
            addToCartButton.find('span').text(addToCartButtonTextWhileAdding);
            addToCartButton.attr('title', addToCartButtonTextWhileAdding);
        },

        /**
         * @param {String} form
         */
        enableAddToCartButton: function (form) {
            var addToCartButtonTextAdded = this.options.addToCartButtonTextAdded || $t('Added'),
                self = this,
                addToCartButton = $(form).find(this.options.addToCartButtonSelector);

            addToCartButton.find('span').text(addToCartButtonTextAdded);
            addToCartButton.attr('title', addToCartButtonTextAdded);

            setTimeout(function () {
                var addToCartButtonTextDefault = self.options.addToCartButtonTextDefault || $t('Add to Cart');

                addToCartButton.removeClass(self.options.addToCartButtonDisabledClass);
                addToCartButton.find('span').text(addToCartButtonTextDefault);
                addToCartButton.attr('title', addToCartButtonTextDefault);
            }, 1000);
        }
    });

    return $.mage.catalogAddToCart;
});
