<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Group\SendInvitationRequest;
use App\Http\Requests\v1\Group\StoreUpdateGroupRequest;
use App\Models\Group;
use App\Services\v1\Group\GroupService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 *
 */
class GroupController extends Controller
{
    private GroupService $groupService;

    private array $relations = array('users', 'owner');

    private ?string $role;

    public function __construct()
    {
        $this->groupService = GroupService::make();
        $this->role = auth()->user()?->roles()?->first()?->name;
    }

    public function data()
    {
        $items = $this->groupService->indexWithPagination($this->relations);
        if ($items) {
            return response()->json(array(
                'data' => $items['data'],
                'pagination_data' => $items['pagination_data'],
            ));
        }

        return response()->json(array(
            'data' => array(),
            'pagination_data' => null,
        ));
    }

    public function index()
    {
        $exportables = Group::getModel()->exportable();
        return Inertia::render("dashboard/{$this->role}/groups/Index", array(
            'exportables' => $exportables,
        ));
    }

    public function show($group_id)
    {
        $group = $this->groupService->view($group_id, [...$this->relations, 'users']);
        return Inertia::render("dashboard/{$this->role}/groups/Show", array(
            'group' => $group,
        ));
    }

    public function create()
    {
        return Inertia::render("dashboard/{$this->role}/groups/Create");
    }

    public function store(StoreUpdateGroupRequest $request)
    {
        /** @var Group|null $item */
        $group = $this->groupService->store($request->validated(), $this->relations);
        if ($group) {
            return redirect()->route("v1.web.{$this->role}.groups.index")->with('success', __('site.stored_successfully'));
        }
        return redirect()->back()->with('error', __('site.something_went_wrong'));
    }

    public function edit($group_id)
    {
        $group = $this->groupService->view($group_id, $this->relations);

        if (!$group) {
            abort(404);
        }
        return Inertia::render("dashboard/{$this->role}/groups/Edit", [
            'group' => $group,
        ]);
    }

    public function update(StoreUpdateGroupRequest $request, $group_id)
    {
        /** @var Group|null $item */
        $group = $this->groupService->update($request->validated(), $group_id, $this->relations);
        if ($group) {
            return redirect()->route("v1.web.{$this->role}.groups.index")->with('success', __('site.update_successfully'));
        } else return redirect()->back()->with('error', __('site.there_is_no_data'));
    }

    public function destroy($group_id)
    {
        $result = $this->groupService->delete($group_id);

        if ($result) {
            return response()->json(array('success' => __('site.delete_successfully')));
        }

        return response()->json(array('error' => __('site.there_is_no_data')), ApiController::STATUS_NOT_FOUND);
    }

    public function export(Request $request)
    {
        $ids = $request->ids ?? array();

        try {
            $result = $this->groupService->export($ids);
            session()->flash('success', __('site.success'));
            return $result;
        } catch (Exception) {
            return redirect()->back()->with('error', __('site.something_went_wrong'));
        }
    }

    public function userGroups()
    {
        $groups = $this->groupService->getUserGroups($this->relations);
        return Inertia::render('auth/customer/GroupSelector', [
            'groups' => $groups,
        ]);
    }

    public function selectGroup($groupId)
    {
        $this->groupService->selectGroup($groupId);
        return redirect()->route('v1.web.customer.index');
    }

    public function changeUserGroup($groupId)
    {
        $group = $this->groupService->changeUserGroup($groupId);

        if ($group) {
            return redirect()->back()->with('success', __('site.group_changed_successfully'));
        } else {

            return redirect()->back()->with('error', __('site.something_went_wrong'));
        }
    }

    public function invite(SendInvitationRequest $request)
    {
        $result = $this->groupService->invite($request->validated());

        if ($result) {
            return redirect()->back()->with('success', __('site.send_invitation_success'));
        }
        return redirect()->back()->with('error', __('site.something_went_wrong'));
    }
}
