<?php

namespace RaifuCore\Phone\Actions;

use RaifuCore\Phone\Dto\PhoneDto;

class GetDtoByPhone
{
    public function __construct(protected ?string $phone)
    {
        $this->phone = is_string($phone)
            ? preg_replace('[\D]', '', $phone)
            : '';
    }

    public function execute(): ?PhoneDto
    {
        $template = (new FindTemplateByPhone($this->phone))->execute();
        if (!$template) {
            return null;
        }

        return new PhoneDto(
            $template->getCode(),
            substr($this->phone, strlen($template->getCode())),
            $this->phone,
            $template->getCountryIso()
        );
    }
}
