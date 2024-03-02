<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

final class UserController extends Controller
{
    public function destroy(string $id)
    {
        return UserResource::collection(User::destroy($id));
    }

    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    public function show(string $id)
    {
        return UserResource::collection(User::find($id));
    }

    public function store(Request $request)
    {
        return new UserResource(User::create($request));
    }

    public function update(Request $request, string $id)
    {
        return UserResource::collection(User::find($id)->update($request));
    }
}
