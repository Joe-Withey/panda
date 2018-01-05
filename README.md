# Panda

Composable, reusable, pure functions for PHP. Inspired by Ramda in the JS world. It's supposed to be a naive PHP port.

## Example

```
<?php

require 'Panda.php';

$xs = [1, 2 ,3, [[4]], [5], 6, 8, 9];

$flattenReverseAndIncrease = P::pipe([
    P::flatten(),
    P::reverse(),
    P::map(P::inc())
]);

print_r($flattenReverseAndIncrease($xs));

```

### Output

```
Array
(
    [0] => 10
    [1] => 9
    [2] => 7
    [3] => 6
    [4] => 5
    [5] => 4
    [6] => 3
    [7] => 2
)

```

## TODO

- Implement as a PSR-4 PHP package.
- Consider a better way of declaring library functions.
- Unit tests.
- Travis.
- Investigate performance.

### Ideas

- Create adapters for Doctrine and Laravel collections.
- Create function implementations to work with Arrays and Objects.

### Function implementations

- [] ascend
- [x] identity
- [] comparator
- [x] concat
- [x] contains
- [] curryN
- [] descend
- [x] F
- [x] find
- [x] findLast
- [x] flatten
- [] groupBy
- [x] has
- [x] init
- [x] isEmpty
- [x] join
- [x] keys
- [] merges?
- [x] omit
- [x] pathEq
- [x] pick
- [x] pluck
- [x] pathOr
- [x] propOr
- [x] reduceRight
- [x] reduceWhile
- [x] reject
- [x] reverse
- [] sort
- [] sortWith
- [x] split
- [x] splitEvery
- [x] splitWhen
- [x] T
- [] uniq
- [] uniqBy
- [] unless
- [] when
- [] where
- [] whereEq

