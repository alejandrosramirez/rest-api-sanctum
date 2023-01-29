<?php

namespace App\Http\Controllers\Api\Authorization;

use App\Http\Controllers\Controller;
use App\Models\Authorization\Permission;
use Illuminate\Http\Request;

/**
 * @group Authorization Endpoints
 *
 * @subGroup Permissions
 *
 * @authenticated
 */
class PermissionController extends Controller
{
    /**
     * Display a paginated permissions.
     *
     * @queryParam size int The number of items by page. Example: 20
     * @queryParam search string The criteria to search. Example: Editar usuarios
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $size = $request->input('size') ?? 20;
        $search = $request->input('search') ?? '';

        $permissions = Permission::where('guard_name', 'web')
            ->search($search)
            ->paginate($size);

        return response()->json($permissions);
    }
}
