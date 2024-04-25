<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Spatie\Permission\Models\Role;

class ValidRole implements Rule
{
    public function passes($attribute, $value)
    {
        // Verificar si el valor proporcionado está en la lista de roles
        return Role::where('name', $value)->exists();
    }

    public function message()
    {
        return 'The role is invalid.';
    }
}
