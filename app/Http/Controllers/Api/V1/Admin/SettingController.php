<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        // Add full URLs for branding images
        if (isset($settings['branding'])) {
            $branding = $settings['branding'];
            if (isset($branding['logo'])) {
                $branding['logo_url'] = Storage::url($branding['logo']);
            }
            if (isset($branding['favicon'])) {
                $branding['favicon_url'] = Storage::url($branding['favicon']);
            }
            $settings['branding'] = $branding;
        }

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'address' => 'required|string',
        ]);

        Setting::set('general', $validated);

        return response()->json([
            'success' => true,
            'message' => 'General settings updated successfully',
        ]);
    }

    public function updateBranding(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
            'font_family' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:512',
            'remove_logo' => 'nullable|string',
            'remove_favicon' => 'nullable|string',
        ]);

        // Get existing branding settings
        $existingBranding = Setting::where('key', 'branding')->first();
        $data = $existingBranding ? $existingBranding->value : [];
        
        // Update colors and font
        if (isset($validated['primary_color'])) {
            $data['primary_color'] = $validated['primary_color'];
        }
        if (isset($validated['secondary_color'])) {
            $data['secondary_color'] = $validated['secondary_color'];
        }
        if (isset($validated['font_family'])) {
            $data['font_family'] = $validated['font_family'];
        }
        
        // Handle logo removal
        if ($request->input('remove_logo') === 'true') {
            if (isset($data['logo']) && Storage::disk('public')->exists($data['logo'])) {
                Storage::disk('public')->delete($data['logo']);
            }
            unset($data['logo'], $data['logo_url']);
        }
        // Handle logo upload
        elseif ($request->hasFile('logo')) {
            if (isset($data['logo']) && Storage::disk('public')->exists($data['logo'])) {
                Storage::disk('public')->delete($data['logo']);
            }
            $path = $request->file('logo')->store('branding', 'public');
            $data['logo'] = $path;
            $data['logo_url'] = Storage::url($path);
        }
        
        // Handle favicon removal
        if ($request->input('remove_favicon') === 'true') {
            if (isset($data['favicon']) && Storage::disk('public')->exists($data['favicon'])) {
                Storage::disk('public')->delete($data['favicon']);
            }
            unset($data['favicon'], $data['favicon_url']);
        }
        // Handle favicon upload
        elseif ($request->hasFile('favicon')) {
            if (isset($data['favicon']) && Storage::disk('public')->exists($data['favicon'])) {
                Storage::disk('public')->delete($data['favicon']);
            }
            $path = $request->file('favicon')->store('branding', 'public');
            $data['favicon'] = $path;
            $data['favicon_url'] = Storage::url($path);
        }
        
        // Add URLs for existing files
        if (isset($data['logo'])) {
            $data['logo_url'] = Storage::url($data['logo']);
        }
        if (isset($data['favicon'])) {
            $data['favicon_url'] = Storage::url($data['favicon']);
        }

        Setting::set('branding', $data);

        return response()->json([
            'success' => true,
            'message' => 'Branding settings updated successfully',
            'data' => $data,
        ]);
    }

    public function updateHomepage(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'nullable|string',
            'hero_subtitle' => 'nullable|string',
            'hero_cta_text' => 'nullable|string',
            'hero_cta_link' => 'nullable|string',
            'about_title' => 'nullable|string',
            'about_description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*.title' => 'required|string',
            'features.*.description' => 'required|string',
            'features.*.icon' => 'nullable|string',
        ]);

        Setting::set('homepage', $validated);

        return response()->json([
            'success' => true,
            'message' => 'Homepage settings updated successfully',
        ]);
    }

    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'twitter_card' => 'nullable|string',
            'twitter_site' => 'nullable|string',
        ]);

        $data = $validated;
        
        if ($request->hasFile('og_image')) {
            $path = $request->file('og_image')->store('seo', 'public');
            $data['og_image'] = $path;
        }

        Setting::set('seo', array_filter($data));

        return response()->json([
            'success' => true,
            'message' => 'SEO settings updated successfully',
        ]);
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'from_email' => 'nullable|email',
            'from_name' => 'nullable|string',
        ]);

        Setting::set('email', $validated);

        return response()->json([
            'success' => true,
            'message' => 'Email settings updated successfully',
        ]);
    }
}
