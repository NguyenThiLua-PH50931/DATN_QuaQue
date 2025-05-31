<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('status', 1)->get(); // chỉ lấy user đang hoạt động
        return view('backend.users.index', compact('users'));
    }

    // Hiển thị form thêm user
    public function create()
    {
        return view('backend.users.create');
    }

    // Xử lý
    public function store(StoreUserRequest $request)
    {
        // Upload avatar nếu có
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Tạo user
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => $request->role,
            'password' => bcrypt($request->password),
            'avatar'   => $avatarPath,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'Tài khoản đã được tạo thành công!');
    }

    // Hiện ẩn người dùng
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        // Xác định đến từ trang nào, ví dụ: route('admin.user.hidden') hay route('admin.user.index')
        $referer = request()->headers->get('referer');

        // Nếu đến từ trang ẩn thì chuyển về trang danh sách chính
        if (strpos($referer, 'users/hidden') !== false) {
            return redirect()->route('admin.user.index')->with('success', 'Người dùng đã được hiển thị!');
        }

        // Ngược lại thì quay về trang trước
        return back()->with('success', 'Trạng thái người dùng đã được cập nhật!');
    }

    // Các tài khoản ẩn:
    public function hidden()
    {
        $users = User::where('status', 0)->get();
        return view('backend.users.hidden', compact('users'));
    }

    // Xóa tài khoản:
    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Tài khoản không tồn tại.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'Xóa tài khoản thành công.');
    }

    // Hiển thị form sửa
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.users.edit', compact('user'));
    }

    // Xử lý cập nhật
    // Xử lý cập nhật
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'role'  => 'required|in:admin,member',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar'   => 'nullable|image|max:2048',
        ]);

        // Upload avatar nếu có
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Cập nhật các trường
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.user.index')->with('success', 'Cập nhật tài khoản thành công!');
    }
}
