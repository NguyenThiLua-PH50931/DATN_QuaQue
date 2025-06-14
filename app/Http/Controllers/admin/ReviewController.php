<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ReviewFilter;
use App\Http\Controllers\Controller;
use App\Models\admin\Product;
use App\Models\admin\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 
public function index(Request $request, ReviewFilter $filter)
{
    $products = Product::all();
    $users = \App\Models\User::where('role', 'member')->get();

    $reviewsQuery = Review::with(['user', 'product'])
        ->whereHas('user', function ($query) {
            $query->where('role', 'member');
        });


    if (!empty(array_filter($request->all()))) { 
        $reviewsQuery->filter($filter);
    }

    $reviews = $reviewsQuery->latest()
        ->paginate($request->query('per_page', 10));

    return view('backend.product-review.index', compact('reviews', 'products', 'users'));
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
