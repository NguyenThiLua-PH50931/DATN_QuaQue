<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ReviewFilter;
use App\Http\Controllers\Controller;
use App\Models\admin\Product;
use App\Models\admin\Review;
use App\Models\Client\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index(Request $request)
{
    $products = Product::whereHas('reviews')->get();

    $users = \App\Models\User::where('role', 'member')
        ->whereHas('reviews')
        ->get();

    $reviewsQuery = Review::with(['user', 'product']);

    // **Chỉ filter theo user khi có chọn user_id hoặc cần**
    if ($request->filled('user_id')) {
        $reviewsQuery->where('user_id', $request->user_id);
        // Chỉ hiện user là member
        $reviewsQuery->whereHas('user', function ($query) {
            $query->where('role', 'member');
        });
    }
    // Nếu muốn luôn filter theo user role member thì giữ nguyên (nhưng nhớ kiểm tra DB!)

    if ($request->filled('product_id')) {
        $reviewsQuery->where('product_id', $request->product_id);
    }
    if ($request->filled('rating')) {
        $reviewsQuery->where('rating', $request->rating);
    }
    if ($request->filled('after_date')) {
        $reviewsQuery->whereDate('created_at', '>=', $request->after_date);
    }
    if ($request->filled('before_date')) {
        $reviewsQuery->whereDate('created_at', '<=', $request->before_date);
    }
    if ($request->has('is_reply') && $request->is_reply !== '') {
        if ($request->is_reply === '1') {
            $reviewsQuery->whereNotNull('parent_id');
        } elseif ($request->is_reply === '0') {
            $reviewsQuery->whereNull('parent_id');
        }
    }
    if ($request->filled('parent_id')) {
        $reviewsQuery->where('parent_id', $request->parent_id);
    }

    $reviews = $reviewsQuery->latest()->paginate($request->query('per_page', 10))->withQueryString();
    $variantNames = ProductVariant::pluck('name', 'id')->toArray();

    return view('backend.product-review.index', compact('reviews', 'products', 'users', 'variantNames'));
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('reviews.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Đánh giá đã được thêm!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $review = Review::with(['user', 'product'])->findOrFail($id);
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);
        $review->delete();


        return redirect()->route('admin.reviews.index')->with('success', 'Đánh giá đã được xoá.');
    }
}
