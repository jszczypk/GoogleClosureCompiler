<?php

declare(strict_types=1);

namespace JSzczypk\GoogleClosureCompiler;

enum LanguageOut: string
{
    case ECMASCRIPT3 = 'ECMASCRIPT3';
    case ECMASCRIPT5 = 'ECMASCRIPT5';
    case ECMASCRIPT5_STRICT = 'ECMASCRIPT5_STRICT';
    case ECMASCRIPT_2015 = 'ECMASCRIPT_2015';
    case ECMASCRIPT_2016 = 'ECMASCRIPT_2016';
    case ECMASCRIPT_2017 = 'ECMASCRIPT_2017';
    case ECMASCRIPT_2018 = 'ECMASCRIPT_2018';
    case ECMASCRIPT_2019 = 'ECMASCRIPT_2019';
    case ECMASCRIPT_2020 = 'ECMASCRIPT_2020';
    case ECMASCRIPT_2021 = 'ECMASCRIPT_2021';
    case STABLE = 'STABLE';
    case ECMASCRIPT_NEXT = 'ECMASCRIPT_NEXT';

    public function getExtension(): string
    {
        return match ($this) {
            self::ECMASCRIPT_2021 => '-es2021',
            self::ECMASCRIPT_2020 => '-es2020',
            self::ECMASCRIPT_2019 => '-es2019',
            self::ECMASCRIPT_2018 => '-es2018',
            self::ECMASCRIPT_2017 => '-es2017',
            self::ECMASCRIPT_2016 => '-es2016',
            self::ECMASCRIPT_2015 => '-es2015',
            self::ECMASCRIPT5 => '-es5',
            self::ECMASCRIPT3 => '-es3',
            self::ECMASCRIPT5_STRICT => '-es5strict',
            self::STABLE => '',
            self::ECMASCRIPT_NEXT => '-esnext',
        }.'.js';
    }

    public static function getByUserAgent(string $userAgent): self
    {
        if (str_contains($userAgent, 'Trident/7.0; rv:11.0')) {
            return self::ECMASCRIPT5;
        }
        if (str_contains($userAgent, 'MSIE 10.0')) {
            return self::ECMASCRIPT5;
        }
        if (str_contains($userAgent, 'MSIE 9.0')) {
            return self::ECMASCRIPT5;
        }
        if (str_contains($userAgent, 'MSIE')) {
            return self::ECMASCRIPT3;
        }

        return self::ECMASCRIPT_2015;
    }
}
