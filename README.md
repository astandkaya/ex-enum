# ExEnum
Extend PHP Enum

```php
enum Suit: string
{
    use HasExtension;

    #[Extension(
        label: 'ハート',
        tags: ['hoge'],
        order: 0,
    )]
    case Hearts = 'hearts';

    #[Extension(
        label: 'ダイア',
        tags: ['hoge', 'fuga'],
        order: 0,
    )]
    case Diamonds = 'diamonds';

    #[Extension(
        label: 'クラブ',
        tags: ['fuga'],
        order: 0,
    )]
    case Clubs = 'clubs';

    #[Extension(
        label: 'スペード',
        tags: ['piyo'],
        order: 0,
    )]
    case Spades = 'spades';
}

$enum = Suit::Hearts;
var_dump($enum->label());
// string(9) "ハート"

var_dump(Suit::casesOnly(['piyo', 'hoge']));
// array(3) {
//   [0]=>
//   enum(Suit::Hearts)
//   [1]=>
//   enum(Suit::Diamonds)
//   [2]=>
//   enum(Suit::Spades)
// }

var_dump(Suit::casesExcept(['hoge']));
// array(2) {
//   [0]=>
//   enum(Suit::Clubs)
//   [1]=>
//   enum(Suit::Spades)
// }
```
