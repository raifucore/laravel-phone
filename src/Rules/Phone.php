<?php

namespace RaifuCore\Phone\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use RaifuCore\Phone\PhoneModule;

class Phone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!PhoneModule::getDtoByPhone($value)) {
            $fail(__('raifucore_phone::validation.phoneNumber'));
        }
    }
}
