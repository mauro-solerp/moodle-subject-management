<?php

// RISK_SPAM - user can add visible content to site, send messages to other users; originally protected by !isguest()
// RISK_PERSONAL - access to private personal information - ex: backups with user details, non public information in profile (hidden email), etc.; originally protected by isteacher()
// RISK_XSS - user can submit content that is not cleaned (both HTML with active content and unprotected files); originally protected by isteacher()
// RISK_CONFIG - user can change global configuration, actions are missing sanity checks
// RISK_MANAGETRUST - manage trust bitmasks of other users
// RISK_DATALOSS - can destroy large amounts of information that cannot easily be recovered.

$capabilities = [ 

    'block/dode:addinstance' => [
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => [
            'manager' => CAP_ALLOW
        ]
    ],

    'block/dode:manage' => [ 
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => [
            'manager' => CAP_ALLOW
        ]
    ],

    'block/dode:managesubjects' => [
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => [ 
            'manager' => CAP_ALLOW
        ]
    ]

];