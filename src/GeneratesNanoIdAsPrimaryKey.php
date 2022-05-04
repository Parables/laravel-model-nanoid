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
trait GeneratesNanoIdAsPrimaryKey
{
    use GeneratesNanoId;

    /**
     * Boot the trait, adding a creating observer.
     *
     * Create a new NanoId if the model's attribute has not been set as the primary key.
     *
     * This trait explicitly disables auto-incrementing on your Eloquent models
     *
     * @return void
     */

    public static function bootGeneratesNanoIdAsPrimaryKey(): void
    {

        static::creating(
            function ($model) {
                $primaryKeyColumn = $model->nanoIdColumn();
                $model->$primaryKeyColumn = NanoId::nanoId();
                $model->keyType = 'string';
                $model->incrementing = false;
                $model->{$model->getKeyName()} = $model->{$model->getKeyName()} ?: (string) NanoId::nanoId();
            }
        );
    }


    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    /**
     * The name of the column that should be used for the NanoID.
     *
     * @return string
     */
    public function nanoIdColumn(): string
    {
        return 'id';
    }
}
