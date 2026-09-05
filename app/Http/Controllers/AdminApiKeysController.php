<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Services\AI\AiSettingsStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AdminApiKeysController extends Controller
{
    public function edit(AiSettingsStore $ai)
    {
        abort_unless(auth()->user()?->hasPermission('integrations.manage'), 403);

        return view('admin.settings.api-keys', [
            'keys' => [
                'gemini' => filled($ai->get('key')),
                'gapgpt' => filled($ai->get('fallback_key')),
                'kpanel' => filled(SiteSetting::getValue('sms.kpanel.api_key')),
            ],
        ]);
    }

    public function update(Request $request, AiSettingsStore $ai)
    {
        abort_unless(auth()->user()?->hasPermission('integrations.manage'), 403);

        $data = $request->validate([
            'gemini_key' => 'nullable|string|max:1000',
            'gapgpt_key' => 'nullable|string|max:1000',
            'kpanel_key' => 'nullable|string|max:1000',
        ]);

        if (filled($data['gemini_key'] ?? null)) {
            $ai->put('key', $data['gemini_key'], true);
        }

        if (filled($data['gapgpt_key'] ?? null)) {
            $ai->put('fallback_key', $data['gapgpt_key'], true);
        }

        if (filled($data['kpanel_key'] ?? null)) {
            SiteSetting::putValue('sms.kpanel.api_key', Crypt::encryptString($data['kpanel_key']));
        }

        return back()->with('success', 'کلیدهای سرویس‌ها با موفقیت ذخیره شدند.');
    }
}