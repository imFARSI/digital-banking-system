<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\Reward;
use App\Models\RewardTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'admin123';

    // Show the login form page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login: admin bypass via constants, then standard credential check
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($request->email === self::ADMIN_USERNAME && $request->password === self::ADMIN_PASSWORD) {
            $admin = User::firstOrCreate(
                ['email' => 'admin@finexa.internal'],
                [
                    'name'          => 'Finexa Admin',
                    'email'         => 'admin@finexa.internal',
                    'phone'         => '00000000000',
                    'password'      => Hash::make(self::ADMIN_PASSWORD),
                    'nid'           => 'ADMIN-INTERNAL-001',
                    'date_of_birth' => '1990-01-01',
                    'gender'        => 'male',
                    'role'          => 'admin',
                    'status'        => 'active',
                ]
            );

            Auth::login($admin);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        $user = Auth::user();

        if (!$user->isActive()) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account has been suspended.']);
        }

        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    // Show the customer registration form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Register a new customer: creates user, savings account, and initial reward record
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:150', 'unique:users,email', 'regex:/@gmail\.com$/i'],
            'phone'         => ['required', 'string', 'max:20'],
            'password'      => ['required', 'confirmed', Password::min(8)],
            'nid'           => ['required', 'string', 'max:30', 'unique:users,nid'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender'        => ['required', 'in:male,female,other'],
        ], [
            'email.regex'        => 'Only Gmail addresses (@gmail.com) are accepted.',
            'email.unique'       => 'This email address is already registered.',
            'nid.unique'         => 'This National ID is already registered.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role']     = 'customer';
        $validated['status']   = 'active';

        $user = User::create($validated);

        Account::create([
            'user_id'        => $user->id,
            'account_number' => Account::generateAccountNumber(),
            'account_type'   => 'savings',
            'balance'        => 0.00,
            'currency'       => 'BDT',
            'status'         => 'active',
            'opened_at'      => now()->toDateString(),
        ]);

        $defaultTier = RewardTier::orderBy('min_points', 'asc')->first();
        if ($defaultTier) {
            Reward::create([
                'user_id'         => $user->id,
                'reward_tier_id'  => $defaultTier->id,
                'total_points'    => 0,
                'redeemed_points' => 0,
            ]);
        }

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    // Destroy the session and redirect to the login page
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
