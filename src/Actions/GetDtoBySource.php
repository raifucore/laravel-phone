<?php

namespace RaifuCore\Phone\Actions;

use RaifuCore\Phone\Dto\PhoneDto;
use RaifuCore\Phone\Dto\PhoneTemplateDto;
use Illuminate\Support\Str;

class GetDtoBySource
{
    public function __construct(protected string $source)
    {
        // Только цифры
        $this->source = preg_replace('[\D]', '', $source);
    }

    public function execute(): PhoneDto
    {
        $dto = (new PhoneDto)->setSource($this->source);

        // Ищем подходящий PhoneTemplateDto
        $templateDto = Str::length($this->source) ? $this->_templateDto($this->source) : null;
        if ($templateDto) {
            $this->_fillDto($dto, $templateDto);
        }

        return $dto;
    }

    private function _templateDto(string $source): ?PhoneTemplateDto
    {
        return (new GetTemplateDtoBySource($source))->execute();
    }

    private function _fillDto(PhoneDto $dto, PhoneTemplateDto $templateDto): void
    {
        $dto
            ->setIsValid(true)
            ->setCountryIso($templateDto->getCountryIso())
            ->setCode($templateDto->getCode())
            ->setBody(Str::substr($dto->getSource(), Str::length($templateDto->getCode())));
    }
}
