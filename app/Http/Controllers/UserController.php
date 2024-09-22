<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\CountryRepository;

class UserController extends Controller
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * Создать пользователя
     *
     * @param StoreUserRequest $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $validated = $request->validated();

        if (isset($validated['country']))
            $countryName = $validated['country'];
        else {
            $countryPhoneCode = explode(' ', $validated['phone'])[0];
            $country = $this->countryRepository->getByPhoneCode($countryPhoneCode);
            $countryName = $country ? $country->name : 'Не определено';
        }

        $user = User::create([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $countryName,
        ]);

        return new UserResource($user);
    }

    /**
     * Получить пользователя
     *
     * @param int $id
     * @return UserResource
     */
    public function show(int $id)
    {
        $user = User::findOrFail($id);

        return new UserResource(User::findOrFail($id));
    }


    /**
     * Обновить пользователя
     * 
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @param int $id
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $validated = $request->validated();

        $user = User::findOrFail($id);

        $user->update($validated);

        return new UserResource($user);
    }

    /**
     * Удалить пользователя
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'Пользователь успешно удален.']);
    }
}
