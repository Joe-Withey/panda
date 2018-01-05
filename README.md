# panda

Composable, reusable functions for PHP. Influenced by Ramda in the JS world.

## Example

```
<?php

require 'Panda.php';

$xs = [1, 2 ,3, [[4]], [5], 6, 8, 9];

$flattenAndIncrease = P::pipe([
    P::flatten(),
    P::map(P::inc())
]);

print_r($flattenAndIncrease($xs));

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

