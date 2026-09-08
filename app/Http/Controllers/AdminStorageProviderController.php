<?php

namespace App\Http\Controllers;

use App\Models\StorageProvider;
use App\Services\Storage\StorageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class AdminStorageProviderController extends Controller
{
    public function index()
    {
        $providers = StorageProvider::withCount('products')->latest()->get()->map(function ($provider) {
            $provider->capacity = $this->capacity($provider);
            return $provider;
        });
        return view('admin.storage.index', compact('providers'));
    }

    protected function capacity(StorageProvider $provider): array
    {
        $config = $provider->config ?? [];
        if ($provider->type === 'local') {
            $root = config('filesystems.disks.' . ($config['disk'] ?? 'local') . '.root', storage_path('app/private'));
            $total = @disk_total_space($root) ?: 0;
            $free = @disk_free_space($root) ?: 0;
            $used = max(0, $total - $free);
            return ['total'=>$total,'used'=>$used,'free'=>$free,'percent'=>$total ? min(100, round($used/$total*100,1)) : 0,'limit'=>$total,'source'=>'disk'];
        }
        $limit = (int) ($config['limit_bytes'] ?? 0);
        $used = (int) ($config['used_bytes'] ?? 0);
        return ['total'=>$limit,'used'=>$used,'free'=>max(0,$limit-$used),'percent'=>$limit ? min(100,round($used/$limit*100,1)) : 0,'limit'=>$limit,'source'=>'configured'];
    }

    public function create() { return view('admin.storage.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>['required','string','max:255'],
            'type'=>['required','in:local,api'],
            'endpoint'=>['nullable','url','required_if:type,api'],
            'api_key'=>['nullable','string','max:5000','required_if:type,api'],
            'upload_path'=>['nullable','string','max:255'],
            'delete_path'=>['nullable','string','max:255'],
            'exists_path'=>['nullable','string','max:255'],
            'download_path'=>['nullable','string','max:255'],
            'file_field'=>['nullable','string','max:100'],
            'path_field'=>['nullable','string','max:100'],
            'header_name'=>['nullable','string','max:100'],
            'header_prefix'=>['nullable','string','max:100'],
            'limit_gb'=>['nullable','numeric','min:0'],
        ]);

        $config = $data['type'] === 'local'
            ? ['disk'=>'local']
            : [
                'endpoint'=>rtrim($data['endpoint'],'/'),
                'api_key_encrypted'=>Crypt::encryptString($data['api_key']),
                'upload_path'=>$data['upload_path'] ?? 'upload',
                'delete_path'=>$data['delete_path'] ?? 'delete',
                'exists_path'=>$data['exists_path'] ?? 'exists',
                'download_path'=>$data['download_path'] ?? 'download',
                'file_field'=>$data['file_field'] ?? 'file',
                'path_field'=>$data['path_field'] ?? 'path',
                'header_name'=>$data['header_name'] ?? 'Authorization',
                'header_prefix'=>$data['header_prefix'] ?? 'Bearer ',
                'timeout'=>120,
                'limit_bytes'=>(int)(($data['limit_gb'] ?? 0)*1024*1024*1024),
                'used_bytes'=>0,
            ];

        StorageProvider::create(['name'=>$data['name'],'type'=>$data['type'],'config'=>$config,'is_active'=>true,'is_default'=>false]);
        return redirect()->route('admin.storage.index')->with('success','Storage Provider با موفقیت ایجاد شد.');
    }

    public function edit(StorageProvider $storageProvider) { return view('admin.storage.edit', compact('storageProvider')); }

    public function update(Request $request, StorageProvider $storageProvider)
    {
        $data = $request->validate([
            'name'=>['required','string','max:255'],
            'endpoint'=>['nullable','url','required_if:type,api'],
            'api_key'=>['nullable','string','max:5000'],
            'upload_path'=>['nullable','string','max:255'],'delete_path'=>['nullable','string','max:255'],
            'exists_path'=>['nullable','string','max:255'],'download_path'=>['nullable','string','max:255'],
            'file_field'=>['nullable','string','max:100'],'path_field'=>['nullable','string','max:100'],
            'header_name'=>['nullable','string','max:100'],'header_prefix'=>['nullable','string','max:100'],
            'limit_gb'=>['nullable','numeric','min:0'],
        ]);
        $config=$storageProvider->config ?? [];
        if ($storageProvider->type==='api') {
            foreach(['endpoint','upload_path','delete_path','exists_path','download_path','file_field','path_field','header_name','header_prefix'] as $key) if(array_key_exists($key,$data) && $data[$key]!==null) $config[$key]=$data[$key];
            if(!empty($data['api_key'])) $config['api_key_encrypted']=Crypt::encryptString($data['api_key']);
            if(array_key_exists('limit_gb',$data)) $config['limit_bytes']=(int)($data['limit_gb']*1024*1024*1024);
        }
        $storageProvider->update(['name'=>$data['name'],'config'=>$config]);
        return redirect()->route('admin.storage.index')->with('success','Storage Provider ویرایش شد.');
    }

    public function toggle(StorageProvider $storageProvider)
    {
        if($storageProvider->is_default && $storageProvider->is_active) return back()->with('error','Provider پیش‌فرض را نمی‌توان غیرفعال کرد.');
        $storageProvider->update(['is_active'=>!$storageProvider->is_active]);
        return back()->with('success','وضعیت Provider تغییر کرد.');
    }

    public function makeDefault(StorageProvider $storageProvider)
    {
        abort_unless($storageProvider->is_active,422,'Provider باید فعال باشد.');
        StorageProvider::query()->update(['is_default'=>false]);
        $storageProvider->update(['is_default'=>true]);
        return back()->with('success','Provider پیش‌فرض تغییر کرد.');
    }

    public function test(StorageProvider $storageProvider, StorageManager $storageManager)
    {
        try { if(!$storageManager->provider($storageProvider)->testConnection()) throw new \RuntimeException('اتصال ناموفق بود.'); return back()->with('success','اتصال Storage با موفقیت تست شد.'); }
        catch(\Throwable $e){ report($e); return back()->with('error','تست Storage ناموفق بود: '.$e->getMessage()); }
    }

    public function destroy(StorageProvider $storageProvider)
    {
        if($storageProvider->products()->exists()) return back()->with('error','این Provider دارای محصول است و نمی‌توان آن را حذف کرد.');
        if($storageProvider->is_default) return back()->with('error','Provider پیش‌فرض را نمی‌توان حذف کرد.');
        $storageProvider->delete();
        return back()->with('success','Provider حذف شد.');
    }
}