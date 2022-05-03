# Laravel Model UUIDs

[![Build Status](https://github.com/michaeldyrynda/laravel-model-nanoid/workflows/run-tests/badge.svg)](https://github.com/michaeldyrynda/laravel-model-nanoid/actions?query=workflow%3Arun-tests)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-nanoid/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-nanoid/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-nanoid/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-nanoid/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/dyrynda/laravel-model-nanoid/v/stable)](https://packagist.org/packages/dyrynda/laravel-model-nanoid)
[![Total Downloads](https://poser.pugx.org/dyrynda/laravel-model-nanoid/downloads)](https://packagist.org/packages/dyrynda/laravel-model-nanoid)
[![License](https://poser.pugx.org/dyrynda/laravel-model-nanoid/license)](https://packagist.org/packages/dyrynda/laravel-model-nanoid)
[![Dependency Status](https://www.versioneye.com/php/dyrynda:laravel-model-nanoid/dev-master/badge?style=flat-square)](https://www.versioneye.com/php/dyrynda:laravel-model-nanoid/dev-master)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/michaeldyrynda/laravel-model-nanoid)

## Introduction

Huge thanks to the original authors of Micheal Dyrynda, whose work inspired me to create this package based on [laravel-model-nanoid](https://github.com/michaeldyrynda/laravel-model-nanoid) but uses NanoId instead of NanoID.

## Why NanoID?

![npm trends](nmp_trends.png)

<img src="https://ai.github.io/nanoid/logo.svg" align="right"
alt="Nano ID logo by Anton Lovchikov" width="180" height="94">

A tiny, secure, URL-friendly, unique string ID generator for PHP.

This package is PHP implementation of [ai's](https://github.com/ai) [nanoid](https://github.com/ai/nanoid).
Read its documentation for more information.

-   **Fast.** It is faster than NanoID.
-   **Safe.** It uses cryptographically strong random APIs. Can be used in clusters.
-   **Compact.** It uses a larger alphabet than NanoID (`A-Za-z0-9_-`). So ID size was reduced from 36 to 21 symbols.
-   **Customizable.** Size, alphabet and Random Bytes Generator may be overridden.

> **Note**: this package explicitly does not disable auto-incrementing on your Eloquent models. In terms of database indexing, it is generally more efficient to use auto-incrementing integers for your internal querying. Indexing your `nanoid` column will make lookups against that column fast, without impacting queries between related models.

## Code Samples

In order to use this package, you simply need to import and use the trait within your Eloquent models.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Parables\NanoId\GeneratesNanoId;

class Post extends Model
{
    use GeneratesNanoId;
}
```

It is assumed that you already have a field named `nanoid` in your database, which is used to store the generated value. If you wish to use a custom column name, for example if you want your primary `id` column to be a `NanoID`, you can define a `nanoIdColumn` method in your model.

```php
class Post extends Model
{
    public function nanoIdColumn(): string
    {
        return 'custom_column';
    }
}
```

You can have multiple NanoID columns in each table by specifying an array in the `nanoIdColumns` method. When querying using the `whereNanoId` scope, the default column - specified by `nanoIdColumn` will be used.

```php
class Post extends Model
{
    public function nanoIdColumns(): array
    {
        return ['nanoid', 'custom_column'];
    }
}
```

The `nanoIdColumns` must return an array. You can customize the generated `NanoId` for each column by using an associative array where the key is the column name and the value is an array with an optional `size` and `alphabet` keys.

The following arrays are supported.

```php
  public function nanoIdColumns(): array
    {
        // array of column names: will use the default generator
        return ['nanoid', 'custom_column'];

        // an array where each element is an array with a required 'key' property.
        // no id will be generated if key is null. 'size' and 'alphabet' are optional
        return [
            ['key'=>'nanoid'], // use the NanoId::SIZE_DEFAULT = 21; and NanoId::ALPHABET_DEFAULT
            ['key'=>'column_one', 'size' => 6, 'alphabets'=> NanoId::ALPHABET_DEFAULT], // '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_'
            ['key'=>'column_two', 'size' => 10, 'alphabets'=> NanoId::ALPHABET_NUMBERS], // '0123456789'
            ['key'=>'column_three', 'size' => 8, 'alphabets'=> NanoId::ALPHABET_NUMBERS_READABLE], // '346789'
            ['key'=>'column_three', 'size' => 8, 'alphabets'=> NanoId::ALPHABET_LOWERCASE], // 'abcdefghijklmnopqrstuvwxyz'
            ['key'=>'column_three', 'size' => 8, 'alphabets'=> NanoId::ALPHABET_LOWERCASE_READABLE], // 'abcdefghijkmnpqrtwxyz'
            ['key'=>'column_three', 'size' => 8, 'alphabets'=> NanoId::ALPHABET_UPPERCASE], // 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            ['key'=>'column_three', 'size' => 8, 'alphabets'=> NanoId::ALPHABET_UPPERCASE_READABLE], // 'ABCDEFGHIJKMNPQRTWXYZ'
            ['key'=>'column_three', 'size' => 8, 'alphabets'=> NanoId::ALPHABET_ALPHA_NUMERIC], // '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
            // Numbers and English alphabet without unreadable letters: 1, l, I, 0, O, o, u, v, 5, S, s, 2, Z
            ['key'=>'column_three', 'size' => 8, 'alphabets'=> NanoId::ALPHABET_ALPHA_NUMERIC_READABLE], // '346789abcdefghijkmnpqrtwxyzABCDEFGHJKLMNPQRTUVWXY'
            // Same as ALPHABET_ALPHA_NUMERIC_READABLE but with removed vowels and following letters: 3, 4, x, X, V.
            ['key'=>'column_three', 'size' => 8, 'alphabets'=> NanoId::ALPHABET_ALPHA_NUMERIC_READABLE_SAFE], // '6789bcdfghjkmnpqrtwzBCDFGHJKLMNPQRTW'
            ['key'=>'column_three', 'size' => 8, 'alphabets'=> NanoId::ALPHABET_UUID], // '0123456789abcdef'
        ];
    }
```

Whilst not recommended, if you _do_ choose to use a NanoID as your primary model key (`id`), be sure to configure your model for this setup correctly. Not updating these properties will lead to Laravel attempting to convert your `id` column to an integer, which will be cast to `0`.

```php
<?php

namespace App;

use Parables\NanoId\GeneratesNanoId;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use GeneratesNanoId;

    public $incrementing = false;

    protected $keyType = 'string';
}
```

This trait also provides a query scope which will allow you to easily find your records based on their NanoID, and respects any custom field name you choose.

```php
// Find a specific post with the default (nanoid) column name
$post = Post::whereNanoId($nanoid)->first();

// Find multiple posts with the default (nanoid) column name
$post = Post::whereNanoId([$first, $second])->get();

// Find a specific post with a custom column name
$post = Post::whereNanoId($nanoid, 'custom_column')->first();

// Find multiple posts with a custom column name
$post = Post::whereNanoId([$first, $second], 'custom_column')->get();
```

## Route model binding

From 6.5.0, should you wish to leverage implicit route model binding on your `nanoid` field, you may use the `BindsOnUuid` trait, which will use the configured `nanoIdColumn` by default.

```php
<?php

namespace App;

use Parables\NanoId\BindsOnUuid;
use Parables\NanoId\GeneratesNanoId;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use BindsOnUuid, GeneratesUuid;
}
```

Should you require additional control over the binding, or are using < 6.5.0 of this package, you may override the `getRouteKeyName` method directly.

```php
public function getRouteKeyName(): string
{
    return 'nanoid';
}
```

_If you are using the [laravel-efficient-nanoid](https://github.com/michaeldyrynda/laravel-efficient-nanoid) package, implicit route model binding won't work out of the box._

Laravel will execute the query using the string representation of the `nanoid` field when querying against the binary data stored in the database. In this instance, you will need to explicitly bind the parameter using the included scope in your `RouteServiceProvider`:

```php
// app/Providers/RouteServiceProvider.php

public function boot()
{
    Route::bind('post', function ($post) {
        return \App\Post::whereNanoId($post)->first();
    });
}
```

## Installation

This package is installed via [Composer](https://getcomposer.org/). To install, run the following command.

```bash
composer require dyrynda/laravel-model-nanoid
```

## Support

If you are having general issues with this package, feel free to contact me on [Twitter](https://twitter.com/michaeldyrynda).

If you believe you have found an issue, please report it using the [GitHub issue tracker](https://github.com/michaeldyrynda/laravel-model-nanoid/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts. Thanks!

## Treeware

You're free to use this package, but if it makes it to your production environment you are required to buy the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to plant trees. If you support this package and contribute to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees [here](https://plant.treeware.earth/michaeldyrynda/laravel-model-nanoid)

Read more about Treeware at [treeware.earth](https://treeware.earth)

## Tools

-   [ID size calculator](https://github.com/CyberAP/nanoid-dictionary) shows collision probability when adjusting the ID alphabet or size.

## Credits

-   Andrey Sitnik [ai](https://github.com/ai) for [Nano ID](https://github.com/ai/nanoid).

-   Stanislav Lashmanov [CyberAP](https://github.com/CyberAP) for [Predefined character sets to use with Nano ID](https://github.com/CyberAP/nanoid-dictionary).
