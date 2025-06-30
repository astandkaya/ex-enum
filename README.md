# ExEnum

A tiny helper library that enriches PHP 8.1+ native `enum`  
with **labels**, **tags**, and a handful of utility methods.

- **PHP ≥ 8.1** ‑ native `enum` required  
- No external dependencies – pure PHP

---

## Installation

```bash
composer require astandkaya/ex-enum
```

---

## Quick Start

```php
use ExEnum\Attributes\Extension;
use ExEnum\Traits\HasExtension;

enum Suit: string
{
    /* 1. Add the trait */
    use HasExtension;

    /* 2. Decorate each case with #[Extension(...)] */
    #[Extension(
        label: 'ハート', // optional, default is case name
        tags: ['red'], // optional, default is `[]`
        order: 1, // optional, default is `0`
    )]
    case Hearts = 'hearts';

    #[Extension(
        label: 'ダイア',
        tags: ['red', 'not_hearts'],
        order: 2,
    )]
    case Diamonds = 'diamonds';

    #[Extension(
        label: 'クラブ',
        tags: ['black', 'not_hearts'],
        order: 3,
    )]
    case Clubs = 'clubs';

    #[Extension(
        label: 'スペード',
        tags: ['black', 'not_hearts'],
        order: -1,
    )]
    case Spades = 'spades';
}
```

Every `Suit` case now carries an **arbitrary label** (string) and **tags** (string[]),  
plus your enum automatically gains dozens of helper methods 👇

---

## Usage

### Locate a case by *name*

```php
Suit::fromName('Hearts');
// enum(Suit::Hearts)

Suit::tryFromName('Spades');
// enum(Suit::Hearts) or null
```

### Enumerate metadata

```php
Suit::names();
// array(4) {
//   [0]=>
//   string(6) "Hearts"
//   [1]=>
//   string(8) "Diamonds"
//   [2]=>
//   string(5) "Clubs"
//   [3]=>
//   string(6) "Spades"
// }

Suit::values();
// array(4) {
//   [0]=>
//   string(6) "hearts"
//   [1]=>
//   string(8) "diamonds"
//   [2]=>
//   string(5) "clubs"
//   [3]=>
//   string(6) "spades"
// }

Suit::labels();
// array(4) {
//   [0]=>
//   string(9) "ハート"
//   [1]=>
//   string(9) "ダイア"
//   [2]=>
//   string(9) "クラブ"
//   [3]=>
//   string(12) "スペード"
// }
```

### Filter cases by *tags*

```php
Suit::casesOnly(['not_hearts']);
// array(3) {
//   [0]=>
//   enum(Suit::Diamonds)
//   [1]=>
//   enum(Suit::Clubs)
//   [2]=>
//   enum(Suit::Spades)
// }

Suit::casesExcept(['red']);
// array(2) {
//   [0]=>
//   enum(Suit::Clubs)
//   [1]=>
//   enum(Suit::Spades)
// }
```

### Check if a case has a tag
```php
$card = Suit::Hearts;

$card->hasTag('red');
// bool(true)
$card->hasTag('not_hearts');
// bool(false)

$card->hasTags(['red', 'not_hearts']);
// bool(true)
$card->hasTags(['red', 'unknown']);
// bool(false)
```

### Sort cases by *order*

```php
Suit::sortBy();
// array(4) {
//   [0]=>
//   enum(Suit::Spades)
//   [1]=>
//   enum(Suit::Hearts)
//   [2]=>
//   enum(Suit::Diamonds)
//   [3]=>
//   enum(Suit::Clubs)
// }

Suit::sortByAsc();
// array(4) {
//   [0]=>
//   enum(Suit::Spades)
//   [1]=>
//   enum(Suit::Hearts)
//   [2]=>
//   enum(Suit::Diamonds)
//   [3]=>
//   enum(Suit::Clubs)
// }

Suit::sortByDesc();
// array(4) {
//   [0]=>
//   enum(Suit::Clubs)
//   [1]=>
//   enum(Suit::Diamonds)
//   [2]=>
//   enum(Suit::Hearts)
//   [3]=>
//   enum(Suit::Spades)
// }
```

### Work with a single enum instance

```php
$card = Suit::Hearts;

$card->label();
// string(9) "ハート"

$card->isHearts();
// bool(true)

$card->isDiamonds();
// bool(false)
```

## Future implementation plans

| Method                               | Returns                               | Description                                    |
|--------------------------------------|---------------------------------------|------------------------------------------------|
| `Suit::map(fn($case) => …)`          | array                                 | Generic map over all cases                     |
| `Suit::filter(fn($case) => …)`       | `static[]`                            | Generic filter over all cases                  |
| `Suit::each(fn($case) => …)`         | void                                  | Iterate through all cases                      |
| `Suit::random()`                     | `static`                              | Pick a random case                             |
| `Suit::toArray()`                    | array `[name => value, …]`            | Enum to associative array                      |
| `Suit::jsonSerialize()`              | same as `toArray()`                   | JSON-ready representation                      |
