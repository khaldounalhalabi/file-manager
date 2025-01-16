<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\StoreUpdateUserRequest;
use App\Models\User;
use App\Services\v1\User\UserService;
use App\Traits\RestTrait;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    use RestTrait;

    private UserService $userService;

    private array $relations = ['roles'];

    public function __construct()
    {
        $this->userService = UserService::make();
    }

    public function data()
    {
        $items = $this->userService->indexWithPagination($this->relations);
        if ($items) {
            return $this->apiResponse($items['data'], ApiController::STATUS_OK, __('site.get_successfully'), $items['pagination_data']);
        }
        return $this->noData();
    }

    public function getUsersByGroup($groupId)
    {
        $items = $this->userService->getByGroup($groupId, $this->relations);
        if ($items) {
            return $this->apiResponse($items['data'], ApiController::STATUS_OK, __('site.get_successfully'), $items['pagination_data']);
        }
        return $this->noData();
    }

    public function getCustomers()
    {
        $items = $this->userService->getCustomers($this->relations);
        if ($items) {
            return $this->apiResponse($items['data'], ApiController::STATUS_OK, __('site.get_successfully'), $items['pagination_data']);
        }
        return $this->noData();
    }

    public function index()
    {
        $exportables = User::getModel()->exportable();
        return Inertia::render('dashboard/admin/users/Index', [
            "exportables" => $exportables
        ]);
    }

    public function show($userId)
    {
        $user = $this->userService->view($userId, $this->relations);
        return Inertia::render('dashboard/admin/users/Show', [
            'user' => $user,
        ]);
    }

    public function create()
    {
        return Inertia::render('dashboard/admin/users/Create');
    }

    /**
     * @throws Exception
     */
    public function store(StoreUpdateUserRequest $request)
    {
        /** @var User|null $item */
        $user = $this->userService->store($request->validated(), $this->relations);
        if ($user) {
            return redirect()->route('v1.web.admin.users.index')->with('success', __('site.stored_successfully'));
        }
        return redirect()->back()->with('error', __('site.something_went_wrong'));
    }

    public function edit($userId)
    {
        $user = $this->userService->view($userId, $this->relations);

        if (!$user) {
            abort(ApiController::STATUS_NOT_FOUND);
        }
        return Inertia::render('dashboard/admin/users/Edit', [
            'user' => $user
        ]);
    }

    public function update(StoreUpdateUserRequest $request, $userId)
    {
        /** @var User|null $item */
        $user = $this->userService->update($request->validated(), $userId, $this->relations);
        if ($user) {
            return redirect()->route('v1.web.admin.users.index')->with('success', __('site.update_successfully'));
        } else return redirect()->back()->with('error', __('site.there_is_no_data'));
    }

    public function destroy($userId)
    {
        $result = $this->userService->delete($userId);

        if ($result) {
            return response()->json(['success' => __("site.delete_successfully")], 200);
        }

        return response()->json(['error' => __('site.there_is_no_data')], ApiController::STATUS_NOT_FOUND);
    }

    public function export(Request $request)
    {
        $ids = $request->ids ?? [];

        try {
            $result = $this->userService->export($ids);
            session()->flash('success', __('site.success'));
            return $result;
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('site.something_went_wrong'));
        }
    }

    public function acceptGroupInvitation(Request $request)
    {
        $result = $this->userService->acceptInvitation($request->invitation_token ?? "");
        if ($result) {
            return redirect()->route('v1.web.customer.user.groups')->with('success', __('site.accepted_successfully'));
        } else {
            abort(403);
        }
    }

    public function storeFcmToken(Request $request)
    {
        try {
            $token = $request->fcm_token;

            $user = auth()->user();

            $user->fcm_token = $token;

            $user->save();

            return response()->json([
                'message' => 'Token Stored Successfully',
            ]);
        } catch (Exception) {
            return response()->json([
                'message' => "There Is Been An Error Registering FCM Token",
            ], 500);
        }
    }

    public function getFcmToken()
    {
        return $this->apiResponse([
            'fcm_token' => auth()->user()?->fcm_token,
        ], 200, __('site.success'));
    }
}
