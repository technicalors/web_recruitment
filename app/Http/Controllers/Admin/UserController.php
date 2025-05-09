<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-users');

        $query = User::query();

        // Lọc theo username 
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%");
            });
        }

        // Lọc theo role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10);

        return view('Admin.pages.users.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('manage-users');

        return view('Admin.pages.users.add_edit');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'password' => 'required|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công');
    }

    public function edit(User $user)
    {
        Gate::authorize('manage-users');

        return view('Admin.pages.users.add_edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('manage-users');

        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
        ]);

        // Không đc sửa role của Admin chính (ID = 1)
        if ($user->id == 1) {
            unset($validated['role']);
        }

        $data = $validated;

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật thành công');
    }

    public function destroy(User $user)
    {
        Gate::authorize('manage-users');

        if ($user->id == 1) {
            return back()->with('error', 'Không thể xóa Admin chính');
        }

        $user->delete();
        return back()->with('success', 'Xóa thành công');
    }
}
