<?php

return [
    [
        'key' => 'sales',
        'name' => 'Sales',
        'sort' => 1
    ], [
        'key' => 'sales.paymentmethods',
        'name' => 'Payment Methods',
        'sort' => 2,
    ], [
        'key' => 'sales.paymentmethods.cashu',
        'name' => 'CashU Payment',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'cashu::app.configuration.title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'cashu::app.configuration.description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'active',
                'title' => 'cashu::app.configuration.status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inactive',
                        'value' => false
                    ]
                ],
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'merchent_id',
                'title' => 'cashu::app.configuration.merchent_id',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'encryption_keyword',
                'title' => 'cashu::app.configuration.encryption_keyword',
                'type' => 'password',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'display_text',
                'title' => 'cashu::app.configuration.display_text',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'transcation_mode',
                'title' => 'cashu::app.configuration.transcation_mode',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Test',
                        'value' => 0
                    ], [
                        'title' => 'Live',
                        'value' => 1
                    ]
                ],
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'encryption',
                'title' => 'cashu::app.configuration.encryption',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Enahanced',
                        'value' => 'full'
                    ], [
                        'title' => 'Default',
                        'value' => 'default'
                    ]
                ],
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'sort',
                'title' => 'admin::app.admin.system.sort_order',
                'type' => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1
                    ], [
                        'title' => '2',
                        'value' => 2
                    ], [
                        'title' => '3',
                        'value' => 3
                    ], [
                        'title' => '4',
                        'value' => 4
                    ]
                ],
            ]
        ]
    ]
];