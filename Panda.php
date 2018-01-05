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

    public static function contains($value, $array = null) {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($value, $array) {
            return in_array($value, $array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function has($key, $array = null) {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($key, $array) {
            return array_key_exists($key, $array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function add($x, $y = null) {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($x, $y) {
            return $x + $y;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function inc($value = null) {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function ($value) {
            return $value + 1;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function reduce($cb, $init = null, $array = null) 
    {
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

    public static function reduceWhile($pred, $cb = null, $init = null, $array = null) 
    {
        $argLength = 4;
        $suppliedArgs = func_get_args();

        $fn = function ($pred, $cb, $init, $array) {
            $a = $init;

            foreach($array as $k => $v) {
                if ($pred($a, $v, $k)) {
                    return $a;
                }

                $a = $cb($a, $v, $k);
            }

            return $a;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function concat($xs, $ys = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($xs, $ys) {
            return array_merge($xs, $ys);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function append($x, $xs = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($x, $xs) {
            return array_merge($xs, [$x]);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function emptyOf($any = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function ($any) {
            switch (gettype($any)) {
                case 'boolean':
                    return false;
                case 'integer':
                    return 0;
                case 'double':
                    return 0.00;
                case 'string':
                    return '';
                case 'array':
                    return [];
                case 'object':
                    return new stdClass;
                default:
                    return null;
            }
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function isEmpty($any = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function ($any) {
            return $any === self::emptyOf($any);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function init($array = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function ($array) {
            return array_slice($xs, 0, -1);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function prepend($x, $xs = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($x, $xs) {
            return array_merge([$x], $xs);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function reduceRight($cb, $init = null, $array = null) {
        $argLength = 3;
        $suppliedArgs = func_get_args();

        $fn = function ($cb, $init, $array) {
            $a = $init;
            $reversed = self::reverse($array);

            foreach($reversed as $k => $v) {
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

    public static function pick($values, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($values, $array) {
            $out = [];

            foreach($array as $k => $v) {
                if (self::contains($k, $values)) {
                    $out[$k] = $v;
                }
            }

            return $out;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function omit($values, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($values, $array) {
            $out = [];

            foreach($array as $k => $v) {
                if (!self::contains($k, $values)) {
                    $out[$k] = $v;
                }
            }

            return $out;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function reject($cb, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function ($cb, $array) {
            $out = [];

            foreach($array as $k => $v) {
                if (!$cb($v, $k)) {
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

    public static function pathEq($path, $value = null, $array = null)
    {
        $argLength = 3;
        $suppliedArgs = func_get_args();

        $fn = function ($path, $value, $array) {
            return self::path($path, $array) === $value;
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

    public static function find($cb, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($cb, $array) {
            foreach($array as $k => $v) {
                if ($cb($v, $k)) {
                    return $v;
                }
            }

            return null;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function findLast($cb, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($cb, $array) {
            $reversed = self::reverse($array);

            foreach($reversed as $k => $v) {
                if ($cb($v, $k)) {
                    return $v;
                }
            }

            return null;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function reverse($array = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($array) {
            $out = [];

            foreach($array as $v) {
                array_unshift($out, $v);
            }

            return $out;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function not($value = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($value) {
            return !$value;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function keys($array = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($array) {
            return $array_keys($array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function head($array = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($array) {
            return $array[0];
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function tail($array = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($array) {
            return array_slice($array, 1);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function eq($x, $y = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($x, $y) {
            return $x === $y;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function complement($cb)
    {
        return function ($val) use ($cb) {
            return !$cb($val);
        };
    }

    public static function all($cb, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($cb, $array) {
            return count(self::filter($cb, $array)) === count($array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function any($cb, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($cb, $array) {
            return count(self::filter($cb, $array)) > 0;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function anyPass($cbs, $value = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($cbs, $value) {
            $passes = self::filter(function ($cb) use ($value) {
                return $cb($value);
            }, $cbs);

            return count($passes) > 0;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function allPass($cbs, $value = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($cbs, $value) {
            $passes = self::filter(function ($cb) use ($value) {
                return $cb($value);
            }, $cbs);

            return count($passes) === count($cbs);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function last($array = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($array) {
            return $array[count($array) - 1];
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function dissocPath($path, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($path, $array) {
            if (count($path) === 0) {
                return $array;
            }

            $idx = $path[0];

            if (count($path) === 1) {
                return self::dissoc($idx, $array);
            }

            if ($array[$idx] === null) {
                return $array;
            }

            return self::assoc($idx, self::dissocPath(array_slice($path, 1), $array[$idx]), $array);
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
            if (is_null($prop) || !isset($array[$prop])) {
                return null;
            }

            return $array[$prop];
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function explode($delimeter, $str = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($delimeter, $str) {
            return explode($delimeter, $str);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function join($glue, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($glue, $array) {
            return implode($delimeter, $array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function flatten($array = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($array) use (&$fn) {
            return self::reduce(function ($a, $c) use ($fn) {
                if (gettype($c) === 'array') {
                    return self::concat($a, $fn($c));
                }

                return self::append($c, $a);
            }, [], $array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function id($any = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($any) {
            return $any;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function T($any = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($any) {
            return true;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function pluck($key, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($key, $array = null) {
            return self::map(function($v, $k) use ($key) {
                return $v[$key];
            }, $array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function F($any = null)
    {
        $argLength = 1;
        $suppliedArgs = func_get_args();

        $fn = function($any) {
            return false;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }


    public static function pathOr($default, $path, $array = null)
    {
        $argLength = 3;
        $suppliedArgs = func_get_args();

        $fn = function($default, $path, $array) {
            $value = self::path($path, $array);

            return is_null($value) ? $default : $value;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function propOr($default, $prop, $array = null)
    {
        $argLength = 3;
        $suppliedArgs = func_get_args();

        $fn = function($default, $prop, $array) {
            $value = self::prop($prop, $array);

            return is_null($value) ? $default : $value;
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function splitEvery($interval, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($interval, $array) {
            $count = 1;

            return self::reduce(function ($a, $c, $k) use ($interval, &$count) {
                $last = self::last($a);

                if (count($last) < $interval) {
                    $a[count($a) - 1] = self::append($c, $last);
                    $count++;
                    return $a;
                }

                $a = self::append([$c], $a);

                $count = 1;

                return $a;
            }, [[]], $array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    public static function splitWhen($pred, $array = null)
    {
        $argLength = 2;
        $suppliedArgs = func_get_args();

        $fn = function($pred, $array) {
            return self::reduce(function ($a, $c, $k) use ($pred) {
                $last = self::last($a);

                if ($pred($c, $k)) {
                    $a = self::append([$c], $a);
                    return $a;
                }

                $a[count($a) - 1] = self::append($c, $last);
                return $a;
            }, [[]], $array);
        };

        return self::callOrDelay($argLength, $suppliedArgs, $fn);
    }

    protected static function callOrDelay($argLength, $suppliedArgs, $fn)
    {
        if (count($suppliedArgs) >= $argLength) {
            return call_user_func_array($fn, $suppliedArgs);
        }

        $closure = function () use ($argLength, $suppliedArgs, $fn, &$closure) {
            $args = func_get_args();

            $suppliedArgs = array_merge($suppliedArgs, $args);

            return count($suppliedArgs) >= $argLength ?
                call_user_func_array($fn, $suppliedArgs) :
                $closure;
        };

        return $closure;
    }
}

