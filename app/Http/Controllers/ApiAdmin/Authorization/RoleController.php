<?php

namespace App\Http\Controllers\ApiAdmin\Authorization;

use App\Http\Controllers\Controller;
use App\Models\Authorization\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $size = $request->input('size') ?? 20;
        $search = $request->input('search') ?? '';

        $roles = Role::where('guard_name', 'web_admin')
            ->search($search)
            ->paginate($size);

        return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $this->handleRequest($request);

        $role = Role::create([
            'name' => $validated->name,
            'guard_name' => 'web_admin',
            'description' => $validated->description,
        ]);

        $role->givePermissionTo($validated->permissions);

        return response()->json(['role' => $role]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return response()->json(['role' => $role->load(['permissions'])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $validated = $this->handleRequest($request, $role->uuid);

        $role->name = $validated->name;
        $role->description = $validated->description;

        $role->syncPermissions($validated->permissions); // First sync permissions if exists

        $role->save();

        return response()->json(['role' => $role]);
    }

    /**
     * Remove the specified resource from storage.
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
        ]);

        $validated->name = Str::snake($request->input('description'));
        $validated->description = $request->input('description');
        $validated->permissions = $request->input('permissions');

        return $validated;
    }
}
