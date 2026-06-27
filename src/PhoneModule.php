<?php

namespace RaifuCore\Phone;

use RaifuCore\Phone\Actions\FindTemplateByPhone;
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
    public static function getProvider(?ProviderLabelEnum $label = null): Interfaces\ProviderInterface
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

    public static function format(?string $phone): ?string
    {
        $phone = is_string($phone) ? preg_replace('[\D]', '', $phone) : '';
        if (!$phone) {
            return null;
        }

        $phoneTemplate = (new FindTemplateByPhone($phone))->execute();
        if (!$phoneTemplate) {
            return $phone;
        }

        $code = (string)$phoneTemplate->getCode();
        if ($code === '') {
            return $phone;
        }

        $body = substr($phone, strlen($code));
        $mask = (string)$phoneTemplate->getMask();
        if ($mask === '') {
            return '+' . $code . $body;
        }

        $bodyPosition = 0;
        $formattedBody = preg_replace_callback('/_/', static function () use (&$bodyPosition, $body): string {
            return $body[$bodyPosition++] ?? '';
        }, $mask);

        if ($formattedBody === null) {
            return '+' . $phone;
        }

        if ($bodyPosition < strlen($body)) {
            $formattedBody .= substr($body, $bodyPosition);
        }

        $formattedBody = trim($formattedBody);
        return $formattedBody !== ''
            ? '+' . $code . ' ' . $formattedBody
            : '+' . $code;
    }

    public static function filter(Collection $collection, ?array $countries = null): Collection
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

    public static function sort(Collection $collection, ?array $countries = null): Collection
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
