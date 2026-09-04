<?php

namespace App\Http\Controllers;

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
                'message' => 'دستیار هوشمند آماده است، اما سرویس هوش مصنوعی هنوز در تنظیمات سایت فعال نشده است. کافی است اتصال AI را در فایل محیطی پروژه تکمیل کنید.',
                'configured' => false,
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

        $system = 'تو دستیار هوشمند یک فروشگاه فایل دیجیتال فارسی هستی. پاسخ‌ها کوتاه، دقیق، محترمانه و کاربردی باشند. درباره محصولات، جست‌وجو، خرید، سبد خرید، حساب کاربری، دانلود و امکانات سایت راهنمایی کن. هرگز قیمت، موجودی، وضعیت سفارش یا اطلاعاتی را که در اختیار نداری جعل نکن. اگر اطلاعات دقیق لازم است، کاربر را به صفحه مرتبط سایت هدایت کن.';

        if (!empty($validated['page'])) {
            $system .= "\nصفحه فعلی کاربر: {$validated['page']}";
        }

        $messages = array_merge(
            [['role' => 'system', 'content' => $system]],
            array_slice($history, -10),
            [['role' => 'user', 'content' => $validated['message']]]
        );

        try {
            $response = Http::withToken($key)
                ->acceptJson()
                ->timeout((int) config('ai.timeout', 45))
                ->post($endpoint, [
                    'model' => $model,
                    'temperature' => 0.2,
                    'messages' => $messages,
                ]);

            if ($response->failed()) {
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
}
