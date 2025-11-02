<?php

namespace RaifuCore\Phone\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use RaifuCore\Phone\PhoneModule;

class ValidPhone implements ValidationRule
{
    public function __construct(protected string|null $phone) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!PhoneModule::getDtoByPhone($this->phone)) {
            $fail(__('raifucore::phone.phoneNumber'));
        }
    }
}
