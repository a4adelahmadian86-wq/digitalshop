<?php

namespace App\Services\Product;

use App\Models\Product;

class ProductImageService
{
    public function defaultFor(Product $product): string
    {
        $extensions = $product->relationLoaded('files')
            ? $product->files->pluck('extension')->map(fn ($e) => strtolower((string) $e))->all()
            : $product->files()->pluck('extension')->map(fn ($e) => strtolower((string) $e))->all();

        if (in_array('pdf', $extensions, true)) {
            return asset('Images/pdf.png');
        }

        if (array_intersect(['doc', 'docx'], $extensions)) {
            return asset('Images/word.png');
        }

        if (array_intersect(['xls', 'xlsx', 'csv'], $extensions)) {
            return asset('Images/excel.png');
        }

        if (array_intersect(['ppt', 'pptx'], $extensions)) {
            return asset('Images/powerpoint.png');
        }

        if (in_array('html', $extensions, true)) return asset('Images/html.png');
        if (in_array('css', $extensions, true)) return asset('Images/css.png');
        if (in_array('js', $extensions, true)) return asset('Images/JavaScript.png');
        if (in_array('py', $extensions, true)) return asset('Images/Python.png');
        if (in_array('php', $extensions, true)) return asset('Images/php.png');
        if (in_array('sql', $extensions, true)) return asset('Images/SQL.png');
        if (in_array('json', $extensions, true)) return asset('Images/JSON.png');
        if (in_array('apk', $extensions, true)) return asset('Images/APK.png');
        if (in_array('svg', $extensions, true)) return asset('Images/svg.png');
        if (in_array('wordpress', $extensions, true)) return asset('Images/WordPress.png');

        return asset('Images/pdf.png');
    }
}
