<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\Controller;
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

    public function __construct()
    {
        $this->groupService = GroupService::make();
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
        return Inertia::render('dashboard/admin/groups/Index', array(
            'exportables' => $exportables,
        ));
    }

    public function show($group_id)
    {
        $group = $this->groupService->view($group_id, [...$this->relations, 'users']);
        return Inertia::render('dashboard/admin/groups/Show', array(
            'group' => $group,
        ));
    }

    public function create()
    {
        return Inertia::render('dashboard/admin/groups/Create');
    }

    public function store(StoreUpdateGroupRequest $request)
    {
        /** @var Group|null $item */
        $group = $this->groupService->store($request->validated(), $this->relations);
        if ($group) {
            return redirect()->route('v1.web.admin.groups.index')->with('success', __('site.stored_successfully'));
        }
        return redirect()->back()->with('error', __('site.something_went_wrong'));
    }

    public function edit($group_id)
    {
        $group = $this->groupService->view($group_id, $this->relations);

        if (!$group) {
            abort(404);
        }
        return Inertia::render('dashboard/admin/groups/Edit', array(
            'group' => $group,
        ));
    }

    public function update(StoreUpdateGroupRequest $request, $group_id)
    {
        /** @var Group|null $item */
        $group = $this->groupService->update($request->validated(), $group_id, $this->relations);
        if ($group) {
            return redirect()->route('v1.web.admin.groups.index')->with('success', __('site.update_successfully'));
        } else return redirect()->back()->with('error', __('site.there_is_no_data'));
    }

    public function destroy($group_id)
    {
        $result = $this->groupService->delete($group_id);

        if ($result) {
            return response()->json(array('success' => __('site.delete_successfully')));
        }

        return response()->json(array('error' => __('site.there_is_no_data')), 404);
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
}
