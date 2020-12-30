<?php

namespace Qihucms\Role\Controllers\Admin;

use App\Admin\Controllers\Controller;
use Qihucms\Role\Models\Permission;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PermissionController extends Controller
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员权限管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Permission());

        $grid->filter(function ($filter) {
            $filter->like('name', __('role::role.name'));
            $filter->like('slug', __('role::role.slug'));
        });

        $grid->column('id', __('role::permission.id'));
        $grid->column('name', __('role::permission.name'));
        $grid->column('slug', __('role::permission.slug'));
        $grid->column('amount', __('role::permission.amount'));
        $grid->column('created_at', __('admin.created_at'));
        $grid->column('updated_at', __('admin.updated_at'));

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
        $show = new Show(Permission::findOrFail($id));

        $show->field('id', __('role::permission.id'));
        $show->field('name', __('role::permission.name'));
        $show->field('slug', __('role::permission.slug'));
        $show->field('amount', __('role::permission.amount'));
        $show->field('created_at', __('admin.created_at'));
        $show->field('updated_at', __('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Permission());

        $form->text('name', __('role::permission.name'));
        $form->text('slug', __('role::permission.slug'));
        $form->number('amount', __('role::permission.amount'))
            ->default(0)
            ->help(__('role::permission.amount_help'));

        return $form;
    }
}
