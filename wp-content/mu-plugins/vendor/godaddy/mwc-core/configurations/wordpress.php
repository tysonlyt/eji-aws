<?php

use GoDaddy\WordPress\MWC\Core\Events\Site\SiteDescriptionEvent;
use GoDaddy\WordPress\MWC\Core\Events\Site\SiteLogoEvent;
use GoDaddy\WordPress\MWC\Core\Events\Site\SiteTitleEvent;

return [
    /*
     *--------------------------------------------------------------------------
     * Domain Attach Flow
     *--------------------------------------------------------------------------
     *
     * enable & configure the domain attachment flow notices in Settings > General and Settings > Reading
     *
     */
    'domainAttachFlow' => [
        'showNotices'   => false,
        'attachmentUrl' => '',
    ],
    /*
     * --------------------------------------------------------------------------
     * Permalinks
     * --------------------------------------------------------------------------
     *
     * Allow plain permalinks. Since some MWCS features break when plain permalinks are set, we force "post name"
     * permalinks via `EnforcePostNamePermalinksInterceptor` by default.  This constant enables overriding that
     * feature in wp-config.php.
     *
     */
    'permalinks' => [
        'allowPlain' => defined('MWC_PERMALINKS_ALLOW_PLAIN') ? MWC_PERMALINKS_ALLOW_PLAIN : false,
    ],
    /*
     * --------------------------------------------------------------------------
     * Monitored Items
     * --------------------------------------------------------------------------
     *
     * WordPress has some common data structures. Any data structures listed below will be monitored using Event Bridge.
     *
     */
    'monitoredItems' => [
        'options' => [
            'site_logo'       => [SiteLogoEvent::class],
            'blogname'        => [SiteTitleEvent::class],
            'blogdescription' => [SiteDescriptionEvent::class],
        ],
    ],
];
