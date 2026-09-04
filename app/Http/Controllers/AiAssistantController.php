<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiAssistantController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'messages' => ['nullable', 'array', 'max:12'],
            'page' => ['nullable', 'string', 'max:300'],
        ]);

        $endpoint = config('ai.endpoint');
        $key = config('ai.key');
        $model = config('ai.model');

        if (!$endpoint || !$key || !$model) {
            return response()->json([
                'ok' => true,
                'configured' => false,
                'message' => 'دستیار هوشمند فعال است، اما اتصال سرویس AI هنوز در تنظیمات سرور تکمیل نشده است.',
            ]);
        }

        $history = collect($validated['messages'] ?? [])
            ->filter(fn ($item) => is_array($item) && isset($item['role'], $item['content']))
            ->map(fn ($item) => [
                'role' => in_array($item['role'], ['user', 'assistant'], true) ? $item['role'] : 'user',
                'content' => mb_substr((string) $item['content'], 0, 2000),
            ])
            ->values()
            ->all();

        $page = (string) ($validated['page'] ?? $request->path());
        $siteContext = $this->buildSiteContext($request, $validated['message'], $page);

        $system = <<<'PROMPT'
تو دستیار هوشمند «فایل‌مارکت» هستی؛ یک فروشگاه فایل دیجیتال فارسی.

قوانین مهم:
1) اطلاعات موجود در بخش «اطلاعات واقعی سایت» منبع معتبر است. قیمت، محصول، دسته‌بندی، سفارش، وضعیت یا آمار را خارج از این اطلاعات حدس نزن.
2) اطلاعات خصوصی را فقط درباره کاربر واردشده و فقط در حدی که در زمینه گفتگو آمده پاسخ بده. هیچ شماره تلفن، کد ملی، مسیر فایل، کلید پرداخت یا اطلاعات محرمانه فنی را نمایش نده.
3) اگر اطلاعات کافی نیست، صریح بگو اطلاعات کافی در اختیار ندارم و کاربر را به بخش مناسب سایت راهنمایی کن.
4) برای پیشنهاد محصول، فقط محصولاتی را پیشنهاد بده که در اطلاعات سایت آمده‌اند. اگر محصول دقیق پیدا نشد، چند گزینه مرتبط از همان فهرست پیشنهاد کن.
5) در پنل مدیریت، می‌توانی آمار و اطلاعات مدیریتی موجود در زمینه را برای مدیر توضیح بدهی، اما هیچ عملیات تغییردهنده‌ای را ادعا نکن و برای انجام تغییر، کاربر را به ابزار مربوط در پنل هدایت کن.
6) پاسخ‌ها فارسی، کوتاه، روشن و کاربردی باشند. در صورت نیاز از فهرست کوتاه استفاده کن.
7) از ساختن لینک یا نام route که در زمینه نیامده خودداری کن.
PROMPT;

        $messages = array_merge(
            [['role' => 'system', 'content' => $system]],
            array_slice($history, -10),
            [[
                'role' => 'user',
                'content' => "اطلاعات واقعی سایت:\n" . json_encode($siteContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n\nسؤال کاربر:\n" . $validated['message'],
            ]]
        );

        try {
            $response = Http::withToken($key)
                ->acceptJson()
                ->timeout((int) config('ai.timeout', 45))
                ->post($endpoint, [
                    'model' => $model,
                    'temperature' => 0.15,
                    'messages' => $messages,
                ]);

            if ($response->failed()) {
                report(new \RuntimeException('AI provider returned HTTP ' . $response->status()));

                return response()->json([
                    'ok' => false,
                    'message' => 'در حال حاضر ارتباط با سرویس هوش مصنوعی برقرار نشد. لطفاً چند لحظه دیگر دوباره تلاش کنید.',
                ], 502);
            }

            $answer = trim((string) data_get($response->json(), 'choices.0.message.content', ''));

            if ($answer === '') {
                return response()->json([
                    'ok' => false,
                    'message' => 'پاسخ معتبری از سرویس هوش مصنوعی دریافت نشد.',
                ], 502);
            }

            $answer = preg_replace('/^```(?:text|markdown)?\s*|\s*```$/mi', '', $answer);

            return response()->json([
                'ok' => true,
                'configured' => true,
                'message' => trim($answer),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'message' => 'سرویس هوش مصنوعی موقتاً در دسترس نیست. لطفاً بعداً دوباره امتحان کنید.',
            ], 502);
        }
    }

    private function buildSiteContext(Request $request, string $message, string $page): array
    {
        $context = [
            'current_page' => mb_substr($page, 0, 300),
            'published_products' => [],
            'active_categories' => [],
        ];

        $terms = $this->searchTerms($message);

        $productsQuery = Product::query()
            ->where('is_published', true)
            ->with(['category:id,name,slug'])
            ->select([
                'id',
                'category_id',
                'title',
                'slug',
                'short_description',
                'price',
                'is_published',
            ]);

        if ($terms !== []) {
            $productsQuery->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('title', 'like', '%' . $term . '%')
                        ->orWhere('short_description', 'like', '%' . $term . '%');
                }
            });
        }

        $products = $productsQuery
            ->latest('id')
            ->limit(12)
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'short_description' => mb_substr(strip_tags((string) $product->short_description), 0, 500),
                'price_toman' => (int) $product->price,
                'category' => $product->category?->name,
            ])
            ->values()
            ->all();

        $context['published_products'] = $products;

        $context['active_categories'] = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(30)
            ->get(['id', 'name', 'slug', 'description'])
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => mb_substr(strip_tags((string) $category->description), 0, 250),
            ])
            ->values()
            ->all();

        $currentProduct = $this->currentProduct($page);
        if ($currentProduct) {
            $context['current_product'] = $currentProduct;
        }

        if ($request->user()) {
            $context['customer'] = $this->customerContext($request->user());
        }

        if ($request->user()?->role === 'admin') {
            $context['admin'] = $this->adminContext();
        }

        return $context;
    }

    private function currentProduct(string $page): ?array
    {
        if (!preg_match('#^/products/([^/?]+)#u', $page, $matches)) {
            return null;
        }

        $product = Product::query()
            ->where('slug', $matches[1])
            ->where('is_published', true)
            ->with('category:id,name,slug')
            ->first();

        if (!$product) {
            return null;
        }

        return [
            'id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'short_description' => mb_substr(strip_tags((string) $product->short_description), 0, 700),
            'description' => mb_substr(strip_tags((string) $product->description), 0, 1600),
            'price_toman' => (int) $product->price,
            'category' => $product->category?->name,
        ];
    }

    private function customerContext(User $user): array
    {
        $orders = Order::query()
            ->where('user_id', $user->id)
            ->with('items.product:id,title,slug')
            ->latest('id')
            ->limit(5)
            ->get(['id', 'order_number', 'status', 'total', 'paid_at']);

        return [
            'order_count' => Order::where('user_id', $user->id)->count(),
            'recent_orders' => $orders->map(fn (Order $order) => [
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total_toman' => (int) $order->total,
                'paid_at' => $order->paid_at?->toIso8601String(),
                'products' => $order->items->map(fn ($item) => $item->product?->title)->filter()->values()->all(),
            ])->values()->all(),
        ];
    }

    private function adminContext(): array
    {
        $recentOrders = Order::query()
            ->with('items.product:id,title')
            ->latest('id')
            ->limit(8)
            ->get(['id', 'order_number', 'status', 'total', 'created_at']);

        return [
            'products_total' => Product::count(),
            'products_published' => Product::where('is_published', true)->count(),
            'categories_total' => Category::count(),
            'categories_active' => Category::where('is_active', true)->count(),
            'users_total' => User::count(),
            'orders_total' => Order::count(),
            'orders_paid' => Order::whereIn('status', ['paid', 'completed', 'success'])->count(),
            'sales_total_toman' => (int) Order::whereIn('status', ['paid', 'completed', 'success'])->sum('total'),
            'recent_orders' => $recentOrders->map(fn (Order $order) => [
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total_toman' => (int) $order->total,
                'created_at' => $order->created_at?->toIso8601String(),
                'products' => $order->items->map(fn ($item) => $item->product?->title)->filter()->values()->all(),
            ])->values()->all(),
        ];
    }

    private function searchTerms(string $message): array
    {
        $message = mb_strtolower(strip_tags($message));
        $message = preg_replace('/[^\p{L}\p{N}\s-]+/u', ' ', $message);
        $words = preg_split('/\s+/u', trim($message), -1, PREG_SPLIT_NO_EMPTY);

        $stopWords = [
            'و', 'یا', 'از', 'به', 'در', 'برای', 'با', 'که', 'را', 'این', 'آن', 'من', 'ما',
            'می', 'میشه', 'شود', 'چطور', 'چگونه', 'چی', 'چه', 'یک', 'است', 'هست', 'هستم',
            'دارم', 'دارید', 'میتونم', 'میتوانم', 'لطفا', 'لطفاً', 'فایل', 'محصول', 'قیمت',
            'خرید', 'سایت', 'کمک', 'کن', 'کنید', 'کرد', 'کردن', 'راهنمایی',
        ];

        return collect($words)
            ->filter(fn ($word) => mb_strlen($word) >= 2 && !in_array($word, $stopWords, true))
            ->unique()
            ->take(6)
            ->values()
            ->all();
    }
}
