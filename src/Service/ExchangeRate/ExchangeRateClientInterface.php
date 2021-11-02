<?php

declare(strict_types=1);

namespace FeeCalcApp\Service\ExchangeRate;

interface ExchangeRateClientInterface
{
    public function getExchangeRate(string $currency1, string $currency2): float;
}
