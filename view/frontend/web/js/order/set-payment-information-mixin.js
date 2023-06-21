define([
    'jquery',
    'mage/utils/wrapper',
    'Ultraplugin_OrderComment/js/order/order-comment-assigner'
], function ($, wrapper, orderCommentAssigner) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, messageContainer, paymentData) {
            orderCommentAssigner(paymentData);

            return originalAction(messageContainer, paymentData);
        });
    };
});
