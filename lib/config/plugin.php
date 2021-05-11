<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2021
 * @license Webasyst
 */
return array(
    'name'                  => 'Доставка с оплатой за расстояние',
    'description'           => 'Стоимость доставки зависит от расстояния в километрах',
    'icon'                  => [
        16 => 'img/icon-16.png',
        24 => 'img/icon-24.png',
        32 => 'img/icon-32.png',
        48 => 'img/icon-48.png',
        64 => 'img/icon-64.png',
    ],
    'logo'                  => 'img/logo-60x32.png',
    'version'               => '1.0.2',
    'vendor'                => '670917',
    'services_by_type'      => true,
    'type'                  => waShipping::TYPE_TODOOR,
    'backend_custom_fields' => true
);
