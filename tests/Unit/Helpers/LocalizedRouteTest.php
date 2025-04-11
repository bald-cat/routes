<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;
use LaravelLang\Config\Enums\Name;

test('route groups', function () {
    $name     = Config::shared()->routes->namePrefix;
    $locale   = LocaleValue::LocaleMain;
    $fallback = LocaleValue::LocaleAliasParent;

    expect(localizedRoute($name . 'via.group.facade', ['foo' => 'bar']))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(localizedRoute($name . 'via.group.macro', ['foo' => 'bar', 'locale' => $fallback]))
        ->toEndWith("localhost/$fallback/group/macro/bar");

    expect(localizedRoute('via.group.facade', ['foo' => 'bar']))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(localizedRoute('via.group.macro', ['foo' => 'bar', 'locale' => $fallback]))
        ->toEndWith("localhost/$fallback/group/macro/bar");
});

test('routes without groups', function () {
    expect(localizedRoute('via.model.default', ['foo' => 'bar']))
        ->toEndWith('localhost/model/default/bar');
});

test('routes hide fallback', function () {
    config()->set(Name::Shared() . '.routes.hide_default', true);
    config()->set('app.locale', \LaravelLang\LocaleList\Locale::Maori->value);
    config()->set('app.fallback_locale', \LaravelLang\LocaleList\Locale::Maori->value);

    expect(localizedRoute('via.group.macro', ['foo' => 'bar']))
        ->toEndWith("localhost/group/macro/bar");
});