<?php

namespace Qihucms\Role\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Qihucms\Currency\Currency;
use Qihucms\Qualification\Models\QualificationCo;
use Qihucms\Qualification\Models\QualificationPa;
use Qihucms\Role\Models\Role;
use Qihucms\Role\Requests\StoreRequest;
use Qihucms\Role\Resources\Role as RoleResource;
use Qihucms\Role\Resources\RoleCollection;

class RoleController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('store');
    }

    /**
     * 角色列表
     *
     * @param Request $request
     * @return RoleCollection
     */
    public function index(Request $request)
    {
        $condition = [];

        // 名称
        if ($request->has('name')) {
            $condition[] = ['name', 'like', '%' . $request->get('name') . '%'];
        }

        // 标识
        if ($request->has('slug')) {
            $condition[] = ['slug', 'like', '%' . $request->get('slug') . '%'];
        }

        // 支付货币类型
        if ($request->has('currency_type_id')) {
            $condition[] = ['currency_type_id', '=', $request->get('currency_type_id')];
        }

        // 时长
        if ($request->has('times')) {
            $condition[] = ['times', '=', $request->get('times')];
        }

        // 时长单位
        if ($request->has('unit')) {
            $condition[] = ['unit', '=', $request->get('unit')];
        }

        // 个人认证
        if ($request->has('is_pa')) {
            $condition[] = ['is_qualification_pa', '=', $request->get('is_pa')];
        }

        // 企业认证
        if ($request->has('is_co')) {
            $condition[] = ['is_qualification_co', '=', $request->get('is_co')];
        }

        if (count($condition) > 0) {
            $items = Role::where($condition)->latest()->get();
        } else {
            $items = Role::latest()->get();
        }

        return new RoleCollection($items);
    }

    /**
     * 角色详细
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|RoleResource
     */
    public function show($id)
    {
        $role = Role::find($id);

        if ($role) {
            return new RoleResource($role);
        }

        return $this->jsonResponse(['数据不存在'], '', 422);
    }

    /**
     * 角色权限开通
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $user = \Auth::user();
        $role_id = $request->input('role_id');

        $role = Role::find($role_id);

        if ($user->isRole($role->slug)) {
            return $this->jsonResponse(['已经开通过了'], '', 422);
        }

        if ($role->is_qualification_pa
            && QualificationPa::where('user_id', $user->id)->where('status', 2)->doesntExist()) {
            return $this->jsonResponse(['请先完成个人认证'], '', 422);
        }

        if ($role->is_qualification_co
            && QualificationCo::where('user_id', $user->id)->where('status', 2)->doesntExist()) {
            return $this->jsonResponse(['请先完成企业认证'], '', 422);
        }

        if ($role->price > 0) {
            // 支付费用
            $result = Currency::expend($user->id, $role->currency_type_id, $role->price,
                'add_user_role', $role_id, '添加会员角色');

            if ($result === 100) {

                if ($role->times > 0) {
                    $expires = Carbon::parse('+' . $role->times . ' ' . $role->unit)->toDateTimeString();
                } else {
                    $expires = null;
                }
//                return $expires;

                $user->roles()->attach($role->id, ['expires' => $expires]);

                return $this->jsonResponse(['status' => 'success']);
            } else {
                return $this->jsonResponse([trans('currency::message.' . $result)], '支付失败', 422);
            }
        } else {

            $user->roles()->attach($role->id, ['expires' => null]);

            return $this->jsonResponse(['status' => 'success']);
        }
    }
}