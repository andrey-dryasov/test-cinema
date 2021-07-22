<?php
declare(strict_types=1);

namespace App\Enum;

class SignificanceType extends EnumType
{
    public const PRINCIPAL = 'principal';
    public const SECONDAIRE = 'secondaire';

    protected string $name = self::class;

    protected array $values = [
        self::PRINCIPAL => self::PRINCIPAL,
        self::SECONDAIRE => self::SECONDAIRE,
    ];
}