<?php

namespace App\Http\Controllers\Api\State;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

/**
 * @group State Endpoints
 * @authenticated
 */
class StateController extends Controller
{
    /**
     * Display a paginated states.
     *
     * @queryParam size int The number of elements for listing. Example: 20
     * @queryParam search string The criteria to search in list. Example: Jalisco
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $size = $request->input('size') ?? 20;
        $search = $request->input('search') ?? '';

        $states = State::search($search)->paginate($size);

        return response()->json($states);
    }
}
