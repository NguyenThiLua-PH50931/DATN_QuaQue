<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Address;
use App\Models\Client\Order;
use App\Models\Client\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ProfileClientController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Lấy lịch sử các đơn hàng (mới nhất trước), và từng sản phẩm trong đơn (order_items)
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product.categories', 'items.product.variants'])
            ->orderByDesc('created_at')
            ->paginate(5, ['*'], 'orders_page');
        // Lấy wishlist + thông tin sản phẩm (chỉ lấy sản phẩm active)
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with(['product' => function ($q) {
                $q->where('active', 1)->with(['categories', 'variants']);
            }])
            ->paginate(12, ['*'], 'wishlist_page');

        // AJAX trả về đúng partial
        if ($request->ajax()) {
            if ($request->has('orders_page')) {
                return view('frontend.profile-user._order-list', compact('orders'))->render();
            }
            if ($request->has('wishlist_page')) {
                return view('frontend.profile-user._wishlist-list', compact('wishlist'))->render();
            }
        }
        // Tổng số đơn hàng của user
        $totalOrder = Order::where('user_id', $user->id)->count();

        // Tổng số đơn đang chờ xử lý
        $totalPendingOrder = Order::where('user_id', $user->id)
            ->where('status', 'pending')->count();

        // Tổng wishlist
        $totalWishlist = Wishlist::where('user_id', $user->id)->count();

        // Thông tin địa chỉ mặc định (nếu có)
        $defaultAddress = Address::where('user_id', $user->id)
            ->where('is_default', 1)->first();


        return view('frontend.profile-user.profile', compact(
            'user',
            'wishlist',
            'totalOrder',
            'totalPendingOrder',
            'totalWishlist',
            'defaultAddress',
            'orders',

        ));
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'digits:10', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'], // max 2MB
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Họ tên là bắt buộc.',
            'name.string' => 'Họ tên phải là chuỗi ký tự.',
            'name.max' => 'Họ tên không được dài quá 255 ký tự.',

            'phone.digits' => 'Số điện thoại phải đúng 10 chữ số.',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',

            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',

            'avatar.image' => 'Ảnh đại diện phải là file hình ảnh.',
            'avatar.max' => 'Ảnh đại diện không được lớn hơn 2MB.',

            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Cập nhật thông tin
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;

        // Nếu có avatar mới thì lưu file
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu cần
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Nếu có đổi mật khẩu
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        /** @var \App\Models\User $user */
        $user->save();

        return redirect()->route('index')->with('success', 'Cập nhật hồ sơ thành công!');
    }
    public function deleteAccount(Request $request)
    {
        $user = auth()->user();
        $type = $request->type;

        if ($type === 'permanent') {
            $user->forceDelete();
            return response()->json(['success' => true]);
        } elseif ($type === 'temporary') {
            $user->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Chưa chọn hình thức xóa tài khoản!']);
    }
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'avatar.required' => 'Bạn chưa chọn ảnh đại diện!',
            'avatar.image'    => 'Tệp tải lên phải là ảnh!',
            'avatar.mimes'    => 'Ảnh phải có định dạng: jpeg,png,jpg,gif,svg,webp',
            'avatar.max'      => 'Kích thước tối đa 2MB.',
        ]);

        $user = Auth::user();

        // Không xóa ảnh mặc định
        if (
            $user->avatar
            && $user->avatar !== 'avatars/default.png'
            && Storage::disk('public')->exists($user->avatar)
        ) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Lưu avatar mới
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'avatar_url' => asset('storage/' . $user->avatar),
                'message' => 'Đã cập nhật ảnh đại diện thành công!',
            ]);
        }

        return back()->with('success', 'Đã cập nhật ảnh đại diện!');
    }
    public function changePassword(Request $request)
    {
        // Dùng Validator thủ công thay vì $request->validate()
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'string', 'min:6', 'max:50'],
            'new_password' => [
                'required',
                'string',
                'min:6',
                'max:50',
                'different:old_password',
                'confirmed'
            ],
        ], [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ.',
            'old_password.string' => 'Mật khẩu cũ phải là chuỗi.',
            'old_password.min' => 'Mật khẩu cũ phải có ít nhất 6 ký tự.',
            'old_password.max' => 'Mật khẩu cũ không được vượt quá 50 ký tự.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.string' => 'Mật khẩu mới phải là chuỗi.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.max' => 'Mật khẩu mới không được vượt quá 50 ký tự.',
            'new_password.different' => 'Mật khẩu mới phải khác mật khẩu cũ.',
            'new_password.confirmed' => 'Nhập lại mật khẩu mới không khớp.',
        ]);

        if ($validator->fails()) {
            // Trả về lỗi JSON đúng định dạng cho JS
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        if (!\Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'old_password' => ['Mật khẩu cũ không đúng.']
                ]
            ], 422);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['success' => true]);
    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'regex:/^[0-9]{8,15}$/'],
            'gender' => ['nullable', 'in:male,female'],
            'birthday' => ['nullable', 'date_format:d/m/Y'],
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.regex' => 'Số điện thoại không hợp lệ (8-15 số).',
            'gender.in' => 'Giới tính chỉ nhận giá trị male hoặc female.',
            'birthday.date_format' => 'Ngày sinh không hợp lệ (định dạng: dd/mm/yyyy).',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->birthday = $request->birthday ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d') : null;
        $user->save();

        return response()->json([
            'success' => true,
            'user' => [
                'name'     => $user->name,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'gender'   => $user->gender,
                'birthday' => $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : null,
                // Có thể thêm các trường khác nếu muốn cập nhật ngoài giao diện
            ]
        ]);
    }
    // app/Http/Controllers/UserController.php

    public function updateAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'regex:/^[0-9]{8,15}$/'],
            'province' => ['required', 'string'],
            'district' => ['required', 'string'],
            'ward' => ['required', 'string'],
            'address' => ['required', 'string', 'max:255'],
        ], [
            'recipient_name.required' => 'Vui lòng nhập họ và tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'province.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'district.required' => 'Vui lòng chọn quận/huyện.',
            'ward.required' => 'Vui lòng chọn phường/xã.',
            'address.required' => 'Vui lòng nhập địa chỉ cụ thể.',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $address = Address::findOrFail($request->address_id);

        // Cập nhật thông tin địa chỉ
        $address->fill($request->only('recipient_name', 'phone', 'province', 'district', 'ward', 'address'));
        $address->save();

        // Đặt địa chỉ này là mặc định, các địa chỉ khác về 0
        Address::where('user_id', $address->user_id)
            ->where('id', '!=', $address->id)
            ->update(['is_default' => 0]);

        $address->is_default = 1;
        $address->save();

        return response()->json(['success' => true]);
    }
}
