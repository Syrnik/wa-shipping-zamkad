<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2021
 * @license Webasyst
 */
declare(strict_types=1);

/**
 * Class zamkadShippingDesiredDeliveryOrderField
 */
class zamkadShippingDesiredDeliveryOrderField
{
    /** @var zamkadShipping */
    protected $plugin;

    /** @var waOrder */
    protected $order;

    /** @var null|array */
    protected $_schedule;

    /**
     * zamkadShippingDesiredDeliveryOrderField constructor.
     * @param zamkadShipping $plugin
     * @param waOrder $order
     */
    public function __construct(zamkadShipping $plugin, waOrder $order)
    {
        $this->plugin = $plugin;
        $this->order = $order;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function build(): array
    {
        $setting = $this->plugin->getSettings('desired_delivery');
        if (!($setting['date'] ?? false) && !($setting['interval'] ?? false)) return [];

        $days_from_now = (int)max(0, round(($this->getStartTime() - time() / 24 * 3600)));
        $field = [
            'value'    => $this->extractValuesFromOrder(),
            'date'     => $setting['date'] ?? 0,
            'interval' => $setting['interval'] ?? 0
        ];

        if ($this->plugin->timeframes) {
            if (isset($this->order->params['departure_datetime']))
                $this->plugin->setParams($this->order->params);

            foreach ($this->plugin->timeframes as $timeframe) {
                $field['intervals'][] = $this->buildInterval($timeframe, $this->getStartTime());
            }
        }

        return $field;
    }

    /**
     * @param array $timeframe
     * @param int $start_timestamp
     * @return array
     * @throws Exception
     */
    protected function buildInterval(array $timeframe, int $start_timestamp): array
    {
        /** Часовой пояс из настроек магазина */
        $shop_time_zone = $this->plugin->getPackageProperty('shop_time_zone');
        $shop_time_zone = new DateTimeZone($shop_time_zone ?: date_default_timezone_get());

        $interval = [
            'from_m' => '00',
            'to_m'   => '00',
            'day'    => [],
            'offset' => 0,
        ];

        $i_from = sprintf('%02d:%02d', $timeframe['from_hour'], $timeframe['from_minutes']);
        $i_to = sprintf('%02d:%02d', $timeframe['to_hour'], $timeframe['to_minutes']);
        $interval['interval'] = "$i_from-$i_to";

        $days = array_map('intval', array_filter($timeframe, function ($v, $k) {
            return is_numeric($k) && $v;
        }, ARRAY_FILTER_USE_BOTH));
        foreach ($days as $key => $day) $interval['day'][$key - 1] = 1;

        $service_delivery_date = null;
        $stepwise_date = new DateTime();
        $stepwise_date->setTimestamp($start_timestamp);
        $stepwise_date->setTimezone($shop_time_zone);
        $start_tz = clone $stepwise_date;

        for ($loop = 0; $loop < 60; $loop++) {
            $interval['offset']++;
            if ($loop) $stepwise_date->modify('+1 day');
            $service_date = $stepwise_date->format('Y-m-d');
            $week_day = $stepwise_date->format('N');

            /** является ли текущая дата ($service_date) дополнительным выходным днем */
            $is_extra_holiday = in_array($service_date, $this->getSchedule()['holidays'], true);
            if (empty($timeframe['holidays']) && $is_extra_holiday) {
                /** $days['holiday'] = 1, если выбрана галка "Доп. выходной" у текущего интервала в таблице "Интервалы доставки".
                 *  Для таких дней могут быть доступные интервалы доставки. Поэтому пропускаем текущее условие */
                continue;
            }

            // is extra workday on current $service_date?
            $is_extra_workday = false;
            $is_extra_workday_enabled = !empty($timeframe['workdays']);
            if ($is_extra_workday_enabled) {
                $is_extra_workday = in_array($service_date, $this->getSchedule()['workdays'], true);
            }

            $is_workday = $is_extra_workday || !empty($days[$week_day]);

            if ($is_workday) {
                $right_i = new DateTime("$service_date $i_to", $shop_time_zone);
                if ($right_i < $start_tz) {
                    /** интервал недоступен в этот день, если стартовая дата наступает позже даты текущей итерации */
                    continue;
                }
                $service_delivery_date = $service_date . ' ' . $i_from;
                break;
            }
        }

        $interval['start_date'] = $service_delivery_date;

        return $interval;
    }

    /**
     * Timestamp когда будет готово к доставке
     * @return int
     * @todo Учитывать настройки шопа и настройки из плагина
     *
     */
    protected function getStartTime(): int
    {
        $start_time = time();

        return $start_time;
    }

    /**
     * Включен ли вообще запрос даты и/или интервала доставки
     *
     * @return bool
     */
    protected function isFieldEnabled(): bool
    {
        $setting = $this->plugin->getSettings('desired_delivery');
        return ($setting['date'] ?? false) || ($setting['interval'] ?? false);
    }

    /**
     * Извлекает из заказа уже заполненные поля, если они там есть
     *
     * @return array
     */
    protected function extractValuesFromOrder(): array
    {
        $shipping_params = (array)$this->order->shipping_params;
        $value = [];

        if (!empty($shipping_params['desired_delivery.interval'])) {
            $value['interval'] = $shipping_params['desired_delivery.interval'];
        }
        if (!empty($shipping_params['desired_delivery.date_str'])) {
            $value['date_str'] = $shipping_params['desired_delivery.date_str'];
        }
        if (!empty($shipping_params['desired_delivery.date'])) {
            $value['date'] = $shipping_params['desired_delivery.date'];
        }

        return $value;
    }

    /**
     * @return array
     */
    protected function getSchedule(): array
    {
        if (!$this->_schedule) {
            $this->_schedule = [
                'holidays' => $this->plugin->getSettings('holidays'),
                'workdays' => $this->plugin->getSettings('workdays'),
                'time'     => time()
            ];
        }

        return $this->_schedule;
    }

    /**
     * Вычисляет дату или даты доставки в соответствии с настройкой
     * @return array
     */
    protected function getDeliveryTimes(): array
    {
        return ['timestamp' => null, 'estimate' => null];
    }

    /**
     * @param array{
     *          from_hour: string,
     *          from_minutes: string,
     *          to_hour: string,
     *          to_minutes: string,
     *          day: array<bool>,
     *          workday: bool,
     *          holiday: bool
     *      } $interval
     * @param int|null $timestamp
     * @return array{
     *     offset: int,
     *     from: string,
     *     to: string,
     *     interval: string,
     *     days: array,
     *     start_date: string
     * }
     */
    protected function getInterval(array $interval, ?int $timestamp): array
    {
        $result = [
            'offset' => 0,
            'from'   => sprintf('%02d:%02d', $interval['from_hour'], $interval['from_minutes']),
            'to'     => sprintf('%02d:%02d', $interval['to_hour'], $interval['string'])
        ];
        $result['interval'] = "{$result['from']}-{$result['to']}";

//        $start = is_array($timestamp) ? reset($timestamp) : $timestamp;
        $workdays = $this->plugin->getSettings('workdays');
        $holidays = $this->plugin->getSettings('holidays');

        do {
            $service_datetime = strtotime(sprintf('+%d days', $result['offset']++), $timestamp);
            $service_date = date('Y-m-d', $service_datetime);
            $week_day = date('N', $service_datetime);
            $is_holiday = in_array($service_date, $holidays, true);
            $is_extra_holiday = $is_holiday && $interval['holiday'];

            if (!$is_holiday || $is_extra_holiday) {
                $is_extra_workday = $interval['workday'] && in_array($service_date, $workdays, true);
                $is_workday = $is_extra_holiday || $is_extra_workday || in_array($week_day, $interval['day']);

                if ($is_workday) {
                    $is_same_day = date('Y-m-d', $this->time) === $service_date;
                    if ($is_same_day) {
                        if ((int)date('H', $this->time) >= (int)$interval['to']) {
                            continue;
                        }
                    }
                    $service_delivery_date = $service_date;
                    $service_delivery_date .= sprintf(' %02d:00', $interval['from']);
                }
            }
            if ($result['offset'] > 60) {
                break;
            }
        } while (empty($service_delivery_date));

        $_days = [];
        foreach ($interval['day'] as $key => $value) {
            $_days[$value - 1] = 1;
        }

        $result['day'] = $_days;

        $result['start_date'] = date('Y-m-d', strtotime($service_delivery_date));
        if ($interval['holiday']) {
            $result['day']['holiday'] = 1;
        }

        if ($interval['workday']) {
            $result['day']['workday'] = 1;
        }

        return $result;
    }
}
