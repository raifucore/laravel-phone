<?php

namespace RaifuCore\Phone;

use RaifuCore\Phone\Actions\GetAllAction;
use RaifuCore\Phone\Actions\GetDtoBySource;
use RaifuCore\Phone\Dto\PhoneDto;
use Illuminate\Support\Collection;

class PhoneModule
{
    public static function getDtoBySource(string $source): PhoneDto
    {
        return (new GetDtoBySource($source))->execute();
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
