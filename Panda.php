<?php

class P
{
    public static function pipe($array) {
        return function ($init) use ($array) {
            $fn = function ($a, $c) {
                return $c($a);
            };

            return self::reduce($fn, $init, $array);
        };
    }

    public static function comp(&$a, &$b)
    {
        return function ($init) use (&$a, &$b) {
            return $a($b($init));
        };
    }

    public static function reduce($cb, $init = null, $array = null) {
        $argLength = 3;
        $suppliedArgs = func_get_args();

        $fn = function ($cb, $init, $array) {
            $a = $init;

            foreach($array as $k => $v) {
                $a = $cb($a, $v, $k);
            }

            return $a;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function map($cb, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($cb, $array) {
            $out = [];

            foreach($array as $k => $v) {
                $out[$k] = $cb($v, $k);
            }

            return $out;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function filter($cb, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($cb, $array) {
            $out = [];

            foreach($array as $k => $v) {
                if ($cb($v, $k)) {
                    $out[$k] = $v;
                }
            }

            return $out;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function propEq($prop, $value = null, $array = null)
    {
        $argLength = 3;
        $suppliedArgs = func_get_args();

        $fn = function ($prop, $value, $array) {
            return self::prop($prop, $array) === $value;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function path($path, $array)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($path, $array) {
            return self::reduce(function ($a, $c) {
                return self::prop($c, $a);
            }, $array, $path);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function flip($fn)
    {
        return function($a, $b) {
            return $fn($b, $a);
        };
    }

    public static function assoc($prop, $value = null, $array = null)
    {
        $argLength = 3;
        $suppliedArgs = func_get_args();

        $fn = function($prop, $value, $array) {
            $out = [];

            foreach($array as $k => $v) {
                $out[$k] = $v;
            }

            $out[$prop] = $value;

            return $out;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function assocPath($path, $value = null, $array = null)
    {
        $argLength = 3;
        $suppliedArgs = func_get_args();

        $fn = function($path, $value, $array) {
            if (count($path) === 0) {
                return $value;
            }

            $idx = $path[0];

            if (count($path) > 1) {
                $value = self::assocPath(array_slice($path, 1), $value, $array[$idx]);
            }

            return self::assoc($idx, $value, $array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function dissoc($prop, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($prop, $array) {
            return array_diff_key($array, [$prop => null]);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function prop($prop, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($prop, $array) {
            if (is_null($a) || !isset($a[$c])) {
                return null;
            }

            return $array[$prop];
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    private static function callOrDelay($argLength, &$suppliedArgs, &$fn)
    {
        if (count($suppliedArgs) === $argLength) {
            return call_user_func_array($fn, $suppliedArgs);
        }

        $closure = function () use ($argLength, &$suppliedArgs, &$fn, &$closure) {
            $args = func_get_args();

            $suppliedArgs = array_merge($suppliedArgs, $args);

            return count($suppliedArgs) === $argLength ?
                call_user_func_array($fn, $suppliedArgs) :
                $closure;
        };

        return $closure;
    }
}

