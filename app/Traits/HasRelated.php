<?php

namespace App\Traits;

use App\Exceptions\DefaultException;
use Illuminate\Database\Eloquent\Model;

trait HasRelated
{
    /**
     * Setup model event hooks
     *
     * @return void
     */
    public static function bootHasRelated()
    {
        static::deleting(function (Model $model) {
            $relateds = $model->relateds;

            foreach ($relateds as $related) {
                if ($model->{$related}()->count() > 0) {
                    throw new DefaultException(__('Cannot delete record because it has already associated.'));
                }
            }
        });
    }
}
