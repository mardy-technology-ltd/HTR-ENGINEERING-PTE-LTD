<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\AboutContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class AboutContentController extends AdminController
{
    public function index()
    {
        $contents = AboutContent::ordered()->get();
        return view('admin.about.index', compact('contents'));
    }

    public function edit(AboutContent $aboutContent)
    {
        return view('admin.about.edit', compact('aboutContent'));
    }

    public function update(Request $request, AboutContent $aboutContent)
    {
        $validated = $request->validate([
            'section_key' => 'required|string',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($aboutContent->image) {
                Storage::disk('public')->delete($aboutContent->image);
            }
            $validated['image'] = $request->file('image')->store('about', 'public');
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        $aboutContent->update($validated);

        return redirect()->route('admin.about.index')
            ->with('success', 'About content updated successfully.');
    }

    public function seed()
    {
        Artisan::call('db:seed', ['--class' => 'AboutContentSeeder']);
        
        return redirect()->route('admin.about.index')
            ->with('success', 'About content initialized successfully.');
    }
}
