<?php

namespace RaifuCore\Phone\Actions;

use RaifuCore\Phone\Models\PhoneFormat;
use Illuminate\Support\Collection;

class GetAllAction
{
    public function execute(): Collection
    {
        $list = collect();

        foreach (config('phone.templates', []) as $countryIso => $template) {

            $new = new PhoneFormat;
            $new->country_iso = $countryIso;
            $new->code = $template['code'] ?? null;
            $new->mask = $template['mask'] ?? null;
            $new->regex = $template['regex'] ?? null;
            $new->country = __("countries.$countryIso");

            $list->add($new);
        }

        return $list;
    }
}
