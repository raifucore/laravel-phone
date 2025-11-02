<?php

namespace RaifuCore\Phone\Actions;

use RaifuCore\Phone\Dto\PhoneTemplateDto;

class GetTemplateDtoBySource
{
    private array $templates;

    public function __construct(protected string $source)
    {
        // Только цифры
        $this->source = preg_replace('[\D]', '', $source);

        $this->templates = config('phone.templates', []);
    }

    public function execute(): ?PhoneTemplateDto
    {
        foreach ($this->templates as $aTemplate) {
            $dto = (new PhoneTemplateDto)->fromArray($aTemplate);
            if ($this->_isMatch($dto, $this->source)) {
                return $dto;
            }
        }

        return null;
    }

    private function _isMatch(PhoneTemplateDto $dto, string $source): bool
    {
        return preg_match("/{$dto->getRegex()}/", $source);
    }
}
