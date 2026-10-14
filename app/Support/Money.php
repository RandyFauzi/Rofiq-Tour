<?php

namespace App\Support;

final class Money
{
    public static function rupiah(?int $amount): string
    {
        return $amount === null ? 'Hubungi kami' : 'Rp '.number_format($amount, 0, ',', '.');
    }

    public static function compact(int $amount): string
    {
        return $amount < 1000000 ? self::rupiah($amount) : 'Rp '.rtrim(rtrim(number_format($amount / 1000000, 1, ',', '.'), '0'), ',').' jt';
    }
}
