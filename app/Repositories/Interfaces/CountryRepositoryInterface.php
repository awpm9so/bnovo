<?php


namespace App\Repositories\Interfaces;
use App\Models\Country;

/**
 * Interface CountryRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface CountryRepositoryInterface
{
    /**
     * Получить Страну по мобильному коду
     *
     * @param string $phoneCode
     * @return Country|null
     */
    public function getByPhoneCode(string $phoneCode): Country|null;
}
