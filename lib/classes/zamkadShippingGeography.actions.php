<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2021
 * @license Webasyst
 */
declare(strict_types=1);

/**
 * Class zamkadShippingGeographyActions
 */
class zamkadShippingGeographyActions extends waJsonActions
{
    /**
     * Список стран
     */
    public function countriesAction()
    {
        $countries = (new waCountryModel())->allWithFav();

        $this->response = array_map(function ($c) {
            return ['code' => $c['iso3letter'], 'name' => $c['name']];
        }, $countries);

        $this->response = array_values($this->response);
    }

    /**
     * Список регионов
     */
    public function regionsAction()
    {
        $country = waRequest::request('country', '', waRequest::TYPE_STRING_TRIM);
        if ($country) {
            $regions = (new waRegionModel())->getByCountry($country);
            $this->response = array_map(function ($r) {
                return ['code' => $r['code'], 'name' => $r['name']];
            }, $regions);
        } else $this->response = [];

        $this->response = array_values($this->response);
    }
}
