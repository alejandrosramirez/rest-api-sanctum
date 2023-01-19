<?php

namespace App\Http\Controllers\ApiAdmin\Authorization;

use App\Http\Controllers\Controller;
use App\Models\Authorization\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
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

        $permissions = Permission::where('guard_name', 'web_admin')
            ->search($search)
            ->paginate($size);

        return response()->json($permissions);
    }
}
