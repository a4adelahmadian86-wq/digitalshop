<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    public function cover(string $type = 'file'): Response
    {
        $labels = [
            'pdf'=>'PDF','word'=>'WORD','doc'=>'WORD','docx'=>'WORD','excel'=>'EXCEL','xls'=>'EXCEL','xlsx'=>'EXCEL',
            'powerpoint'=>'PPT','ppt'=>'PPT','pptx'=>'PPT','html'=>'HTML','css'=>'CSS','js'=>'JS','javascript'=>'JS',
            'py'=>'PY','python'=>'PY','php'=>'PHP','sql'=>'SQL','json'=>'JSON','apk'=>'APK','svg'=>'SVG','wordpress'=>'WP','file'=>'FILE'
        ];
        $label = $labels[strtolower($type)] ?? strtoupper(substr($type,0,8));
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 560" role="img" aria-label="'.$label.'"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#eef4ff"/><stop offset="1" stop-color="#f7f3ff"/></linearGradient></defs><rect width="900" height="560" rx="42" fill="url(#g)"/><circle cx="760" cy="90" r="120" fill="#ffffff" opacity=".65"/><circle cx="120" cy="470" r="150" fill="#ffffff" opacity=".55"/><rect x="285" y="95" width="330" height="370" rx="28" fill="#fff" stroke="#dbe3f0" stroke-width="5"/><path d="M520 95v92h95" fill="#f0f4fa"/><path d="M520 95l95 92h-95z" fill="#e1e8f3"/><rect x="345" y="245" width="210" height="28" rx="14" fill="#dfe7f5"/><rect x="345" y="295" width="165" height="22" rx="11" fill="#e8edf6"/><rect x="345" y="335" width="190" height="22" rx="11" fill="#e8edf6"/><text x="450" y="410" text-anchor="middle" font-family="Arial,sans-serif" font-size="48" font-weight="800" fill="#304a8a">'.$label.'</text></svg>';
        return response($svg, 200, ['Content-Type'=>'image/svg+xml; charset=UTF-8','Cache-Control'=>'public, max-age=604800']);
    }

    public function category(string $slug): Response
    {
        $titles = ['academic-projects'=>'دانشگاه','employment'=>'کار','business-entrepreneurship'=>'کسب‌وکار','programming'=>'فناوری','content-social-media'=>'طراحی و محتوا','books-pdf'=>'کتاب و منابع'];
        $title = $titles[$slug] ?? 'دسته‌بندی';
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 700 430"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#f2f7ff"/><stop offset="1" stop-color="#f5f1ff"/></linearGradient></defs><rect width="700" height="430" rx="36" fill="url(#g)"/><circle cx="560" cy="100" r="82" fill="#fff" opacity=".75"/><rect x="175" y="75" width="350" height="245" rx="30" fill="#fff" stroke="#dce5f4" stroke-width="5"/><rect x="220" y="125" width="260" height="22" rx="11" fill="#dce6f5"/><rect x="220" y="170" width="205" height="18" rx="9" fill="#e8edf5"/><rect x="220" y="205" width="235" height="18" rx="9" fill="#e8edf5"/><circle cx="350" cy="280" r="42" fill="#edf3ff"/><text x="350" y="296" text-anchor="middle" font-family="Arial,sans-serif" font-size="34" font-weight="800" fill="#3157a6">DS</text><text x="350" y="365" text-anchor="middle" font-family="Arial,sans-serif" font-size="28" font-weight="800" fill="#344054">'.$title.'</text></svg>';
        return response($svg, 200, ['Content-Type'=>'image/svg+xml; charset=UTF-8','Cache-Control'=>'public, max-age=604800']);
    }

    public function legacy(string $path): BinaryFileResponse
    {
        $base = realpath(base_path('Images'));
        $file = realpath(base_path('Images/'.$path));
        abort_unless($base && $file && str_starts_with($file, $base.DIRECTORY_SEPARATOR) && is_file($file), 404);
        return response()->file($file);
    }
}
