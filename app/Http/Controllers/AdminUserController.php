<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        if ($request->filled('role')) $query->where('role', $request->input('role'));
        if ($request->filled('status')) $query->where('is_active', $request->input('status') === 'active');
        $users = $query->latest()->paginate(20)->withQueryString();
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'verified' => User::whereNotNull('phone_verified_at')->count(),
        ];
        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request, AuditLogger $auditLogger)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^09\d{9}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['buyer', 'user', 'staff', 'admin'])],
        ]);

        if (!$request->user()->isAdmin() && $data['role'] === 'admin') {
            return back()->withErrors(['role' => 'ایجاد مدیر ارشد فقط توسط مدیر ارشد مجاز است.'])->withInput();
        }

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'phone' => $this->normalizePhone($data['phone']),
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'is_active' => true,
        ]);

        $auditLogger->record($request, 'user.created', $user, ['role' => $user->role]);
        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    public function show(Request $request, User $user)
    {
        $canWallets = $request->user()->hasPermission('wallets.view');
        $canPayments = $request->user()->hasPermission('payments.view');

        if ($canWallets) {
            $user->load(['wallet', 'wallet.transactions', 'wallet.topups']);
        }

        $orderQuery = $user->orders()->with(['items.product'])->latest();
        if ($canPayments) {
            $orderQuery->with('payment');
        }
        $orders = $orderQuery->paginate(10, ['*'], 'orders_page');

        $purchasedItems = $user->orderItems()->with(['order', 'product', 'downloads'])->latest()->get();
        $totalOrders = $user->orders()->count();
        $totalPurchased = $canPayments ? $user->orders()->whereIn('status', ['paid', 'completed'])->sum('total') : null;
        $purchasedFilesCount = $purchasedItems->filter(fn ($item) => $item->product !== null)->count();

        if (!$canWallets || !$canPayments) {
            return view('admin.users.show-restricted', compact(
                'user', 'orders', 'purchasedItems', 'totalOrders', 'totalPurchased',
                'purchasedFilesCount', 'canWallets', 'canPayments'
            ));
        }

        return view('admin.users.show', compact('user', 'orders', 'purchasedItems', 'totalOrders', 'totalPurchased', 'purchasedFilesCount'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user, AuditLogger $auditLogger)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^09\d{9}$/', Rule::unique('users', 'phone')->ignore($user->id)],
            'role' => ['required', Rule::in(['buyer', 'user', 'staff', 'admin'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $roleChanged = $user->role !== $data['role'];
        if ($roleChanged && !$request->user()->isAdmin()) {
            return back()->withErrors(['role' => 'تغییر نقش فقط توسط مدیر ارشد مجاز است.'])->withInput();
        }

        if ($user->role === 'admin' && $data['role'] !== 'admin' && $this->wouldRemoveLastActiveAdmin($user)) {
            return back()->withErrors(['role' => 'حداقل یک مدیر ارشد فعال باید باقی بماند.'])->withInput();
        }

        $before = ['role' => $user->role, 'is_active' => $user->is_active];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'] ?? null;
        $user->phone = $this->normalizePhone($data['phone']);
        $user->role = $data['role'];
        if (!empty($data['password'])) $user->password = Hash::make($data['password']);
        $user->save();

        $auditLogger->record($request, 'user.updated', $user, ['before' => $before, 'after' => ['role' => $user->role, 'is_active' => $user->is_active]]);
        return redirect()->route('admin.users.edit', $user)->with('success', 'اطلاعات کاربر بروزرسانی شد.');
    }

    public function toggle(Request $request, User $user, AuditLogger $auditLogger)
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'نمی‌توانید حساب کاربری خودتان را غیرفعال کنید.');
        }
        if ($user->role === 'admin' && $user->is_active && $this->wouldRemoveLastActiveAdmin($user)) {
            return back()->with('error', 'حداقل یک مدیر ارشد فعال باید باقی بماند.');
        }

        $before = $user->is_active;
        $user->update(['is_active' => !$user->is_active]);
        $auditLogger->record($request, 'user.status.updated', $user, ['before' => $before, 'after' => $user->is_active]);
        return back()->with('success', $user->is_active ? 'حساب کاربر فعال شد.' : 'حساب کاربر غیرفعال شد.');
    }

    private function wouldRemoveLastActiveAdmin(User $user): bool
    {
        return User::where('role', 'admin')->where('is_active', true)->count() <= 1;
    }

    private function normalizePhone(string $phone): string
    {
        return str_replace(['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], ['0','1','2','3','4','5','6','7','8','9'], trim($phone));
    }
}
