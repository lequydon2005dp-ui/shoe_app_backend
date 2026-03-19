<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::query()->orderBy('created_at', 'desc')->paginate(10);
        return view('banner.index', compact('banners'));
    }
    public function store(StoreBannerRequest $request)
    {
        $banner = new Banner();
        $banner->name = $request->name;
        //upload file
        $file = $request->file('image_url');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        // lưu vào disk public (storage/app/public)
        $path = $file->storeAs('images/banners', $filename, 'public'); // returns images/xxxxx.jpg
        $banner->image_url = $path;
        //end upload file
        if ($banner->save()) {
            return redirect()->route('banner.index')->with('success', 'Thêm thành công');;
        }
    }
    public function edit($id)
    {
        $banner = Banner::find($id);
        if ($banner == null) {
            return redirect()->route('banner.index')->with('error', 'Không tìm thấy thông tin!');
        }
        return view('banner.edit', compact('banner'));
    }
    public function update(UpdateBannerRequest $request, $id)
    {
        $banner = Banner::find($id);
        if ($banner == null) {
            return redirect()->route('banner.index')->with('error', 'Không tìm thấy thông tin!');
        }
        $banner->name = $request->name;
        //upload file
        if ($request->hasFile('image_url')) {
            if ($banner->image_url && Storage::disk('public')->exists($banner->image_url)) {
                Storage::disk('public')->delete("images/banners/" . $banner->image_url);
            }
            $file = $request->file('image_url');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/banners', $filename, 'public');
            $banner->image_url = $path;
        }
        //end upload file
        if ($banner->save()) {
            return redirect()->route('banner.index')->with('success', 'Cập nhật thành công');;
        }
    }
    public function destroy($id)
    {
        $banner = Banner::find($id);
        $image_banner = $banner->image_url;
        if ($banner == null) {
            return redirect()->route('banner.index')->with('error', 'Không tìm thấy thông tin!');
        }
        if ($banner->delete()) {
            if ($image_banner && Storage::disk('public')->exists($image_banner)) {
                Storage::disk('public')->delete("images/banners/" . $image_banner);
            }
            return redirect()->route('banner.index')->with('success', 'Xóa thành công');;
        }
    }
}
