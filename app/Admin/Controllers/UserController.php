<?php

namespace App\Admin\Controllers;

use App\User;
use App\Provider;
use App\Client;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Hash;


class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('first_name', __('First name'))->editable();
        $grid->column('last_name', __('Last name'))->editable();
        $grid->column('email', __('Email'))->editable();
        $grid->column('type', __('Type'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('first_name', __('First name'));
        $show->field('last_name', __('Last name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('type', __('Type'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());
        if($form->isCreating()){
            $form->text('first_name', __('First name'))->creationRules('required');
            $form->text('last_name', __('Last name'))->creationRules('required');
            $form->email('email', __('Email'))->creationRules('required|unique:users');
            $form->password('password', __('Password'))->creationRules('required|min:8');
            $form->radio('type', __('Type'))->options(['Client'=>'Client', 'Provider'=>'Provider'])
                        ->default('Client')->creationRules('required');
            $form->saving(function (Form $form) {
                $form->password = Hash::make($form->password);
            });
            $form->saved(function (Form $form) {
                if($form->type == 'Provider'){
                    Provider::create([
                        "provider_id"=>$form->model()->id
                    ]);
                }
            });
        }
        else{
            $form->text('first_name', __('First name'));
            $form->text('last_name', __('Last name'));
            $form->email('email', __('Email'));
            $form->password('password', __('New Password'));
            $form->saving(function (Form $form) {
                $form->password = Hash::make($form->password);
            });
        }
        return $form;
    }
}
