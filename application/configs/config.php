<?php

use DI\Container;

return array(
    'Extlib\System' => \DI\factory(function (Container $c) {
        return \Extlib\System::getInstance();
    }),
    'Extlib\System\Cookie' => \DI\object('Extlib\System\Cookie'),
    'Extlib\System\IpAddress' => \DI\object('Extlib\System\IpAddress'),
    'Extlib\System\Url' => \DI\object('Extlib\System\Url'),
    'Extlib\System\Browser' => \DI\object('Extlib\System\Browser'),
    'System\Session' => \DI\object('Zend_Session_Namespace')->constructor('system-session')
);