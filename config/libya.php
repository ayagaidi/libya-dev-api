<?php

return [
    'api_version' => 'v1',
    'country' => [
        'name_en' => 'Libya',
        'name_ar' => 'ليبيا',
        'iso2' => 'LY',
        'iso3' => 'LBY',
        'calling_code' => '+218',
        'currency' => 'LYD',
        'timezone' => 'Africa/Tripoli',
    ],
    'rate_limit_per_minute' => (int) env('LIBYA_API_RATE_LIMIT', 60),
    'locations' => [
        'version' => env('LIBYA_LOCATIONS_VERSION', '1.2.0'),
        'cache_ttl' => (int) env('LIBYA_LOCATIONS_CACHE_TTL', 86400),
        'repository' => 'https://github.com/ayagaidi/libyancityseeds',
        'municipalities_url' => 'https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/municipalities.json',
        'cities_url' => 'https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/cities.json',
        'points_url' => 'https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/municipality-points.json',
        'geojson_url' => 'https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/municipality-points.geojson',
    ],
    'telecom_operators' => [
        [
            'slug' => 'almadar',
            'name_en' => 'Almadar Aljadid',
            'name_ar' => 'المدار الجديد',
            'prefixes' => ['091', '093'],
            'verification' => 'current_primary_source',
            'sources' => [
                'https://tawasul.cim.gov.ly/HowToUse',
                'https://wazi.almadar.ly/',
            ],
        ],
        [
            'slug' => 'libyana',
            'name_en' => 'Libyana',
            'name_ar' => 'ليبيانا',
            'prefixes' => ['092', '094'],
            'verification' => 'current_primary_source',
            'sources' => [
                'https://libyana.ly/call-me/',
                'https://libyana.ly/sim-card/',
            ],
        ],
        [
            'slug' => 'libyaphone',
            'name_en' => 'LibyaPhone / LTT',
            'name_ar' => 'ليبيافون / ليبيا للاتصالات والتقنية',
            'prefixes' => ['095'],
            'verification' => 'historical_numbering_plan_current_operator_reference',
            'sources' => [
                'https://www.itu.int/dms_pub/itu-t/opb/sp/T-SP-OB.854-2006-OAS-PDF-E.pdf',
                'https://www.cim.gov.ly/numbering_companies.html',
            ],
        ],
    ],
];
