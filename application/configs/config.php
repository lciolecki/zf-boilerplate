<?php

use DI\Container;

return array(
    'Extlib\System' => \DI\factory(function (Container $c) {
        return \Extlib\System::getInstance();
    }),
    'Extlib\System\Cookie' => \DI\object('Extlib\System\Cookie')->constructor('system-cookie'),
    'System\Session' => \DI\object('Zend_Session_Namespace')->constructor('system-session')
);