<?php

namespace App\Http\Controllers\Api\Authorization;

use App\Http\Controllers\Controller;
use App\Models\Authorization\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group Authorization Endpoints
 *
 * @subGroup Roles
 *
 * @authenticated
 */
class RoleController extends Controller
{
    /**
     * Display a paginated roles.
     *
     * @queryParam size int The number of elements for listing. Example: 20
     * @queryParam search string The criteria to search in list. Example: Administrador
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $size = $request->input('size') ?? 20;
        $search = $request->input('search') ?? '';

        $roles = Role::where('guard_name', 'web')
            ->search($search)
            ->paginate($size);

        return response()->json($roles);
    }

    /**
     * Store a new role.
     *
     * @bodyParam description string required The description for this role. Example: Super Admin
     * @bodyParam permissions string[] required The selected permissions names for this role. Example: ['users_read']
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $this->handleRequest($request);

        $role = Role::create([
            'name' => $validated->name,
            'guard_name' => 'web',
            'description' => $validated->description,
        ]);

        $role->givePermissionTo($validated->permissions);

        return response()->json(['role' => $role]);
    }

    /**
     * Display the specified role.
     *
     * @urlParam role_uuid string required The role uuid.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return response()->json(['role' => $role->load(['permissions'])]);
    }

    /**
     * Update the specified role.
     *
     * @urlParam role_uuid string required The role uuid.
     *
     * @bodyParam description string required The description for this role. Example: Super Admin
     * @bodyParam permissions string[] required The selected permission names for this role. Example: ['users_read']
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $validated = $this->handleRequest($request, $role->uuid);

        $role->name = $validated->name;
        $role->description = $validated->description;
        $role->save();

        $role->syncPermissions($validated->permissions);

        return response()->json(['role' => $role]);
    }

    /**
     * Remove the specified role.
     *
     * @urlParam role_uuid string required The role uuid.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        return response()->json(['deleted' => $role->delete()]);
    }

    /**
     * Handler for request validation.
     *
     * @return object
     */
    protected function handleRequest(Request $request, string $uuid = 'NULL')
    {
        $validated = (object) [];

        $uniqueNameValidation = 'NULL' == $uuid ? 'unique:roles,description,'.$uuid.',id' : '';

        $request->validate([
            'description' => ['required', 'string', 'max:255', $uniqueNameValidation],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['required', 'string'],
        ]);

        $validated->name = Str::snake($request->input('description'));
        $validated->description = $request->input('description');
        $validated->permissions = $request->input('permissions');

        return $validated;
    }
}
