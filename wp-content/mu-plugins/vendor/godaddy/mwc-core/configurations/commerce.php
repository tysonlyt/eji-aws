<?php

return [
    'customers' => [
        'api' => [
            'url' => [
                'prod' => defined('MWC_COMMERCE_CUSTOMERS_SERVICE_URL') ? MWC_COMMERCE_CUSTOMERS_SERVICE_URL : 'https://api.mwc.secureserver.net',
                'dev'  => defined('MWC_COMMERCE_CUSTOMERS_SERVICE_URL') ? MWC_COMMERCE_CUSTOMERS_SERVICE_URL : 'https://api-test.mwc.secureserver.net',
            ],
        ],
    ],
    'catalog' => [
        'api' => [
            'url' => [
                'prod' => defined('MWC_COMMERCE_CATALOG_SERVICE_URL') ? MWC_COMMERCE_CATALOG_SERVICE_URL : 'https://api.mwc.secureserver.net',
                'dev'  => defined('MWC_COMMERCE_CATALOG_SERVICE_URL') ? MWC_COMMERCE_CATALOG_SERVICE_URL : 'https://api-test.mwc.secureserver.net',
            ],
            'timeout' => [
                'prod' => defined('MWC_COMMERCE_CATALOG_SERVICE_TIMEOUT') ? MWC_COMMERCE_CATALOG_SERVICE_TIMEOUT : 3,
                'dev'  => defined('MWC_COMMERCE_CATALOG_SERVICE_TIMEOUT') ? MWC_COMMERCE_CATALOG_SERVICE_TIMEOUT : 20,
            ],
            /*
             * Maximum number of UUIDs to include in an `ids` filter. We've determined this to be the maximum value
             * we can retrieve without having too long of a query string.
             */
            'maxIdsPerRequest' => 100,
        ],
        'website' => [
            'categoriesUrl' => [
                'prod' => 'https://spa.commerce.godaddy.com/categories',
                'dev'  => 'https://spa.commerce.test-godaddy.com/categories',
            ],
        ],
    ],
    'inventory' => [
        'api' => [
            'url' => [
                'prod' => defined('MWC_COMMERCE_INVENTORY_SERVICE_URL') ? MWC_COMMERCE_INVENTORY_SERVICE_URL : 'https://api.mwc.secureserver.net/v1/commerce/proxy',
                'dev'  => defined('MWC_COMMERCE_INVENTORY_SERVICE_URL') ? MWC_COMMERCE_INVENTORY_SERVICE_URL : 'https://api-test.mwc.secureserver.net/v1/commerce/proxy',
            ],
            'timeout' => [
                'prod' => defined('MWC_COMMERCE_INVENTORY_SERVICE_TIMEOUT') ? MWC_COMMERCE_INVENTORY_SERVICE_TIMEOUT : 3,
                'dev'  => defined('MWC_COMMERCE_INVENTORY_SERVICE_TIMEOUT') ? MWC_COMMERCE_INVENTORY_SERVICE_TIMEOUT : 10,
            ],
        ],
    ],
    'gateway' => [
        'api' => [
            'url' => [
                'prod' => defined('MWC_COMMERCE_API_GATEWAY_URL') ? MWC_COMMERCE_API_GATEWAY_URL : 'https://api.mwc.secureserver.net',
                'dev'  => defined('MWC_COMMERCE_API_GATEWAY_URL') ? MWC_COMMERCE_API_GATEWAY_URL : 'https://api-test.mwc.secureserver.net',
            ],
        ],
    ],
];
