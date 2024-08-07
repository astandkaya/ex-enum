# ExEnum
Extend PHP Enum

```php
<?php

use App\Libs\ExEnum\HasExtension;
use App\Libs\ExEnum\Extension;

enum Suit: string
{
    // このトレイトをuseする
    use HasExtension;

    #[Extension(
        label: 'ハート',
        tags: ['red'],
    )]
    case Hearts = 'hearts';

    #[Extension(
        label: 'ダイア',
        tags: ['red', 'not_hearts'],
    )]
    case Diamonds = 'diamonds';

    #[Extension(
        label: 'クラブ',
        tags: ['black', 'not_hearts'],
    )]
    case Clubs = 'clubs';

    #[Extension(
        label: 'スペード',
        tags: ['black', 'not_hearts'],
    )]
    case Spades = 'spades';
}

// nameからEnumを特定する
var_dump(Suit::fromName('Hearts'));
// enum(Suit::Hearts)

// nameからEnumを特定する（存在しないときはnullを返す）
var_dump(Suit::tryFromName('Hearts'));
// enum(Suit::Hearts)

// nameの一覧を取得する
var_dump(Suit::names());
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

// valueの一覧を取得する
var_dump(Suit::values());
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

// labelの一覧を取得する
var_dump(Suit::labels());
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

// not_heartsタグが付いたものだけ取得する
var_dump(Suit::casesOnly(['not_hearts']));
// array(3) {
//   [0]=>
//   enum(Suit::Diamonds)
//   [1]=>
//   enum(Suit::Clubs)
//   [2]=>
//   enum(Suit::Spades)
// }

// redタグが付いていないものだけ取得する
var_dump(Suit::casesExcept(['red']));
// array(2) {
//   [0]=>
//   enum(Suit::Clubs)
//   [1]=>
//   enum(Suit::Spades)
// }


$enum = Suit::Hearts;

// 設定したlabelを取得する
var_dump($enum->label());
// string(9) "ハート"

// EnumがHeartsであることを確認する
var_dump($enum->isHearts());
// bool(true)
var_dump($enum->isDiamonds());
// bool(false)
```
