<?php

namespace RaifuCore\Phone;

use RaifuCore\Phone\Actions\GetAllAction;
use RaifuCore\Phone\Actions\GetDtoByPhone;
use RaifuCore\Phone\Dto\PhoneDto;
use Illuminate\Support\Collection;
use RaifuCore\Phone\Enums\ProviderLabelEnum;
use RaifuCore\Phone\Exceptions\ProviderParamsException;
use RaifuCore\Phone\Models\PhoneFormat;
use RaifuCore\Phone\Providers\Factory;

class PhoneModule
{
    /**
     * @throws ProviderParamsException
     */
    public static function getProvider(ProviderLabelEnum $label = null): Interfaces\ProviderInterface
    {
        return (new Factory($label))->init();
    }

    public static function getDtoByPhone(string $phone): PhoneDto|null
    {
        return (new GetDtoByPhone($phone))->execute();
    }

    public static function getAll(): Collection
    {
        return (new GetAllAction())->execute();
    }

    public static function filter(Collection $collection, array|null $countries = null): Collection
    {
        $countries = collect($countries ?? [])
            ->map(static fn (string $countryIso): string => mb_strtolower($countryIso))
            ->values()
            ->all();

        if (empty($countries)) {
            return collect();
        }

        return $collection
            ->filter(static fn (PhoneFormat $phoneFormat): bool => in_array(
                mb_strtolower($phoneFormat->country_iso),
                $countries,
                true
            ))->values();
    }

    public static function sort(Collection $collection, array|null $countries = null): Collection
    {
        $priorityCountries = collect($countries ?? [])
            ->map(static fn (string $countryIso): string => mb_strtolower($countryIso))
            ->values()
            ->all();

        $priorityMap = array_flip($priorityCountries);

        return $collection
            ->sort(static function (PhoneFormat $a, PhoneFormat $b) use ($priorityMap): int {
                $aIso = mb_strtolower($a->country_iso);
                $bIso = mb_strtolower($b->country_iso);

                $aPriority = $priorityMap[$aIso] ?? null;
                $bPriority = $priorityMap[$bIso] ?? null;

                if ($aPriority !== null && $bPriority !== null) {
                    return $aPriority <=> $bPriority;
                }

                if ($aPriority !== null) {
                    return -1;
                }

                if ($bPriority !== null) {
                    return 1;
                }

                $aCountry = mb_strtolower($a->country);
                $bCountry = mb_strtolower($b->country);

                return strcmp($aCountry, $bCountry);
            })
            ->values();
    }
}
