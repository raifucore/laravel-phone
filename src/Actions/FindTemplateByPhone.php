<?php

namespace RaifuCore\Phone\Actions;

use RaifuCore\Phone\Dto\TemplateDto;

class FindTemplateByPhone
{
    public function __construct(protected ?string $phone)
    {
        $this->phone = is_string($phone)
            ? preg_replace('[\D]', '', $phone)
            : '';
    }

    public function execute(): ?TemplateDto
    {
        return $this->_findTemplate();
    }

    private function _findTemplate(): ?TemplateDto
    {
        $length = strlen($this->phone);
        if (!$length) {
            return null;
        }

        foreach (config('phone.templates', []) as $countryIso => $data) {

            // Check length first
            if ($length === $data['length'] || (isset($data['length_max'])) && $length > $data['length'] && $length <= $data['length_max']) {

                // Check regex then
                if (preg_match("/{$data['regex']}/", $this->phone)) {
                    return (new TemplateDto($countryIso))->fromArray($data);
                }
            }
        }

        return null;
    }
}
