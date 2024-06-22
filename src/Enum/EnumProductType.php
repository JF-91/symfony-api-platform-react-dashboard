<?php 
namespace App\Enum;

enum EnumProductType : string
{
    case CLOTHES = 'clothes';
    case PAITING = 'painting';
    case ACCESSORIES = 'accessories';
    case ART = 'art';

    case ANOTHER = 'another';

    public static function productCategoriesList(): array
    {
        return [
            self::CLOTHES->value,
            self::PAITING->value,
            self::ACCESSORIES->value,
            self::ART->value,
            self::ANOTHER->value,
        ];
    }
}