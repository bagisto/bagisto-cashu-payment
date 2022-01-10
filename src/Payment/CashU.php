<?php

namespace Webkul\CashU\Payment;

use Illuminate\Support\Facades\Config;
use Webkul\Payment\Payment\Payment;

/**
 * CashU class
 *
 * @author    Rahul Shukla <rahulshukla,symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class CashU extends Payment
{
    /**
     * CashU web URL generic getter
     *
     * @return string
     */
    public function getCashuUrl()
    {
        if (core()->getConfigData('sales.paymentmethods.cashu.transcation_mode') == 0) {
            return sprintf('https://sandbox.cashu.com/cgi-bin/payment/pcashu.cgi');
        } else {
            return sprintf('https://www.cashu.com/cgibin/payment/pcashu.cgi');
        }
    }
}