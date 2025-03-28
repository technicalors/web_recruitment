<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{
    /**
     * Hiển thị danh sách cấu hình.
     */
    public function index(Request $request)
    {
        $configs = Config::query();

        if ($request->has('search')) {
            $configs->where('config_key', 'like', '%' . $request->search . '%');
        }

        $configs = $configs->paginate(10);

        return view('Admin.pages.configs.index', compact('configs'));
    }

    /**
     * Hiển thị form thêm mới cấu hình.
     */
    public function create()
    {
        return view('Admin.pages.configs.add_edit');
    }

    /**
     * Lưu cấu hình mới vào database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'config_key' => 'required|string|unique:config,config_key|max:255',
            'config_value' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Xử lý tải lên logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        Config::create($validated);

        return redirect()->route('admin.configs.index')->with('success', 'Cấu hình đã được thêm.');
    }

    /**
     * Hiển thị form chỉnh sửa cấu hình.
     */
    public function edit(Config $config)
    {
        return view('Admin.pages.configs.add_edit', compact('config'));
    }

    /**
     * Cập nhật cấu hình.
     */
    public function update(Request $request, Config $config)
    {
        $validated = $request->validate([
            'config_value' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Xử lý tải lên logo 
        if ($request->hasFile('logo')) {
            // Xóa logo cũ 
            if ($config->logo) {
                Storage::disk('public')->delete($config->logo);
            }

            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $config->update($validated);

        return back()->with('success', 'Cấu hình đã được cập nhật.');
    }

    /**
     * Xóa cấu hình.
     */
    public function destroy(Config $config)
    {
        // Xóa logo
        if ($config->logo) {
            Storage::disk('public')->delete($config->logo);
        }

        $config->delete();
        return back()->with('success', 'Cấu hình đã được xóa.');
    }

    /**
     * Lấy theme hiện tại.
     */
    public function getTheme()
    {
        $theme = Config::where('config_key', 'theme')->value('config_value');
        return response()->json(['theme' => $theme]);
    }

    /**
     * Cập nhật theme.
     */
    public function changeTheme(Request $request)
    {
        $request->validate(['theme' => 'required|in:light,dark']);

        Config::where('config_key', 'theme')->update(['config_value' => $request->theme]);

        return response()->json(['message' => 'Theme updated']);
    }
}
