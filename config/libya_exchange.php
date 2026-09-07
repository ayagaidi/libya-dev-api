<?php

return [
    'source_url' => 'https://cbl.gov.ly/en/currency-exchange-rates/',
    'source_name' => 'Central Bank of Libya',
    'cache_ttl_seconds' => (int) env('CBL_EXCHANGE_CACHE_TTL', 1800),
    'stale_ttl_seconds' => (int) env('CBL_EXCHANGE_STALE_TTL', 604800),
    'currencies' => [
        'USD' => ['name_en' => 'American Dollar', 'source_names' => ['American Dollar', 'US Dollar'], 'unit_multiplier' => 1],
        'EUR' => ['name_en' => 'Euro', 'source_names' => ['Euro'], 'unit_multiplier' => 1],
        'GBP' => ['name_en' => 'Pound Sterling', 'source_names' => ['Pound', 'British Pound', 'Pound Sterling'], 'unit_multiplier' => 1],
        'CAD' => ['name_en' => 'Canadian Dollar', 'source_names' => ['Canadian Dollar'], 'unit_multiplier' => 1],
        'AUD' => ['name_en' => 'Australian Dollar', 'source_names' => ['Australian Dollar'], 'unit_multiplier' => 1],
        'CHF' => ['name_en' => 'Swiss Franc', 'source_names' => ['Swiss Franc'], 'unit_multiplier' => 1],
        'SEK' => ['name_en' => 'Swedish Krona', 'source_names' => ['Swedish Krona'], 'unit_multiplier' => 1],
        'JPY' => ['name_en' => 'Japanese Yen', 'source_names' => ['Japanese Yen', 'Yen'], 'unit_multiplier' => 100],
        'AED' => ['name_en' => 'UAE Dirham', 'source_names' => ['UAE Dirham', 'Emirati Dirham', 'United Arab Emirates Dirham'], 'unit_multiplier' => 1],
        'TND' => ['name_en' => 'Tunisian Dinar', 'source_names' => ['Tunisian Dinar'], 'unit_multiplier' => 1],
        'DZD' => ['name_en' => 'Algerian Dinar', 'source_names' => ['Algerian Dinar'], 'unit_multiplier' => 10],
        'MAD' => ['name_en' => 'Moroccan Dirham', 'source_names' => ['Moroccan Dirham'], 'unit_multiplier' => 1],
        'MRU' => ['name_en' => 'Mauritanian Ouguiya', 'source_names' => ['Mauritanian Ouguiya', 'Ouguiya'], 'unit_multiplier' => 100],
        'XOF' => ['name_en' => 'West African CFA Franc', 'source_names' => ['African Franc', 'CFA Franc'], 'unit_multiplier' => 1],
        'RUB' => ['name_en' => 'Russian Ruble', 'source_names' => ['Russian Ruble', 'Ruble'], 'unit_multiplier' => 10],
        'TRY' => ['name_en' => 'Turkish Lira', 'source_names' => ['Turkish Lira'], 'unit_multiplier' => 1],
        'CNY' => ['name_en' => 'Chinese Yuan', 'source_names' => ['Chinese Yuan', 'Yuan', 'Chinese Renminbi'], 'unit_multiplier' => 1],
    ],
];
