<?php

namespace RaifuCore\Phone\Rules;

use RaifuCore\Phone\PhoneModule;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhone implements ValidationRule
{
    public function __construct(protected string|null $phone) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneDto = PhoneModule::getDtoBySource($this->phone);
        if (!$phoneDto->isValid()) {
            $fail(__('raifucore::phone.phoneNumber'));
        }
    }
}
