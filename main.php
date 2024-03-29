<?php

require_once __DIR__ . '/vendor/autoload.php';

use ExEnum\Attributes\Extension;
use ExEnum\Traits\HasExtension;

// 軽く試す用
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

dump($enum->isHearts());
dump($enum->isDiamonds());
dump($enum->ifHearts(
	then: fn () => 'hoge',
	else: fn () => 'fuga',
));
dump($enum->ifDiamonds(
	then: fn () => 'hoge',
	else: fn () => 'fuga',
));

dump(Suit::names());
dump(Suit::values());

dump($enum->label());
dump(Suit::labels());

dump(Suit::casesOnly(['piyo', 'hoge']));
dump(Suit::casesExcept(['hoge', 'piyo']));