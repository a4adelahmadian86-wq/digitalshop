<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * لیست کاربران
     */
    public function index(Request $request)
    {
        $query = User::query();

        /*
         * Search
         */
        if ($search = trim($request->input('search', ''))) {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'first_name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'last_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /*
         * Role filter
         */
        if ($request->filled('role')) {

            $query->where(
                'role',
                $request->input('role')
            );
        }

        /*
         * Status filter
         */
        if ($request->filled('status')) {

            $query->where(
                'is_active',
                $request->input('status') === 'active'
            );
        }

        $users = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => User::count(),

            'active' => User::where(
                'is_active',
                true
            )->count(),

            'inactive' => User::where(
                'is_active',
                false
            )->count(),

            'admins' => User::where(
                'role',
                'admin'
            )->count(),

            'verified' => User::whereNotNull(
                'phone_verified_at'
            )->count(),
        ];

        return view(
            'admin.users.index',
            compact(
                'users',
                'stats'
            )
        );
    }


    /**
     * صفحه ایجاد کاربر
     */
    public function create()
    {
        return view('admin.users.create');
    }


    /**
     * ساخت کاربر توسط مدیر
     *
     * طبق معماری فعلی، مدیر می‌تواند
     * بدون OTP کاربر ایجاد کند.
     */
    public function store(Request $request)
    {
        $data = $request->validate([

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/',
                'unique:users,phone',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in([
                    'user',
                    'admin',
                ]),
            ],

        ], [

            'first_name.required' =>
                'نام الزامی است.',

            'phone.required' =>
                'شماره موبایل الزامی است.',

            'phone.regex' =>
                'شماره موبایل معتبر نیست.',

            'phone.unique' =>
                'این شماره موبایل قبلاً ثبت شده است.',

            'password.min' =>
                'رمز عبور باید حداقل ۸ کاراکتر باشد.',

            'password.confirmed' =>
                'تکرار رمز عبور صحیح نیست.',
        ]);

        $user = User::create([

            'first_name' =>
                $data['first_name'],

            'last_name' =>
                $data['last_name'] ?? null,

            'phone' =>
                $this->normalizePhone(
                    $data['phone']
                ),

            'password' =>
                Hash::make(
                    $data['password']
                ),

            'role' =>
                $data['role'],

            'is_active' =>
                true,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'کاربر با موفقیت ایجاد شد.'
            );
    }


    /**
     * مشاهده جزئیات کاربر
     */
    public function show(User $user)
    {
        $user->load([
            'wallet',
            'wallet.transactions',
            'wallet.topups',
        ]);

        $orders = $user->orders()
            ->with([
                'items.product',
                'payment',
            ])
            ->latest()
            ->paginate(
                10,
                ['*'],
                'orders_page'
            );

        $purchasedItems = $user->orderItems()
            ->with([
                'order',
                'product',
                'downloads',
            ])
            ->latest()
            ->get();

        $totalOrders = $user->orders()->count();

        $totalPurchased = $user->orders()
            ->where('status', 'paid')
            ->sum('total');

        $purchasedFilesCount = $purchasedItems
            ->filter(function ($item) {
                return $item->product !== null;
            })
            ->count();

        return view(
            'admin.users.show',
            compact(
                'user',
                'orders',
                'purchasedItems',
                'totalOrders',
                'totalPurchased',
                'purchasedFilesCount'
            )
        );
    }


    /**
     * فرم ویرایش
     */
    public function edit(User $user)
    {
        return view(
            'admin.users.edit',
            compact('user')
        );
    }


    /**
     * بروزرسانی
     */
    public function update(
        Request $request,
        User $user
    ) {
        $data = $request->validate([

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/',
                Rule::unique(
                    'users',
                    'phone'
                )->ignore($user->id),
            ],

            'role' => [
                'required',
                Rule::in([
                    'user',
                    'admin',
                ]),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

        ], [

            'first_name.required' =>
                'نام الزامی است.',

            'phone.regex' =>
                'شماره موبایل معتبر نیست.',

            'phone.unique' =>
                'این شماره موبایل قبلاً ثبت شده است.',

            'password.min' =>
                'رمز عبور باید حداقل ۸ کاراکتر باشد.',

            'password.confirmed' =>
                'تکرار رمز عبور صحیح نیست.',
        ]);

        /*
         * جلوگیری از تغییر نقش ادمین جاری
         */
        if (
            auth()->id() === $user->id &&
            $data['role'] !== 'admin'
        ) {
            return back()
                ->withErrors([
                    'role' =>
                        'نمی‌توانید نقش حساب فعلی خودتان را از مدیر حذف کنید.',
                ])
                ->withInput();
        }

        $user->first_name =
            $data['first_name'];

        $user->last_name =
            $data['last_name'] ?? null;

        $user->phone =
            $this->normalizePhone(
                $data['phone']
            );

        $user->role =
            $data['role'];

        if (
            !empty($data['password'])
        ) {
            $user->password =
                Hash::make(
                    $data['password']
                );
        }

        $user->save();

        return redirect()
            ->route(
                'admin.users.edit',
                $user
            )
            ->with(
                'success',
                'اطلاعات کاربر بروزرسانی شد.'
            );
    }


    /**
     * فعال / غیرفعال کردن
     */
    public function toggle(User $user)
    {
        /*
         * مدیر جاری نباید بتواند
         * خودش را غیرفعال کند.
         */
        if (auth()->id() === $user->id) {

            return back()->with(
                'error',
                'نمی‌توانید حساب کاربری خودتان را غیرفعال کنید.'
            );
        }

        $user->update([
            'is_active' =>
                !$user->is_active,
        ]);

        return back()->with(
            'success',
            $user->is_active
                ? 'حساب کاربر فعال شد.'
                : 'حساب کاربر غیرفعال شد.'
        );
    }


    /**
     * نرمال‌سازی شماره موبایل
     */
    private function normalizePhone(
        string $phone
    ): string {
        $phone = trim($phone);

        $phone = str_replace(
            [
                '۰',
                '۱',
                '۲',
                '۳',
                '۴',
                '۵',
                '۶',
                '۷',
                '۸',
                '۹',
            ],
            [
                '0',
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
            ],
            $phone
        );

        return $phone;
    }
}
