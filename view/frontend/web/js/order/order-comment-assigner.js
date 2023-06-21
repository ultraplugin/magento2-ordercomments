define([
    'jquery'
], function ($) {
    'use strict';

    return function (paymentData) {
        if (paymentData['extension_attributes'] === undefined) {
            paymentData['extension_attributes'] = {};
        }
        var paymentMethodCode = $('.payment-method._active').find('input.radio').val();
        paymentData['extension_attributes']['up_order_comment'] = $('textarea#' + paymentMethodCode).val();
        console.log(paymentData['extension_attributes']['up_order_comment']);
    };
});
