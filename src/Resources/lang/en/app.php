<?php

return [
    'configuration' => [
        'title' => 'Title',
        'description' => 'Description',
        'status' => 'Status',
        'merchent_id' => 'Merchant Id',
        'encryption_keyword' => 'Encryption Keyword',
        'display_text' => 'Display Text',
        'encryption' => 'Encryption',
        'transcation_mode' => 'Transaction Mode',
        'error' => 'This Merchent is inactive or required parameter is missing.'
    ],

    'error' => [
        'checkout-again'  => 'Checkout Again',
        'order-failure'   => 'Order Fail!',
        'failure-message' => 'Your Order could not be completed due to mismatch token or verification. Please try again.',
        '2'  => 'Inactive Merchant ID.',
        '4'  => 'Inactive Payment Account.',
        '6'  => 'Insufficient funds.',
        '7'  => 'Incorrect Payment Account details.',
        '8'  => 'Invalid account.',
        '15' => 'The password of the Payment Account has expired.',
        '17' => 'The transaction has not been completed.',
        '20'  => 'The merchant has limited his sales to some countries;and the purchase attempt is coming from a country that is not listed in the merchant’s profile.',
        '21' => 'The transaction value is more than the limit. This limitation is applied to Payment Accounts that do not comply with KYC rules.',
        '22' => 'The merchant has limited his sales to only KYCcompliant Payment Accounts; and the purchase attempt is coming from a Payment Account that is NOT KYCcompliant.',
        '23' => 'The transaction has been cancelled by the customer. If the customer clicks on the “Cancel” button.',
        '24' => 'The Payment Account has been locked.',
        '27' => 'The customer is already subscribed to standing order.',
        '32' => 'User profile is incomplete, and the customer needs to upload their identification document inside their Payment Account in order to process this transaction.',
        'general' => 'CashU payment has been canceled.'
    ],
];