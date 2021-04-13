<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2021
 * @license Webasyst
 */

declare(strict_types=1);

/**
 * Main plugin class
 */
class zamkadShipping extends waShipping
{
    /** @var array|null */
    protected $_typecasted_settings;

    /**
     * @return bool|string|array
     */
    protected function calculate()
    {
        return [[
            'name'     => 'Доставка за МКАД',
            'currency' => 'RUB',
            'rate'     => 80 * 3,
        ]];
    }

    public function allowedCurrency(): string
    {
        return 'RUB';
    }

    public function allowedWeightUnit(): string
    {
        return 'kg';
    }

    public function customFields(waOrder $order): array
    {
        $fields = parent::customFields($order);
        $params = (array)$order->shipping_params;

        $fields['zamkad_distance'] = [
            'control_type' => waHtmlControl::INPUT,
            'value'        => $params['zamkad_distance'] ?? '1',
            'title'        => $this->getSettings('field_name') ?: 'Расстояние от МКАД (км.)',
            'description'  => 'Плата за каждый полный и неполный км.',
            'field_type'   => 'number',
            'placeholder'  => '1',
            'min'          => 1,
            'max'          => 150,
            'step'         => 1,
            'required'     => 1,
            'data'         => ['affects-rate' => true]
        ];

        return $fields;
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

        $view = wa()->getView();
        $view->assign(compact('settings', 'info'));

        return $view->fetch($this->path . '/templates/settings.html');
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
                    $value = trim((string)$value);
                    if (!$value) $value = 'Расстояние от МКАД (км.)';
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
                    usort($value, function ($a, $b) {
                        return $a['to'] <=> $b['to'];
                    });
                    break;
                case 'timeframes':
                    if (!is_array($value)) {
                        $value = [['from_hour' => 9, 'from_minutes' => 0, 'to_hour' => 18, 'to_minutes' => 0, 1 => true, 2 => true, 3 => true, 4 => true, 5 => true, 6 => false, 7 => false, 'holidays' => false, 'workdays' => false]];
                        break;
                    }
                    array_walk($value, function (&$v) {
                        if (!is_array($v))
                            $v = ['from_hour' => 9, 'from_minutes' => 0, 'to_hour' => 18, 'to_minutes' => 0, 1 => true, 2 => true, 3 => true, 4 => true, 5 => true, 6 => false, 7 => false, 'holidays' => false, 'workdays' => false];
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
            }
            $settings[$setting] = $value;
        }

        return $this->_typecasted_settings = $settings;
    }
}
