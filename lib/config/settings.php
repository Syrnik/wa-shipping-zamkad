<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2021
 * @license Webasyst
 */
return [
    'variant_name'     => ['value' => 'Доставка за город'],
    'field_name'       => [
        'title'        => 'Название поля с расстоянием',
        'control_type' => waHtmlControl::INPUT,
        'value'        => 'Расстояние от города (км.)'
    ],
    'weight_limits'    => ['value' => ['min' => 0, 'max' => 0]],
    'price_limits'     => ['value' => ['min' => 0, 'max' => 0]],
    'km_table'         => ['value' => [['base' => 0, 'to' => 1, 'price' => 0.0]]],
    'street_field'     => ['value' => 'yes'],
    'delivery_date'    => ['value' => ['show' => true, 'interval' => '0']],
    'desired_delivery' => ['value' => ['date' => false, 'interval' => false]],
    'timeframes'       => ['value' => [
        [
            'from_hour'    => 9,
            'from_minutes' => 0,
            'to_hour'      => 18,
            'to_minutes'   => 0,
            1              => true,
            2              => true,
            3              => true,
            4              => true,
            5              => true,
            6              => false,
            7              => false,
            'holidays'     => false,
            'workdays'     => false
        ]
    ]],
    'holidays'         => ['value' => []],
    'workdays'         => ['value' => []],
];
