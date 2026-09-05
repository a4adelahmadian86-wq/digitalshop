<?php

namespace App\Http\Controllers;

use App\Models\ProductFile;
use App\Models\ProductUpload;
use App\Models\StorageProvider;
use App\Services\Storage\StorageManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ZipArchive;

class AdminProductUploadController extends Controller
{
    private const MAX_BYTES = 209715200;
    private const EXTENSIONS = ['pdf','doc','docx','ppt','pptx'];
    private const MIME_TYPES = [
        'pdf'=>['application/pdf'],
        'doc'=>['application/msword','application/octet-stream','application/vnd.ms-office'],
        'docx'=>['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/zip','application/octet-stream'],
        'ppt'=>['application/vnd.ms-powerpoint','application/octet-stream','application/vnd.ms-office'],
        'pptx'=>['application/vnd.openxmlformats-officedocument.presentationml.presentation','application/zip','application/octet-stream'],
    ];

    public function store(Request $request, StorageManager $storageManager): JsonResponse
    {
        $validator=Validator::make($request->all(),['file'=>['required','file','max:209715'],'storage_provider_id'=>['nullable','integer','exists:storage_providers,id']]);
        $validator->after(function($validator)use($request){
            if(!$request->hasFile('file'))return;
            $file=$request->file('file');$ext=strtolower($file->getClientOriginalExtension());$mime=strtolower((string)$file->getMimeType());
            if(!in_array($ext,self::EXTENSIONS,true))$validator->errors()->add('file','فقط PDF، DOC، DOCX، PPT و PPTX مجاز است.');
            elseif(!in_array($mime,self::MIME_TYPES[$ext]??[],true))$validator->errors()->add('file','نوع واقعی فایل با پسوند آن سازگار نیست.');
            if((int)$file->getSize()>self::MAX_BYTES)$validator->errors()->add('file','حجم فایل نباید بیشتر از 200MB باشد.');
        });
        $validator->validate();
        $file=$request->file('file');
        $provider=$request->filled('storage_provider_id')?StorageProvider::findOrFail($request->integer('storage_provider_id')):StorageProvider::where('is_active',true)->where('is_default',true)->first()??StorageProvider::where('is_active',true)->orderBy('id')->first();
        abort_unless($provider&&$provider->is_active,422,'هیچ Storage Provider فعالی برای آپلود وجود ندارد.');
        return response()->json(['ok'=>true,'files'=>[$this->storeOne($file,$provider,$storageManager)]]);
    }

    private function storeOne(UploadedFile $file,StorageProvider $provider,StorageManager $storageManager):array
    {
        $sha256=hash_file('sha256',$file->getRealPath());
        abort_if(ProductFile::where('sha256',$sha256)->exists()||ProductUpload::where('sha256',$sha256)->where('status','uploaded')->exists(),422,'این فایل قبلاً ثبت شده یا در حال ثبت است.');
        $extension=strtolower($file->getClientOriginalExtension());$storedName=Str::uuid().'.'.$extension;$path='products/staging/'.auth()->id().'/'.date('Y/m').'/'.$storedName;$storedPath=$storageManager->upload($provider,$file,$path);
        $upload=ProductUpload::create(['user_id'=>auth()->id(),'storage_provider_id'=>$provider->id,'original_name'=>$file->getClientOriginalName(),'stored_path'=>$storedPath,'mime_type'=>$file->getMimeType(),'extension'=>$extension,'size'=>(int)$file->getSize(),'sha256'=>$sha256,'status'=>'uploaded']);
        return ['id'=>$upload->id,'name'=>$upload->original_name,'size'=>$upload->size,'extension'=>strtoupper($extension),'format'=>'.'.strtoupper($extension),'page_count'=>$this->detectPageCount($file,$extension),'status'=>'uploaded'];
    }

    private function detectPageCount(UploadedFile $file,string $extension):?int
    {
        try {
            if($extension==='pdf'){ $data=file_get_contents($file->getRealPath(),false,null,0,12*1024*1024); if($data===false)return null; preg_match_all('/\/Type\s*\/Page\b/',$data,$m); return !empty($m[0])?(int)count($m[0]):null; }
            if(in_array($extension,['docx','pptx'],true)){ $zip=new ZipArchive(); if($zip->open($file->getRealPath())!==true)return null; $xml=$zip->getFromName('docProps/app.xml'); $zip->close(); if(!$xml)return null; $tag=$extension==='docx'?'Pages':'Slides'; if(preg_match('/<'.$tag.'>(\d+)<\/'.$tag.'>/i',$xml,$m))return (int)$m[1]; }
        }catch(\Throwable $e){report($e);}
        return null;
    }

    public function destroy(ProductUpload $upload,StorageManager $storageManager):JsonResponse
    {
        abort_unless($upload->user_id===auth()->id()&&$upload->status==='uploaded',403);
        try{$storageManager->provider($upload->storageProvider)->delete($upload->stored_path);}catch(\Throwable $e){report($e);}
        $upload->update(['status'=>'deleted']);return response()->json(['ok'=>true]);
    }
}
