<?php

namespace App\Http\Controllers\Api\Permission;

use App\Http\Controllers\Controller;
use App\Models\Permission\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * Display the specified resource.
     *
     * @param  \App\Models\Permission\Role  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return response()->json(['role' => $role->load(['permissions'])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission\Role  $role
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission\Role  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        return response()->json(['deleted' => $role->delete()]);
    }

    /**
     * Handler for request validation
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return object
     */
    protected function handleRequest(Request $request, string $uuid = 'NULL')
    {
        $validated = (object)[];

        $uniqueNameValidation = $uuid == 'NULL' ? 'unique:roles,description,' . $uuid . ',id' : '';

        $request->validate([
            'description' => ['required', 'string', 'max:255', $uniqueNameValidation],
            'permissions' => ['required', 'array', 'min:1'],
        ]);

        $validated->name = Str::snake($request->input('description'));
        $validated->description = $request->input('description');
        $validated->permissions = $request->input('permissions');

        return $validated;
    }
}
