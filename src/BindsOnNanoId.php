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
    abstract public function nanoIdColumn(): string;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return $this->nanoIdColumn();
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
}
