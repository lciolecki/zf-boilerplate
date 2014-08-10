<?php

use DI\Container;

return [
    'Extlib\System' => \DI\factory(function (Container $c) {
        return \Extlib\System::getInstance();
    }),
    'System\AuthUser' =>  \DI\factory(function (Container $c) {
        if (\Zend_Auth::getInstance()->hasIdentity()) {
            return \Zend_Auth::getInstance()->getIdentity();
        }

        return null;
    }),
    'Extlib\System\Cookie' => \DI\object('Extlib\System\Cookie')->constructor('system-cookie'),
    'System\Session' => \DI\object('Zend_Session_Namespace')->constructor('system-session'),
];


