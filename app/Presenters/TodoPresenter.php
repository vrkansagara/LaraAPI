<?php

namespace App\Presenters;

use App\Transformers\TodoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TodoPresenter.
 *
 * @package namespace App\Presenters;
 */
class TodoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TodoTransformer();
    }
}
