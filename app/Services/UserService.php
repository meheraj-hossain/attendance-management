<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService.
 */
class UserService
{

    public function setInformation($request)
    {
        try {
            $user = User::where('id', $request->id)->first();
            if (empty($user)) {
                $user           = new User();
                $user->password = Hash::make($request->password);
            } else {
                $user->status = $request->status;
                $user->role   = $request->role;
            }

            $user->name  = $request->name;
            $user->email = $request->email;
            $user->save();
        } catch (\exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

}
