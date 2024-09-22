<?php


namespace App\Repositories;


use App\Models\Country;
use App\Repositories\Interfaces\CountryRepositoryInterface;

class CountryRepository implements CountryRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function getByPhoneCode(string $phoneCode): Country|null
    {
        return Country::query()->where('phone_code', $phoneCode)->first();
    }
}
