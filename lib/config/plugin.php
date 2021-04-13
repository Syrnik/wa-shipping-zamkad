<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2021
 * @license Webasyst
 */
return array(
    'name'                  => 'Доставка с оплатой за расстояние',
    'description'           => 'Стоимость доставки зависит от расстояния в километрах',
    'img'                   => 'img/zamkad.png',
    'version'               => '1.0.0',
    'vendor'                => '670917',
    'services_by_type'      => true,
    'type'                  => waShipping::TYPE_TODOOR,
    'backend_custom_fields' => true
);
