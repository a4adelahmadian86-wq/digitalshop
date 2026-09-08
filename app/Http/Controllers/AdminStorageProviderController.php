<?php

namespace App\Http\Controllers;

use App\Models\StorageProvider;
use App\Services\Storage\StorageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AdminStorageProviderController extends Controller
{
    public function index()
    {
        $providers = StorageProvider::withCount('products')->orderBy('scope')->orderBy('location')->orderBy('priority')->get()->map(function ($provider) {
            $provider->capacity = $this->capacity($provider);
            return $provider;
        });
        $groups = [];
        foreach (StorageProvider::SCOPES as $scope => $label) {
            foreach (StorageProvider::LOCATIONS as $location => $locationLabel) {
                $groups[$scope][$location] = $providers->where('scope',$scope)->where('location',$location)->values();
            }
        }
        return view('admin.storage.index', compact('providers','groups'));
    }

    protected function capacity(StorageProvider $provider): array
    {
        $config = $provider->config ?? [];
        if ($provider->type === 'local') {
            $root = config('filesystems.disks.' . ($config['disk'] ?? 'local') . '.root', storage_path('app/private'));
            $total = @disk_total_space($root) ?: 0; $free = @disk_free_space($root) ?: 0;
            $used = max(0, $total - $free);
            return ['total'=>$total,'used'=>$used,'free'=>$free,'percent'=>$total ? min(100,round($used/$total*100,1)) : 0,'source'=>'disk'];
        }
        $limit=(int)($config['limit_bytes']??0); $used=(int)($config['used_bytes']??0);
        return ['total'=>$limit,'used'=>$used,'free'=>max(0,$limit-$used),'percent'=>$limit?min(100,round($used/$limit*100,1)):0,'source'=>'configured'];
    }

    public function create() { return view('admin.storage.create', ['scopes'=>StorageProvider::SCOPES,'locations'=>StorageProvider::LOCATIONS]); }

    public function store(Request $request)
    {
        $data=$request->validate([
            'name'=>['required','string','max:255'], 'type'=>['required','in:local,api'],
            'scope'=>['required','in:files,backups,user_data,temporary'], 'location'=>['required','in:internal,external'],
            'priority'=>['required','integer','min:1','max:999999'], 'documentation_url'=>['nullable','url','max:2000'],
            'endpoint'=>['nullable','url','required_if:type,api'], 'api_key'=>['nullable','string','max:5000','required_if:type,api'],
            'upload_path'=>['nullable','string','max:255'],'delete_path'=>['nullable','string','max:255'],'exists_path'=>['nullable','string','max:255'],
            'download_path'=>['nullable','string','max:255'],'test_path'=>['nullable','string','max:255'],
            'file_field'=>['nullable','string','max:100'],'path_field'=>['nullable','string','max:100'],
            'header_name'=>['nullable','string','max:100'],'header_prefix'=>['nullable','string','max:100'],
            'path_response'=>['nullable','string','max:100'],'exists_response'=>['nullable','string','max:100'],
            'limit_gb'=>['nullable','numeric','min:0'],'max_file_mb'=>['nullable','numeric','min:0'],'max_files'=>['nullable','integer','min:0'],
            'retention_days'=>['nullable','integer','min:1','max:36500'],
        ]);
        $config=['disk'=>'local'];
        if($data['type']==='api') $config=[
            'endpoint'=>rtrim($data['endpoint'],'/'),'api_key_encrypted'=>Crypt::encryptString($data['api_key']),
            'upload_path'=>$data['upload_path']??'upload','delete_path'=>$data['delete_path']??'delete','exists_path'=>$data['exists_path']??'exists',
            'download_path'=>$data['download_path']??'download','test_path'=>$data['test_path']??'','file_field'=>$data['file_field']??'file','path_field'=>$data['path_field']??'path',
            'header_name'=>$data['header_name']??'Authorization','header_prefix'=>$data['header_prefix']??'Bearer ','path_response'=>$data['path_response']??'path','exists_response'=>$data['exists_response']??'exists',
            'timeout'=>120,'limit_bytes'=>(int)(($data['limit_gb']??0)*1073741824),'max_file_bytes'=>(int)(($data['max_file_mb']??0)*1048576),'max_files'=>(int)($data['max_files']??0),'used_bytes'=>0,
        ];
        if($data['scope']==='temporary') $config['retention_days']=(int)($data['retention_days']??30);
        StorageProvider::create(['name'=>$data['name'],'type'=>$data['type'],'scope'=>$data['scope'],'location'=>$data['location'],'priority'=>$data['priority'],'documentation_url'=>$data['documentation_url']??null,'config'=>$config,'is_active'=>true,'is_default'=>false]);
        return redirect()->route('admin.storage.index')->with('success','Storage Provider با موفقیت ایجاد شد.');
    }

    public function edit(StorageProvider $storageProvider) { return view('admin.storage.edit', compact('storageProvider')); }

    public function update(Request $request, StorageProvider $storageProvider)
    {
        $data=$request->validate(['name'=>['required','string','max:255'],'scope'=>['required','in:files,backups,user_data,temporary'],'location'=>['required','in:internal,external'],'priority'=>['required','integer','min:1','max:999999'],'documentation_url'=>['nullable','url','max:2000'],'api_key'=>['nullable','string','max:5000'],'limit_gb'=>['nullable','numeric','min:0'],'max_file_mb'=>['nullable','numeric','min:0'],'max_files'=>['nullable','integer','min:0'],'retention_days'=>['nullable','integer','min:1','max:36500']]);
        $config=$storageProvider->config??[]; $config['limit_bytes']=array_key_exists('limit_gb',$data)?(int)($data['limit_gb']*1073741824):($config['limit_bytes']??0); $config['max_file_bytes']=array_key_exists('max_file_mb',$data)?(int)($data['max_file_mb']*1048576):($config['max_file_bytes']??0); $config['max_files']=array_key_exists('max_files',$data)?(int)$data['max_files']:($config['max_files']??0);
        if($storageProvider->scope==='temporary' || $data['scope']==='temporary') $config['retention_days']=(int)($data['retention_days']??30);
        if(!empty($data['api_key'])) $config['api_key_encrypted']=Crypt::encryptString($data['api_key']);
        $storageProvider->update(['name'=>$data['name'],'scope'=>$data['scope'],'location'=>$data['location'],'priority'=>$data['priority'],'documentation_url'=>$data['documentation_url']??null,'config'=>$config]);
        return redirect()->route('admin.storage.index')->with('success','Storage Provider ویرایش شد.');
    }

    public function toggle(StorageProvider $storageProvider) { if($storageProvider->is_default&&$storageProvider->is_active)return back()->with('error','Provider پیش‌فرض را نمی‌توان غیرفعال کرد.'); $storageProvider->update(['is_active'=>!$storageProvider->is_active]); return back()->with('success','وضعیت Provider تغییر کرد.'); }
    public function makeDefault(StorageProvider $storageProvider) { abort_unless($storageProvider->is_active,422,'Provider باید فعال باشد.'); StorageProvider::query()->where('scope',$storageProvider->scope)->where('location',$storageProvider->location)->update(['is_default'=>false]); $storageProvider->update(['is_default'=>true]); return back()->with('success','Provider پیش‌فرض این بخش تغییر کرد.'); }
    public function test(StorageProvider $storageProvider, StorageManager $storageManager) { try { if(!$storageManager->provider($storageProvider)->testConnection()) throw new \RuntimeException('اتصال ناموفق بود.'); return back()->with('success','اتصال Storage با موفقیت تست شد.'); } catch(\Throwable $e){ report($e); return back()->with('error','تست Storage ناموفق بود: '.$e->getMessage()); } }
    public function destroy(StorageProvider $storageProvider) { if($storageProvider->products()->exists())return back()->with('error','این Provider دارای محصول است و نمی‌توان آن را حذف کرد.'); if($storageProvider->is_default)return back()->with('error','Provider پیش‌فرض را نمی‌توان حذف کرد.'); $storageProvider->delete(); return back()->with('success','Provider حذف شد.'); }
}
