<?php

namespace RaifuCore\Phone\Actions;

use RaifuCore\Phone\Dto\PhoneDto;
use RaifuCore\Phone\Dto\TemplateDto;

class GetDtoByPhone
{
    public function __construct(protected string|null $phone)
    {
        $this->phone = is_string($phone)
            ? preg_replace('[\D]', '', $phone)
            : '';
    }

    public function execute(): PhoneDto|null
    {
        $template = $this->_findTemplate();
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

    private function _findTemplate(): TemplateDto|null
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
