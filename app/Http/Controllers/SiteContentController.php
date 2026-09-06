<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteContentController extends Controller
{
    public function page(string $slug)
    {
        $page = SitePage::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.content', compact('page'));
    }

    public function blog()
    {
        $posts = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->latest('id')
            ->paginate(9);

        $featured = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->latest('id')
            ->first();

        return view('blog.index', compact('posts', 'featured'));
    }

    public function post(string $slug)
    {
        $post = BlogPost::with('author')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $content = (string) $post->content;
        $toc = [];
        $headingIndex = 0;

        $contentWithAnchors = preg_replace_callback(
            '/<h([23])([^>]*)>(.*?)<\/h\1>/isu',
            function ($matches) use (&$toc, &$headingIndex) {
                $headingIndex++;
                $level = (int) $matches[1];
                $text = trim(strip_tags($matches[3]));
                $id = 'section-' . $headingIndex;
                $toc[] = ['id' => $id, 'text' => $text, 'level' => $level];

                $attributes = $matches[2];
                if (!preg_match('/\bid\s*=\s*["\'][^"\']+["\']/i', $attributes)) {
                    $attributes .= ' id="' . $id . '"';
                }

                return '<h' . $level . $attributes . '>' . $matches[3] . '</h' . $level . '>';
            },
            $content
        );

        $wordCount = count(array_filter(preg_split('/\s+/u', trim(strip_tags($content)))));
        $readingMinutes = max(1, (int) ceil($wordCount / 220));

        $related = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->latest('id')
            ->limit(3)
            ->get();

        return view('blog.show', [
            'post' => $post,
            'toc' => $toc,
            'contentWithAnchors' => $contentWithAnchors ?: $content,
            'readingMinutes' => $readingMinutes,
            'related' => $related,
        ]);
    }
}
