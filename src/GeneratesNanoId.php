<?php

namespace Parables\NanoId;

use Illuminate\Support\Arr;
use Snortlin\NanoId\NanoId;
use Illuminate\Database\Eloquent\Builder;

/**
 * NanoID generation trait.
 *
 * Include this trait in any Eloquent model where you wish to automatically set
 * a NanoID field. When saving, if the `nanoid` field has not been set, generate a
 * new NanoID value, which will be set on the model and saved by Eloquent.
 *
 * @author    Parables Boltnoel <parables@github.com>
 * @copyright 2017 Parables Boltnoel
 * @license   MIT <https://github.com/parables>
 *
 * @method static \Illuminate\Database\Eloquent\Builder  whereNanoID(string $nanoid)
 */
trait GeneratesNanoId
{
    /**
     * The name of the column that should be used for the NanoID.
     *
     * @return string
     */
    public function nanoIdColumn(): string
    {
        return 'nanoid';
    }

    /**
     * The names of the columns that should be used for the NanoID.
     *
     * @return array
     */
    public function nanoIdColumns(): array
    {
        return [$this->nanoIdColumn()];
    }

    /**
     * Scope queries to find by NanoID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string|array                          $nanoid
     * @param  string                                $nanoIdColumn
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNanoId($query, $nanoid, $nanoIdColumn = null): Builder
    {
        $nanoIdColumn = !is_null($nanoIdColumn) &&
            in_array($nanoIdColumn, $this->nanoIdColumns())
            ? $nanoIdColumn
            : $this->nanoIdColumns()[0];

        return $query->whereIn(
            $this->qualifyColumn($nanoIdColumn),
            Arr::wrap($nanoid)
        );
    }

    public static function bootGeneratesNanoId(): void
    {
        static::creating(
            function ($model) {
                foreach (self::transformNanoIdColumns($model->nanoIdColumns()) as $column) {
                    $columnName = $column['key'];
                    if (!isset($model->attributes[$columnName])) {
                        $model->{$columnName} = NanoId::nanoId(size: $column['size'], alphabet: $column['alphabets']);
                    }
                }
            }
        );
    }

    private static function transformNanoIdColumns(array $arr)
    {
        $mappedColumns = array_map(
            'self::transformNanoIdColumnsCallback',
            array_keys($arr),
            array_values($arr)
        );
        return array_filter($mappedColumns);
    }

    private static function transformNanoIdColumnsCallback($key, $value)
    {
        return self::getColumnFromNumericKey(key: $key, value: $value);
    }


    private static function getColumnFromNumericKey($key, $value)
    {
        return is_numeric($key)
            ? self::getColumnFromStringValue(value: $value)
            : self::getColumnFromStringKey(key: $key, value: $value);
    }

    private static function getColumnFromStringValue($value)
    {
        return is_string($value) ?
            [
                'key' => $value,
                'size' => NanoId::SIZE_DEFAULT,
                'alphabets' => NanoId::ALPHABET_DEFAULT
            ] : self::getColumnFromArrayValue(value: $value);
    }

    private static function getColumnFromArrayValue($value)
    {
        return (is_array($value) && array_key_exists('key', $value)) ?
            [
                'key' => $value['key'],
                'size' =>  self::getSizeFromArrayValueOrDefaultSize(value: $value),
                'alphabets' => self::getAlphabetsFromArrayValueOrDefaultAlphabets(value: $value)
            ] : null;
    }

    private static function getColumnFromStringKey($key, $value)
    {
        return is_string($key) ?
            [
                'key' => self::getKeyFromArrayValueOrDefaultKey(key: $key, value: $value),
                'size' => self::getSizeFromArrayValueOrDefaultSize(value: $value),
                'alphabets' => self::getAlphabetsFromArrayValueOrDefaultAlphabets(value: $value)
            ] : null;
    }

    private static function getKeyFromArrayValueOrDefaultKey(string $key, array $value)
    {
        return array_key_exists('key', $value) && is_string($value['key']) ?  $value['key'] :  $key;
    }

    private static function getSizeFromArrayValueOrDefaultSize(array $value)
    {
        return $value['size'] && is_int($value['size']) ? $value['size'] : NanoId::SIZE_DEFAULT;
    }

    private static function getAlphabetsFromArrayValueOrDefaultAlphabets(array $value)
    {
        return $value['alphabets'] ?: NanoId::ALPHABET_DEFAULT;
    }
}
