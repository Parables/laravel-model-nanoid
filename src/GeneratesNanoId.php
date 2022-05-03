<?php

namespace Parables\NanoId;

use Illuminate\Support\Arr;
use Snortlin\NanoId\NanoId;
use Illuminate\Database\Eloquent\Model;
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
     * Boot the trait, adding a creating observer.
     *
     * Create a new NanoId if the model's attribute has not been set
     *
     * @return void
     */
    public static function bootGeneratesNanoId(): void
    {
        static::creating(
            function ($model) {
                foreach (self::parseColumns($model->nanoIdColumns()) as $column) {
                    $columnName = $column['key'];
                    if (!isset($model->attributes[$columnName])) {
                        $model->{$columnName} = NanoId::nanoId(size: $column['size'], alphabet: $column['alphabets']);
                    }
                }
            }
        );
    }

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

    private static function parseColumns(array $arr)
    {
        function mapColumns($key, $value)
        {
            if (is_numeric($key)) {
                if (is_string($value)) {
                    return [
                        'key' => $value,
                        'size' => NanoId::SIZE_DEFAULT,
                        'alphabets' => NanoId::ALPHABET_DEFAULT
                    ];
                } elseif (is_array($value) && array_key_exists('key', $value)) {
                    return [
                        'key' => $value['key'],
                        'size' => $value['size'] ?: NanoId::SIZE_DEFAULT,
                        'alphabets' => $value['alphabets'] ?: NanoId::ALPHABET_DEFAULT
                    ];
                }
            } elseif (is_string($key)) {
                return [
                    'key' => array_key_exists('key', $value) ?  $value['key'] :  $key,
                    'size' => $value['size'] ?: NanoId::SIZE_DEFAULT,
                    'alphabets' => $value['alphabets'] ?: NanoId::ALPHABET_DEFAULT
                ];
            }
        }
        return array_filter(
            array_map(
                'mapColumns',
                array_keys($arr),
                array_values($arr)
            )
        );
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

    /**
     * Route bind desired nanoid field
     * Default 'nanoid' column name has been set.
     *
     * @param  string  $value
     * @param  null|string  $field
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function resolveRouteBinding($value, $field = null): Model
    {
        return self::whereNanoId($value, $field)->firstOrFail();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return $this->nanoIdColumn();
    }
}
