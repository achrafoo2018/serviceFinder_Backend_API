<?php

namespace App\Admin\Actions\User;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Restore extends RowAction
{
    public $name = 'Restore';

    public function handle (Model $model)
    {
        $model->restore();

        return $this->response()->success('Restore')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure you want to restore this user?');
    }
}
