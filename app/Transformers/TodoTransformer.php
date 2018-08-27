<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Todo;

/**
 * Class TodoTransformer.
 *
 * @package namespace App\Transformers;
 */
class TodoTransformer extends TransformerAbstract
{
    /**
     * Transform the Todo entity.
     *
     * @param \App\Entities\Todo $model
     *
     * @return array
     */
    public function transform(Todo $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
