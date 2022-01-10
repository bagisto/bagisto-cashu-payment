<?php

namespace Webkul\CashU\Payment;

/**
 * CashUPayment payment method class
 *
 * @author    Rahul Shukla <rahulshukla,symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CashUPayment extends CashU
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'cashu';

     /**
     * Return paypal redirect url
     *
     * @var string
     */
    public function getRedirectUrl()
    {
        return route('cashu.payement.redirect');
    }
}