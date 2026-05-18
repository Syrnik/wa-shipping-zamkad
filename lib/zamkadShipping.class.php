<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2021-2022
 * @license Webasyst
 */

declare(strict_types=1);

/**
 * Main plugin class
 *
 * @property-read  array|string|null $delivery_date
 * @property-read  string $street_field
 * @property-read  array $geography_limits
 * @property-read  string $variant_name
 * @property-read  array{min:float|null, max:float|null} $weight_limits
 * @property-read  array{min:float|null, max:float|null} $price_limits
 * @property-read  array $km_table
 */
class zamkadShipping extends waShipping
{
    /** @var array|null */
    protected ?array $_typecasted_settings = null;

    /**
     * @return string
     */
    public function allowedCurrency(): string
    {
        $c = $this->getSettings('currency') ?? 'RUB';
        return $this->getSettings('currency') ?? 'RUB';
    }

    /**
     * @return string
     */
    public function allowedWeightUnit(): string
    {
        return 'kg';
    }

    /**
     * @param waOrder $order
     * @return array
     * @throws waException
     * @throws Exception
     */
    public function customFields(waOrder $order): array
    {
        $fields = parent::customFields($order);
        $shipping_params = (array)$order->shipping_params;

        $km_table = $this->km_table;
        $last = end($km_table);

        $fields['zamkad_distance'] = [
            'control_type' => waHtmlControl::INPUT,
            'value'        => $shipping_params['zamkad_distance'] ?? '',
            'title'        => $this->getSettings('field_name') ?: _wp('Расстояние от города (км.)'),
            'description'  => _wp('Плата за каждый полный и неполный км.'),
            'field_type'   => 'number',
            'min'          => 1,
            'max'          => max($last['to'], 1),
            'step'         => 1,
            //            'required'     => 1,
            'data'         => ['affects-rate' => true]
        ];

        if ($desired_delivery_field = (new zamkadShippingDesiredDeliveryOrderField($this, $order))->build())
            $fields['desired_delivery'] = $desired_delivery_field;

        return $fields;
    }

    /**
     * @param string|null $name
     * @return array|string|mixed|null
     */
    public function getSettings($name = null)
    {
        $settings = $this->_getSettingsTypecast();
        if ($name === null) return $settings;

        return $settings[$name] ?? null;
    }

    /**
     * @return array
     */
    protected function _getSettingsTypecast(): array
    {
        if (is_array($this->_typecasted_settings)) return $this->_typecasted_settings;
        $settings = parent::getSettings();

        foreach ($settings as $setting => $value) {
            switch ($setting) {
                case 'field_value':
                case 'variant_name':
                    $value = trim((string)$value);
                    break;
                case 'weight_limits':
                    if (!is_array($value)) $value = ['min' => 0, 'max' => 0];
                    else $value = [
                        'min' => (int)max(0, $value['min'] ?? 0),
                        'max' => (int)max(0, $value['max'] ?? 0)];
                    if (!$value['max']) $value['max'] = null;
                    break;
                case 'price_limits':
                    if (!is_array($value)) $value = ['min' => 0, 'max' => 0];
                    else $value = [
                        'min' => (float)max(0, round((float)($value['min'] ?? 0), 2)),
                        'max' => (float)max(0, round((float)($value['max'] ?? 0), 2))
                    ];
                    if (!$value['max']) $value['max'] = null;
                    break;
                case 'km_table':
                    if (!is_array($value)) {
                        $value = [['base' => 0, 'to' => 1, 'price' => 0.0]];
                        break;
                    }
                    array_walk($value, function (&$v) {
                        if (!is_array($v)) {
                            $v = ['base' => 0, 'to' => 1, 'price' => 0.0];
                            return;
                        }
                        $v = [
                            'base'  => (float)max(0, round((float)($v['base'] ?? 0), 2)),
                            'to'    => (int)max(1, (int)($v['to'] ?? 1)),
                            'price' => (float)max(0.0, round((float)($v['price'] ?? 0.0), 2))
                        ];
                    });
                    usort($value, fn($a, $b) => $a['to'] <=> $b['to']);
                    break;
                case 'timeframes':
                    if (!is_array($value)) {
                        $value = [[
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
                                  ]];
                        break;
                    }
                    array_walk($value, function (&$v) {
                        if (!is_array($v))
                            $v = [
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
                            ];
                        foreach ($v as $key => $item) {
                            switch ($key) {
                                case 'from_hour':
                                case 'from_minutes':
                                case 'to_hour':
                                case 'to_minutes':
                                    $v[$key] = (int)$item;
                                    break;
                                default:
                                    $v[$key] = (bool)$item;
                            }
                        }
                        for ($i = 1; $i < 8; $i++) if (!isset($v[$i])) $v[$i] = false;
                    });
                    break;
                case 'holidays':
                case 'workdays':
                    if (!is_array($value)) {
                        $value = [];
                    } else {
                        $normalized = [];
                        foreach ($value as $v) {
                            $v = trim((string)$v);
                            if ($v) $normalized[$v] = $v;
                        }
                        $value = $normalized;
                    }
                    break;
                case 'desired_delivery':
                    $value = ['date' => (bool)($value['date'] ?? false), 'interval' => (bool)($value['interval'] ?? false)];
                    break;
                case 'delivery_date':
                    $value = ['show' => (bool)($value['show'] ?? false), 'interval' => trim((string)($value['interval'] ?? ''))];
                    break;
            }
            $settings[$setting] = $value;
        }

        return $this->_typecasted_settings = $settings;
    }

    /**
     * @param array $params
     * @return string
     * @throws SmartyException
     * @throws waException
     */
    public function getSettingsHTML($params = array()): string
    {
        $settings = $this->getSettings();
        $info = static::info($this->id);
        $info['namespace'] = $params['namespace'] ?? '';
        $info['action_url'] = [
            'countries' => $this->getInteractionUrl('countries', 'geography'),
            'regions'   => $this->getInteractionUrl('regions', 'geography')
        ];

        $info['currencies'] = waCurrency::getAll('all');
        array_walk($info['currencies'], function (&$v) {
            $v = [
                'code'      => $v['code'],
                'name'      => $v['title'],
                'sign'      => strip_tags($v['sign_html']),
                'precision' => intval($v['precision'])];
        });

        if (class_exists('Collator')) {
            $collator = new Collator(waLocale::getLocale());
            $string_comparer = fn(string $str1, string $str2) => (int)$collator->compare($str1, $str2);
        } else $string_comparer = fn(string $str1, string $str2) => mb_strtolower($str1, 'UTF-8') <=> mb_strtolower($str2, 'UTF-8');

        usort($info['currencies'], function ($a, $b) use ($string_comparer) {
            $a_fav = array_search($a['code'], ['RUB', 'USD', 'EUR', 'BYR', 'BYN', 'KZT', 'UAH']);
            $b_fav = array_search($b['code'], ['RUB', 'USD', 'EUR', 'BYR', 'BYN', 'KZT', 'UAH']);
            if (false === $a_fav) $a_fav = PHP_INT_MAX;
            if (false === $b_fav) $b_fav = PHP_INT_MAX;

            if ($a_fav === $b_fav) return $string_comparer($a['name'], $b['name']);
            return $a_fav <=> $b_fav;
        });
        $info['currencies'] = array_column($info['currencies'], null, 'code');

        $view = wa()->getView();
        $_zamkadPlugin = $this;
        $view->assign(compact('settings', 'info', '_zamkadPlugin'));

        $template_name = version_compare(wa()->whichUI(), '2.0', '>=') ? 'settings' : 'settings-legacy';

        return $view->fetch("$this->path/templates/$template_name.html");
    }

    /**
     * @return DateTime[]|null
     * @throws Exception
     */
    public function getDeliveryDates(): array
    {
        $departure_datetime = $this->getPackageProperty('departure_datetime');
        if (!$departure_datetime) {
            $departure_datetime = 'now';
        } else $departure_datetime = (string)$departure_datetime;

        $timezone = $this->getPackageProperty('shop_time_zone');
        $timezone = new DateTimeZone($timezone ?: date_default_timezone_get());

        try {
            $departure_datetime = new DateTime($departure_datetime ?: 'now', $timezone);
        } catch (Exception $e) {
            $departure_datetime = date_create();
        }

        $setting = $this->delivery_date;
        if ($setting['interval']) {
            $user_offsets = explode('-', $setting['interval']);
            if ($user_offsets) {
                array_walk($user_offsets, function (&$v) {
                    $v = trim($v);
                    $v = max(0, (int)$v);
                });
                $user_offsets = array_unique($user_offsets);
                sort($user_offsets, SORT_NUMERIC);
            } else $user_offsets = [0];
        } else $user_offsets = [0];

        return array_map(function ($o) use ($departure_datetime) {
            $date = clone $departure_datetime;
            if ($o) $date->modify("+$o days");
            return $date;
        }, $user_offsets);

    }

    public function requestedAddressFields(): array
    {
        $fields = [
            'country' => ['required' => true],
            'region'  => ['required' => true],
            'city'    => ['required' => true]
        ];

        if ($this->street_field !== 'no') $fields['street'] = [];
        if ($this->street_field === 'required') $fields['street']['required'] = true;

        return $fields;
    }

    /**
     * Хак, чтобы json-контроллерам тоже достался экземпляр плагина
     * А если указан ключ конкретной конфигурации, так чтоб не просто экземпляр, а экземпляр указанной конфигурации
     *
     * @param string $module
     * @param string $action
     * @return waController|waJsonActions|waJsonController|waSystemPluginAction|waSystemPluginActions
     * @throws waException
     */
    public function getController($module = 'backend', $action = 'Default')
    {
        $controller = parent::getController($module, $action);
        if (method_exists($controller, 'setPlugin')) {
            $key = waRequest::get('plugin_key');
            $controller->setPlugin($key ? waShipping::factory($this->id, $key, $this->app_id) : $this);
        }

        return $controller;
    }

    /**
     * @return array[]
     */
    public function allowedAddress(): array
    {
        $setting = $this->geography_limits;
        if (!$setting['country']) return parent::allowedAddress();

        $allowed = ['country' => $setting['country']];
        if (strlen($setting['region'])) $allowed['region'] = $setting['region'];

        return [$allowed];
    }

    /**
     * @return array|bool
     * @throws waException
     */
    protected function calculate()
    {
        if ($this->isOrderWeightExceedsLimit() || $this->isOrderCostExceedsLimit()) return false;

        $delivery_variant = [
            'currency' => $this->getSettings('currency') ?? 'RUB'
        ];

        if ($this->variant_name) $delivery_variant['name'] = $this->variant_name;

        $distance = $this->getDistanceFromParams();
        if (($this->getSelectedServiceId() === null) && !$distance) $delivery_variant += $this->getMinMaxRates();
        else {
            if ($distance) {
                if (($rate = $this->calcByRule($distance)) !== null)
                    $delivery_variant += ['rate' => $rate];
                else $delivery_variant += ['rate' => null, 'comment' => _wp('Доставка на указанное расстояние невозможна')];
            } else {
                $delivery_variant += ['rate' => null, 'comment' => _wp('Укажите расстояние в км.')];
            }
        }

        $est_delivery = $this->delivery_date;
        try {
            if ($est_delivery['show'] && ($dates = $this->getDeliveryDates())) {
                $delivery_variant['est_delivery'] = waDateTime::format('humandate', $dates[0]->getTimestamp(), $dates[0]->getTimezone()->getName());
                if (count($dates) > 1) {
                    $delivery_variant['est_delivery'] .= " " . waDateTime::format('humandate', $dates[1]->getTimestamp(), $dates[1]->getTimezone()->getName());
                    $delivery_variant['delivery_date'] = [$dates[0]->format('Y-m-d') . " 00:00:00", $dates[1]->format('Y-m-d') . " 00:00:00"];
                } else $delivery_variant['delivery_date'] = $dates[0]->format('Y-m-d') . " 00:00:00";

            }
        } catch (waException $e) {
            unset($delivery_variant['est_delivery']);
        }

        return [$delivery_variant];
    }

    /**
     * @return bool
     */
    protected function isOrderWeightExceedsLimit(): bool
    {
        $weight = round(max(0, (float)$this->getTotalWeight()), 3);

        if ($this->weight_limits['min'] && ($weight < $this->weight_limits['min'])) return true;
        if ($this->weight_limits['max'] && ($weight > $this->weight_limits['max'])) return true;

        return false;
    }

    /**
     * @return bool
     */
    protected function isOrderCostExceedsLimit(): bool
    {
        $order_cost = max(0, round((float)$this->getTotalPrice(), 2));
        if ($this->price_limits['min'] && ($order_cost < $this->price_limits['min'])) return true;
        if ($this->price_limits['max'] && ($order_cost > $this->price_limits['max'])) return true;

        return false;
    }

    /**
     * Хак, чтобы в Shop-Script 8.0 .... 8.4 тоже получать выбранный пункт
     *
     * @return string|null
     */
    public function getSelectedServiceId(): ?string
    {
        if (($service_id = parent::getSelectedServiceId()) !== null) return $service_id;

        $shipping_params = $this->getPackageProperty('shipping_params');

        if (is_array($shipping_params)) {
            $variant_id = ifset($shipping_params, 'service', 'variant_id', null);
            if ($variant_id === null) return null;

            if (preg_match('/^\d+\..*/', $variant_id) && substr($variant_id, 0, strlen($this->key) + 1) == "$this->key.")
                return $variant_id;
        }

        return null;
    }

    /**
     * @param string $property
     * @return float|int|mixed|null
     */
    public function getPackageProperty($property)
    {
        return parent::getPackageProperty($property);
    }

    /**
     * @return array
     */
    protected function getMinMaxRates(): array
    {
        $rate = [];
        $table = $this->km_table;
        $first = reset($table);
        $last = array_pop($table);
        if ($table) $pre_last = array_pop($table);
        else $pre_last = ['to' => 0];

        $rate[] = $first['base'] ? max(0, round($first['base'] + $first['price'], 2)) : 0;
        $rate[] = round(max(0, $last['base'] + ($last['to'] - $pre_last['to']) * $last['price']));

        $rate = array_unique($rate);

        if (count($rate) > 1) {
            sort($rate, SORT_NUMERIC);
            return [
//            'rate'     => $min_rate,
'rate'     => $rate,
'rate_min' => $rate[0],
'rate_max' => $rate[1]
            ];
        } else
            return ['rate' => $rate[0]];
    }

    /**
     * @param int $distance
     * @return float|null
     */
    public function calcByRule(int $distance): ?float
    {
        $rule = $this->findPriceRule($distance);
        if ($rule === null) return null;

        return round(max(0, $rule['base'] + $distance * $rule['price']), 2);
    }

    /**
     * @param int $distance
     * @return array|null
     */
    protected function findPriceRule(int $distance): ?array
    {
        $table = $this->km_table;
        $last = end($table);
        if ($distance > $last['to']) return null;

        $rule = [];
        foreach ($table as $row) {
            if ($row['to'] < $distance) continue;
            else {
                $rule = $row;
                break;
            }
        }

        return $rule ?: null;
    }

    /**
     * @return int|null
     */
    protected function getDistanceFromParams(): ?int
    {
        $params = (array)$this->getPackageProperty('shipping_params');
        $distance = ifset($params['zamkad_distance']);
//        if (is_null($distance) && (wa()->getEnv() === 'backend')) {
//            $params = waRequest::post('shipping_' . $this->key);
//            if (is_array($params)) $distance = ifset($params['zamkad_distance']);
//        }
        if (is_string($distance)) {
            $distance = trim($distance);
            if (!strlen($distance)) $distance = null;
        }
        if ($distance !== null) $distance = (int)max(1, (int)$distance);

        return $distance;
    }
}
