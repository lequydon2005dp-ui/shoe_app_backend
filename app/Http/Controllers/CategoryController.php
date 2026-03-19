<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categorys = Category::query()->orderBy('created_at', 'desc')->paginate(10);
        return view('category.index', compact('categorys'));
    }
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        //upload file
        $file = $request->file('image_url');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('images/categorys', $filename, 'public');
        $category->image_url = $path;
        //end upload file
        if ($category->save()) {
            return redirect()->route('category.index')->with('success', 'Thêm thành công');
        }
    }
    public function edit($id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('category.index')->with('error', 'Không tìm thấy thông tin!');
        }
        return view('category.edit', compact('category'));
    }
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('category.index')->with('error', 'Không tìm thấy thông tin!');
        }
        $category->name = $request->name;
        //upload file
        if ($request->hasFile('image_url')) {
            if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
                Storage::disk('public')->delete('images/categorys/' . $category->image_url);
            }
            $file = $request->file('image_url');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/categorys', $filename, 'public'); // returns images/xxxxx.jpg
            $category->image_url = $path;
        }
        //end upload file
        if ($category->save()) {
            return redirect()->route('category.index')->with('success', 'Cập nhật thành công');;
        }
    }
    public function destroy($id)
    {
        $category = Category::find($id);
        $category_image = $category->image_url;
        if ($category == null) {
            return redirect()->route('category.index')->with('error', 'Không tìm thấy thông tin!');
        }
        if ($category->delete()) {
            if ($category_image && Storage::disk('public')->exists($category_image)) {
                Storage::disk('public')->delete('images/categorys/' . $category_image);
            }
            return redirect()->route('category.index')->with('success', 'Xóa thành công');;
        }
    }
}
