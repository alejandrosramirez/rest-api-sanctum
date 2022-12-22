<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\DiskDriver;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\IsValidEmailPattern;
use App\Traits\HandleUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HandleUpload;

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

        $users = User::with(['roles'])
            ->search($search)
            ->where('id', '!=', Auth::guard('api')->user()->id)
            ->paginate($size);

        return response()->json($users);
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

        $dataObj = [
            'name' => $validated->name,
            'lastname' => $validated->lastname,
            'phone' => $validated->phone,
            'email' => $validated->email,
            'password' => Hash::make($validated->password),
        ];

        if ($request->hasFile('avatar')) {
            $avatar = $this->saveImage($request->file('avatar'), DiskDriver::UPLOADS);
            $dataObj['avatar'] = $avatar['url'];
        }

        $user = User::create($dataObj);

        $user->assignRole($validated->role);

        return response()->json(['user' => $user]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json(['user' => $user->load(['roles'])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $this->handleRequest($request, $user->uuid);

        if ($request->hasFile('avatar')) {
            $avatar = $this->saveImage($request->file('avatar'), DiskDriver::UPLOADS, $user->avatar);
            $user->avatar = $avatar['url'];
        }

        $user->name = $validated->name;
        $user->lastname = $validated->lastname;
        $user->phone = $validated->phone;
        $user->email = $validated->email;
        if ($validated->password) {
            $user->password = Hash::make($validated->password);
        }
        $user->save();

        $user->syncRoles($validated->role);

        return response()->json(['user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        return response()->json(['deleted' => $user->delete()]);
    }

    /**
     * Disable the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function disable(User $user)
    {
        $user->disabled = !$user->disabled;
        $user->save();

        return response()->json(['disabled' => $user->disabled]);
    }

    /**
     * Handler for request validation
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return object
     */
    private function handleRequest(Request $request, string $uuid = 'NULL')
    {
        $validated = (object)[];

        $passwordValidation = $uuid == 'NULL' ? 'required' : 'sometimes';
        $uniqueEmailValidation = $uuid == 'NULL' ? 'unique:users,email,' . $uuid . ',id,deleted_at,NULL' : '';

        $request->validate([
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
            'name' => ['required', 'max:255'],
            'lastname' => ['required', 'max:255'],
            'phone' => ['required', 'max:30'],
            'email' => ['required', 'email', $uniqueEmailValidation, new IsValidEmailPattern()],
            'password' => [$passwordValidation, 'min:8'],
            'role' => ['required'],
        ]);

        $validated->name = $request->input('name');
        $validated->lastname = $request->input('lastname');
        $validated->phone = $request->input('phone');
        $validated->email = $request->input('email');
        $validated->password = $request->input('password') ?? null;
        $validated->role = $request->input('role');

        return $validated;
    }
}
