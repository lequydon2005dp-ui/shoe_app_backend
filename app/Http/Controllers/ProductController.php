<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->orderBy('created_at', 'desc')
            ->with('category')
            ->paginate(10);
        return view('product.index', compact('products'));
    }
    public function create()
    {
        $categorys = Category::query()->get();
        return view('product.create', compact('categorys'));
    }
    public function store(StoreProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->content = $request->content;
        $product->price = $request->price;
        $product->price_discount = $request->price_discount;
        $product->category_id = $request->category_id;
        //upload file
        $file = $request->file('image_url');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('images/products', $filename, 'public'); // returns images/xxxxx.jpg
        $product->image_url = $path;
        //end upload file
        if ($product->save()) {
            return redirect()->route('product.index')->with('success', 'Thêm thành công');;
        }
    }
    public function edit($id)
    {
        $product = Product::find($id);
        $categorys = Category::query()->get();
        if ($product == null) {
            return redirect()->route('user.index')->with('error', 'Không tìm thấy thông tin!');
        }
        return view('product.edit', compact('product', 'categorys'));
    }
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);
        if ($product == null) {
            return redirect()->route('user.index')->with('error', 'Không tìm thấy thông tin!');
        }
        $product->name = $request->name;
        $product->content = $request->content;
        $product->price = $request->price;
        $product->price_discount = $request->price_discount;
        $product->category_id = $request->category_id;
        //upload file
        if ($request->hasFile('image_url')) {
            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete('images/products/' . $product->image_url);
            }
            $file = $request->file('image_url');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/products', $filename, 'public'); // returns images/xxxxx.jpg
            $product->image_url = $path;
        }
        //end upload file
        if ($product->save()) {
            return redirect()->route('product.index')->with('success', 'Cập nhật thành công');;
        }
    }
    public function destroy($id)
    {
        $product = Product::find($id);
        $product_image = $product->image_url;
        if ($product == null) {
            return redirect()->route('user.index')->with('error', 'Không tìm thấy thông tin!');
        }
        if ($product->delete()) {
            if ($product_image && Storage::disk('public')->exists($product_image)) {
                Storage::disk('public')->delete('images/products/' . $product_image);
            }
            return redirect()->route('product.index')->with('success', 'Xóa thành công');;
        }
    }
}
