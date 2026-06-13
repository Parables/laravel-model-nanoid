<?php

namespace Parables\NanoId;

use Illuminate\Database\Eloquent\Model;

/**
 * NanoID route binding trait.
 *
 * Alters getRouteKeyName() to the return nanoIdColumn()
 *
 * @author    Parables Boltnoel <parables@github.com>
 * @copyright 2017 Parables Boltnoel
 * @license   MIT <https://github.com/parables>
 */
trait BindsOnNanoId
{
    abstract public static function nanoIdColumn(): string;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return static::nanoIdColumn();
    }

    /**
     * Route bind desired nanoId field
     * Default 'nanoId' column name has been set.
     *
     * @param  string  $value
     * @param  null|string  $field
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function resolveRouteBinding($value, $field = null): Model
    {
        return static::whereNanoId($value, $field)->firstOrFail();
    }
}
