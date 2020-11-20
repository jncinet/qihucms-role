<?php

namespace Qihucms\Role\Controllers\Admin;

use Qihucms\Currency\Models\CurrencyType;
use Qihucms\Role\Models\Permission;
use Qihucms\Role\Models\Role;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RoleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员角色管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Role());

        $grid->filter(function ($filter) {
            $filter->like('name', __('role::role.name'));
            $filter->like('slug', __('role::role.slug'));
        });

        $grid->column('id', __('role::role.id'));
        $grid->column('name', __('role::role.name'));
        $grid->column('slug', __('role::role.slug'));
        $grid->column('times', __('role::role.times'));
        $grid->column('unit', __('role::role.unit.label'))
            ->using(__('role::role.unit.value'));
        $grid->column('is_qualification_pa', __('role::role.is_qualification_pa'))
            ->using(['否', '是']);
        $grid->column('is_qualification_co', __('role::role.is_qualification_co'))
            ->using(['否', '是']);
        $grid->column('price', __('role::role.price'));
        $grid->column('currency_type.name', __('role::role.currency_type_id'));
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
        $show = new Show(Role::findOrFail($id));

        $show->field('id', __('role::role.id'));
        $show->field('name', __('role::role.name'));
        $show->field('slug', __('role::role.slug'));
        $show->field('desc', __('role::role.desc'));
        $show->field('times', __('role::role.times'));
        $show->field('unit', __('role::role.unit.label'))
            ->using(__('role::role.unit.value'));
        $show->field('is_qualification_pa', __('role::role.is_qualification_pa'))
            ->using(['否', '是']);
        $show->field('is_qualification_co', __('role::role.is_qualification_co'))
            ->using(['否', '是']);
        $show->field('price', __('role::role.price'));
        $show->field('currency_type_id', __('role::role.currency_type_id'))->as(function () {
            return $this->currency_type ? $this->currency_type->name : '未设置';
        });
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
        $form = new Form(new Role());

        $form->text('name', __('role::role.name'));
        $form->text('slug', __('role::role.slug'));
        $form->textarea('desc', __('role::role.desc'));
        $form->number('times', __('role::role.times'))
            ->default(0)->help('0时为永久');
        $form->radio('unit', __('role::role.unit.label'))
            ->options(__('role::role.unit.value'))->default('days');
        $form->radio('is_qualification_pa', __('role::role.is_qualification_pa'))
            ->options(['否', '是']);
        $form->radio('is_qualification_co', __('role::role.is_qualification_co'))
            ->options(['否', '是']);
        $form->currency('price', __('role::role.price'))->default(0.00)
            ->symbol('￥')
            ->help('开通角色价格');
        $form->select('currency_type_id', __('role::role.currency_type_id'))
            ->options(CurrencyType::all()->pluck('name', 'id'));
        $form->listbox('permissions', '选择权限')
            ->options(Permission::all()->pluck('name', 'id'));
        $form->display('created_at', __('admin.created_at'));
        $form->display('updated_at', __('admin.updated_at'));

        $form->saving(function (Form $form) {
            $form->currency_type_id = $form->currency_type_id ?: 0;
        });

        return $form;
    }
}
