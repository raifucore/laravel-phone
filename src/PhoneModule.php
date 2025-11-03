<?php

namespace RaifuCore\Phone;

use RaifuCore\Phone\Actions\GetAllAction;
use RaifuCore\Phone\Actions\GetDtoByPhone;
use RaifuCore\Phone\Dto\PhoneDto;
use Illuminate\Support\Collection;
use RaifuCore\Phone\Enums\ProviderLabelEnum;
use RaifuCore\Phone\Exceptions\ProviderParamsException;
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

    public static function sorted(array $priorityCountries = null): Collection
    {
        $collection = self::getAll();

        // step 1: отсортировать по country_iso -> Россия, Казахстан, Кыргызстан
        // step 2: остальные отсортировать по алфавиту, поле country

        return $collection->sortBy(function ($phoneFormat) {
            $countryIso = $phoneFormat->country_iso;

            // Приоритетные страны идут первыми
            $priorityCountries = ['ru', 'kz', 'kg'];
            $priorityIndex = array_search($countryIso, $priorityCountries);

            if ($priorityIndex !== false) {
                // Возвращаем индекс приоритета (0, 1, 2)
                return str_pad($priorityIndex, 4, '0', STR_PAD_LEFT);
            }

            // Остальные страны сортируются по алфавиту по полю country
            // Для кириллических символов используем правильную сортировку
            return 1000 . $phoneFormat->country;
        });
    }
}
