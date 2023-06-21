define([
    'jquery',
    'uiComponent'
], function ($, Component) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Ultraplugin_OrderComment/order-comment',
            paymentMethodSelector: '.payment-method._active',
            orderCommentSelector: '.input-text.up_order_comment'
        },
        isEnabled: window.checkoutConfig.up_order_comment.is_enabled,
        commentLabel: window.checkoutConfig.up_order_comment.comment_label,
        commentPlaceholder: window.checkoutConfig.up_order_comment.comment_placeholder,
        initialize: function () {
            this._super();
            this._bind();
        },
        _bind: function () {
            var self = this;
            $(document).on('click', this.orderCommentSelector, function () {
                var paymentMethodCode = $(self.paymentMethodSelector).find('input.radio').val();
                $(this).attr('id', paymentMethodCode);
            });
        }
    });
});
