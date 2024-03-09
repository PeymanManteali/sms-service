<?php

namespace SmsService\Enums\Concerns;

use Illuminate\Support\Str;
use ReflectionClassConstant;
use App\Enums\Attributes\TranslateKey;

trait GetsAttributes
{

    /**
     * @param \App\Enums\Concerns\GetsAttributes $enum
     * @return string string of translated message
     */
    private static function getTranslatedName(self $enum): string
    {
        $ref = new ReflectionClassConstant(self::class, $enum->name);
        $classAttributes = $ref->getAttributes(TranslateKey::class);

        if (count($classAttributes) === 0) {
            return Str::headline($enum->value);
        }

        return __($classAttributes[0]->newInstance()->translateKey);
    }

    /**
     * @return array<string,string>
     */
    public static function asSelectArray(): array
    {
        /** @var array<string,string> $values */
        $values = collect(self::cases())
            ->map(function ($enum) {
                return (object) [
                    'name' => self::getTranslatedName($enum),
                    'value' => $enum->value,
                ];
            })->toArray();

        return $values;
    }

    /**
     * @return array<string>
     */
    public static function asDatabaseEnum(): array
    {
        /** @var array<string> $values */
        $values = collect(self::cases())
            ->map(function ($enum) {
                return $enum->value;
            })->toArray();

        return $values;
    }


}
