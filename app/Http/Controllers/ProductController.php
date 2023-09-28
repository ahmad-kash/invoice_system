<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateProductRequest;
use App\Models\Product;
use App\Models\Section;
use App\Notifications\Database\Product\ProductCreated;
use App\Notifications\Database\Product\ProductDeleted;
use App\Notifications\Database\Product\ProductUpdated;
use App\Services\Notification\AdminNotifyService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct(private AdminNotifyService $adminNotifyService)
    {
        $this->authorizeResource(Product::class, 'product');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product.index', ['products' => Product::with('section')->paginate(5)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create', ['sections' => Section::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateProductRequest $request)
    {
        $product = Product::create($request->validated());

        $this->adminNotifyService->notifyAdmins(new ProductCreated($product, auth()->user()));

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('product.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('product.edit', ['product' => $product, 'sections' => Section::all()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        $this->adminNotifyService->notifyAdmins(new ProductUpdated($product, auth()->user()));


        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        $this->adminNotifyService->notifyAdmins(new ProductDeleted($product, auth()->user()));


        return redirect()->route('products.index');
    }

    public function getSectionProducts(Section $section)
    {
        return $section->products()->select('id', 'name')->get();
    }
}
