<?php

namespace Parables\NanoId;

use Snortlin\NanoId\NanoId;

/**
 * NanoID generation trait.
 *
 * Include this trait in any Eloquent model where you wish to automatically set
 * a NanoID field. When saving, if the `nanoId` field has not been set, generate a
 * new NanoID value, which will be set on the model and saved by Eloquent.
 *
 * @author    Parables Boltnoel <parables@github.com>
 * @copyright 2017 Parables Boltnoel
 * @license   MIT <https://github.com/parables>
 *
 * @method static \Illuminate\Database\Eloquent\Builder  whereNanoID(string $nanoId)
 */
trait NanoIdAsPrimaryKey
{
    use GeneratesNanoId;
    use BindsOnNanoId;
    
    /**
     * The name of the column that should be used for the NanoID.
     *
     * @return string
     */
    public static function nanoIdColumn(): string
    {
        return 'id';
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
