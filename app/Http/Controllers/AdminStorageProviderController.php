<?php

namespace App\Http\Controllers;

use App\Models\StorageProvider;
use App\Services\Storage\StorageManager;
use Illuminate\Http\Request;

class AdminStorageProviderController extends Controller
{
    public function index()
    {
        $providers = StorageProvider::withCount(
            'products'
        )
        ->latest()
        ->get();

        return view(
            'admin.storage.index',
            compact('providers')
        );
    }

    public function create()
    {
        return view('admin.storage.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'in:local',
            ],

        ]);

        if (
            $data['type'] === 'local'
        ) {

            StorageProvider::create([
                'name' => $data['name'],
                'type' => 'local',
                'config' => [
                    'disk' => 'local',
                ],
                'is_active' => true,
                'is_default' => false,
            ]);
        }

        return redirect()
            ->route('admin.storage.index')
            ->with(
                'success',
                'Storage Provider با موفقیت ایجاد شد.'
            );
    }

    public function edit(
        StorageProvider $storageProvider
    ) {
        return view(
            'admin.storage.edit',
            compact('storageProvider')
        );
    }

    public function update(
        Request $request,
        StorageProvider $storageProvider
    ) {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $storageProvider->update([
            'name' => $data['name'],
        ]);

        return redirect()
            ->route('admin.storage.index')
            ->with(
                'success',
                'Storage Provider ویرایش شد.'
            );
    }

    public function toggle(
        StorageProvider $storageProvider
    ) {
        if (
            $storageProvider->is_default &&
            $storageProvider->is_active
        ) {
            return back()->with(
                'error',
                'Provider پیش‌فرض را نمی‌توان غیرفعال کرد.'
            );
        }

        $storageProvider->update([
            'is_active' =>
                !$storageProvider->is_active,
        ]);

        return back()->with(
            'success',
            'وضعیت Provider تغییر کرد.'
        );
    }

    public function makeDefault(
        StorageProvider $storageProvider
    ) {
        abort_unless(
            $storageProvider->is_active,
            422,
            'Provider باید فعال باشد.'
        );

        StorageProvider::query()
            ->update([
                'is_default' => false,
            ]);

        $storageProvider->update([
            'is_default' => true,
        ]);

        return back()->with(
            'success',
            'Provider پیش‌فرض تغییر کرد.'
        );
    }

    public function test(
        StorageProvider $storageProvider,
        StorageManager $storageManager
    ) {
        try {

            $provider =
                $storageManager->provider(
                    $storageProvider
                );

            $result =
                $provider->testConnection();

            if (!$result) {
                throw new \RuntimeException(
                    'اتصال ناموفق بود.'
                );
            }

            return back()->with(
                'success',
                'اتصال Storage با موفقیت تست شد.'
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'تست Storage ناموفق بود: ' .
                $e->getMessage()
            );
        }
    }

    public function destroy(
        StorageProvider $storageProvider
    ) {
        if (
            $storageProvider->products()->exists()
        ) {
            return back()->with(
                'error',
                'این Provider دارای محصول است و نمی‌توان آن را حذف کرد.'
            );
        }

        if ($storageProvider->is_default) {
            return back()->with(
                'error',
                'Provider پیش‌فرض را نمی‌توان حذف کرد.'
            );
        }

        $storageProvider->delete();

        return back()->with(
            'success',
            'Provider حذف شد.'
        );
    }
}