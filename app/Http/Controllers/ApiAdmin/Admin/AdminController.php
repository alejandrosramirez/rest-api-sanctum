<?php

namespace App\Http\Controllers\ApiAdmin\Admin;

use App\Enums\DiskDriver;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Rules\IsValidEmailPattern;
use App\Traits\HandleUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use HandleUpload;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $size = $request->input('size') ?? 20;
        $search = $request->input('search') ?? '';

        $admins = Admin::with(['roles'])
            ->search($search)
            ->where('id', '!=', Auth::guard('api_admin')->user()->id)
            ->paginate($size);

        return response()->json($admins);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $this->handleRequest($request);

        $dataObj = [
            'name' => $validated->name,
            'lastname' => $validated->lastname,
            'email' => $validated->email,
            'password' => Hash::make($validated->password),
            'email_verified_at' => now(),
        ];

        if ($request->hasFile('avatar')) {
            $avatar = $this->saveImage($request->file('avatar'), DiskDriver::UPLOADS);
            $dataObj['avatar'] = $avatar['url'];
        }

        $admin = Admin::create($dataObj);

        $admin->assignRole($validated->role);

        return response()->json(['admin' => $admin]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function show(Admin $admin)
    {
        return response()->json(['admin' => $admin->load(['roles'])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $this->handleRequest($request, $admin->uuid);

        if ($request->hasFile('avatar')) {
            $avatar = $this->saveImage($request->file('avatar'), DiskDriver::UPLOADS, $admin->avatar);
            $admin->avatar = $avatar['url'];
        }

        $admin->name = $validated->name;
        $admin->lastname = $validated->lastname;
        $admin->email = $validated->email;
        if ($validated->password) {
            $admin->password = Hash::make($validated->password);
        }
        $admin->save();

        $admin->syncRoles($validated->role);

        return response()->json(['admin' => $admin]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function destroy(Admin $admin)
    {
        return response()->json(['deleted' => $admin->delete()]);
    }

    /**
     * Disable the specified resource from storage.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function disable(Admin $admin)
    {
        $admin->disabled = !$admin->disabled;
        $admin->save();

        return response()->json(['disabled' => $admin->disabled]);
    }

    /**
     * Handler for request validation.
     *
     * @return object
     */
    private function handleRequest(Request $request, string $uuid = 'NULL')
    {
        $validated = (object) [];

        $requiredPasswordValidation = 'NULL' == $uuid ? 'required' : 'sometimes';
        $uniqueEmailValidation = 'NULL' == $uuid ? 'unique:admins,email,'.$uuid.',id,deleted_at,NULL' : '';

        $request->validate([
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
            'name' => ['required', 'max:255'],
            'lastname' => ['required', 'max:255'],
            'email' => ['required', 'email', $uniqueEmailValidation, new IsValidEmailPattern()],
            'password' => [$requiredPasswordValidation, 'min:8'],
            'role' => ['required'],
        ]);

        $validated->name = $request->input('name');
        $validated->lastname = $request->input('lastname');
        $validated->email = $request->input('email');
        $validated->password = $request->input('password') ?? null;
        $validated->role = $request->input('role');

        return $validated;
    }
}
