<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The ProductVisibility enum.
 *
 * @method static self CATALOG()
 * @method static self SEARCH()
 * @method static self CATALOG_AND_SEARCH()
 */
class ProductVisibility extends Enum
{
    const CATALOG = 0;
    const SEARCH = 1;
    const CATALOG_AND_SEARCH = 2;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::CATALOG => trans('Catalog'),
            static::SEARCH => trans('Search'),
            static::CATALOG_AND_SEARCH => trans('Catalog and Search'),
        ];
    }
}
