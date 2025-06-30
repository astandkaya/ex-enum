<?php

use ExEnum\Attributes\Extension;
use ExEnum\Traits\HasExtension;

enum Suit: string
{
    use HasExtension;

    // none settings case
    // #[Extension(
    //     label: null,
    //     tags: [],
    //     order: 0,
    // )]
    case NoneExtension = 'none_extension';

    #[Extension(
        label: 'ハート',
        tags: ['red'],
        order: 1,
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

it('fromName() で名前から Enum を取得できること', function () {
    expect(Suit::fromName('Hearts'))->toBe(Suit::Hearts);
    expect(Suit::tryFromName('Hearts'))->toBe(Suit::Hearts);
});
it('fromName() は存在しない名前で例外を投げること', function () {
    expect(fn() => Suit::fromName('Unknown'))->toThrow(\ValueError::class);
});

it('tryFromName() は存在しない名前で null を返すこと', function () {
    expect(Suit::tryFromName('Unknown'))->toBeNull();
});

it('names() が全ケースの name を返すこと', function () {
    expect(Suit::names())
        ->toBe(['NoneExtension', 'Hearts', 'Diamonds', 'Clubs', 'Spades']);
});

it('values() が全ケースの value を返すこと', function () {
    expect(Suit::values())
        ->toBe(['none_extension', 'hearts', 'diamonds', 'clubs', 'spades']);
});

it('labels() が拡張定義した label を返すこと', function () {
    expect(Suit::labels())
        ->toBe(['NoneExtension', 'ハート', 'ダイア', 'クラブ', 'スペード']);
});

it('casesOnly() で指定タグのみを含むケースを取得できること', function () {
    $cases = Suit::casesOnly(['not_hearts']);

    expect($cases)->toHaveCount(3)
        ->and($cases)->toContain(Suit::Diamonds, Suit::Clubs, Suit::Spades)
        ->and($cases)->not()->toContain(Suit::NoneExtension, Suit::Hearts);
});
it('casesOnly() で存在しないタグを指定した場合、空の配列を返すこと', function () {
    $cases = Suit::casesOnly(['unknown_tag']);

    expect($cases)->toBeEmpty();
});

it('casesExcept() で指定タグを含まないケースを取得できること', function () {
    $cases = Suit::casesExcept(['red']);

    expect($cases)->toHaveCount(3)
        ->and($cases)->toContain(Suit::NoneExtension, Suit::Clubs, Suit::Spades)
        ->and($cases)->not()->toContain(Suit::Hearts, Suit::Diamonds);
});
it('casesExcept() で存在しないタグを指定した場合、全ケースを返すこと', function () {
    $cases = Suit::casesExcept(['unknown_tag']);

    expect($cases)->toHaveCount(5)
        ->and($cases)->toContain(Suit::NoneExtension, Suit::Hearts, Suit::Diamonds, Suit::Clubs, Suit::Spades);
});

it('label() インスタンスメソッドが正しいラベルを返すこと', function () {
    expect(Suit::Hearts->label())->toBe('ハート');
});

it('isXxx() 系ヘルパーが正しい真偽値を返すこと', function () {
    $hearts = Suit::Hearts;

    expect($hearts->isHearts())->toBeTrue();
    expect($hearts->isDiamonds())->toBeFalse();
});
it('isXxx() 系ヘルパーが存在しないケースで例外を投げること', function () {
    $hearts = Suit::Hearts;

    expect(fn() => $hearts->isUnknown())->toThrow(\ExEnum\Exceptions\CaseNotFoundException::class);
});

it('sortBy() で昇順にソートできること', function () {
    $sorted = Suit::sortBy();

    expect($sorted)->toBe([
        Suit::Spades,
        Suit::NoneExtension,
        Suit::Hearts,
        Suit::Diamonds,
        Suit::Clubs,
    ]);
});
it('sortByAsc() で昇順にソートできること', function () {
    $sorted = Suit::sortByAsc();

    expect($sorted)->toBe([
        Suit::Spades,
        Suit::NoneExtension,
        Suit::Hearts,
        Suit::Diamonds,
        Suit::Clubs,
    ]);
});
it('sortByDesc() で降順にソートできること', function () {
    $sorted = Suit::sortByDesc();

    expect($sorted)->toBe([
        Suit::Clubs,
        Suit::Diamonds,
        Suit::Hearts,
        Suit::NoneExtension,
        Suit::Spades,
    ]);
});

it('hasTag() で指定されたタグを持つか確認できること', function () {
    $hearts = Suit::Hearts;

    expect($hearts->hasTag('red'))->toBeTrue();
});
it('hasTag() で指定されていないタグを持っていないか確認出来ること', function () {
    $hearts = Suit::Hearts;

    expect($hearts->hasTag('not_hearts'))->toBeFalse();
    expect($hearts->hasTag('unknown_tag'))->toBeFalse();
});

it('hasTags() で指定されたタグを持つか確認できること', function () {
    $diamonds = Suit::Diamonds;

    expect($diamonds->hasTags(['red']))->toBeTrue();
    expect($diamonds->hasTags(['red', 'not_hearts']))->toBeTrue();
});
it('hasTags() で指定されていないタグを持っていないか確認出来ること', function () {
    $hearts = Suit::Hearts;

    expect($hearts->hasTags(['not_hearts']))->toBeFalse();
    expect($hearts->hasTags(['red', 'not_hearts']))->toBeFalse();
    expect($hearts->hasTags(['unknown_tag']))->toBeFalse();
});

it('tags() でタグの配列を取得できること', function () {
    $hearts = Suit::Hearts;

    expect($hearts->tags())->toBe(['red']);
});
it('tags() で空のケースのタグを取得すると空配列を返すこと', function () {
    $noneExtension = Suit::NoneExtension;

    expect($noneExtension->tags())->toBe([]);
});

