<?php

namespace HH {
    class ImmMap{
        public function __construct(){}
    }
}

namespace {
    class Helper {
        public static function go(string $Request, HH\ImmMap $GET, HH\ImmMap $POST, HH\ImmMap $SERVER, HH\ImmMap $COOKIE):string {}
    }

    class HTTP {
        public static const GET = 'GET';
    }
}
