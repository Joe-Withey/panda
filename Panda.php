<?php

class P
{
  public static function pipe($array) {
    return function ($init) use ($array) {
      return self::reduce(function ($a, &$c) {
        return $c($a);
      }, $init, $array);
    };
  }

  public static function comp(&$a, &$b)
  {
    return function ($init) use (&$a, &$b) {
      return $a($b($init));
    };
  }

  public static function reduce(&$cb = null, $init = null, $array = null) {
    $argLength = 3;
    $suppliedArgs = func_get_args();

    $fn = function(&$cb, $init, $array) {
      $i = 0;
      $a = $init;

      while ($i < count($array)) {
        $a = $cb($a, $array[$i++]);
      }

      return $a;
    };

    return self::callOrDelay($argLength, $suppliedArgs, $fn);
  }

  public static function map(&$cb = null, $array = null)
  {
    $argLength = 2;
    $suppliedArgs = func_get_args();

    $fn = function (&$cb, $array) {
      $out = [];

      foreach($array as $k => $v) {
        $out[$k] = $cb($v, $k);
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
      return self::reduce();
    };

    return self::callOrDelay($argLength, $suppliedArgs, $fn);
  }

  public static function flip($fn)
  {
    return function($a, $b) {
      return $fn($b, $a);
    };
  }

  public static function prop($prop, $array = null)
  {
    $argLength = 2;
    $suppliedArgs = func_get_args();

    $fn = function($prop, $array) {
      return $array[$prop];
    };

    return self::callOrDelay($argLength, $suppliedArgs, $fn);
  }

  private static function callOrDelay($argLength, &$suppliedArgs, &$fn)
  {
    $closure = function () use ($argLength, &$suppliedArgs, &$fn, &$closure) {
      $args = func_get_args();

      $suppliedArgs = array_merge($suppliedArgs, $args);

      return count($suppliedArgs) === $argLength ?
        call_user_func_array($fn, $suppliedArgs) :
        $closure;
    };

    return count($suppliedArgs) === $argLength ?
      call_user_func_array($fn, $suppliedArgs) :
      $closure;
  }
}

