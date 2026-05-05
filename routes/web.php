<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\AdminUserController;

// ----------------------------------------------------------------------
// Public Routes
// ----------------------------------------------------------------------
Route::get('/', fn() => view('welcome'))->name('home');

// Public auth routes
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read.all');
});

// ----------------------------------------------------------------------
// Customer Portal Routes (Requires 'auth' and 'customer' middleware)
// ----------------------------------------------------------------------
// Customer portal
Route::middleware(['auth', 'customer'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

    Route::get('/accounts',         [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/accounts/create',  [AccountController::class, 'create'])->name('accounts.create');
    Route::post('/accounts',        [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/accounts/{account}',[AccountController::class, 'show'])->name('accounts.show');
    Route::post('/accounts/{account}/close-request', [AccountController::class, 'closeRequest'])->name('accounts.close');

    Route::get('/profile',  [AccountController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');

    Route::get('/transactions',           [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions/transfer', [TransactionController::class, 'transfer'])->name('transactions.transfer');
    Route::post('/transactions/deposit',  [TransactionController::class, 'deposit'])->name('transactions.deposit');
    Route::post('/transactions/withdraw', [TransactionController::class, 'withdraw'])->name('transactions.withdraw');

    Route::get('/cards',      [ServiceController::class, 'cardsIndex'])->name('services.cards');
    Route::get('/loans',      [ServiceController::class, 'loansIndex'])->name('services.loans');
    Route::get('/savings',    [ServiceController::class, 'savingsIndex'])->name('services.savings');
    Route::get('/payments',   [ServiceController::class, 'paymentsIndex'])->name('services.payments');

    Route::post('/services/cards/request',          [ServiceController::class, 'requestCard'])->name('services.cards.request');
    Route::post('/services/cards/{card}/freeze',    [ServiceController::class, 'freezeCard'])->name('services.cards.freeze');
    Route::post('/services/cards/{card}/unfreeze',  [ServiceController::class, 'unfreezeCard'])->name('services.cards.unfreeze');
    Route::post('/services/cards/pay',              [ServiceController::class, 'cardPayment'])->name('services.cards.pay');

    Route::post('/services/loans/apply',  [ServiceController::class, 'applyLoan'])->name('services.loans.apply');
    Route::post('/services/loans/{loan}/repay', [ServiceController::class, 'repayLoan'])->name('services.loans.repay');
    Route::post('/services/savings',      [ServiceController::class, 'storeSavings'])->name('services.savings.store');
    Route::post('/services/payments/bill',     [ServiceController::class, 'payBill'])->name('services.payments.bill');
    Route::post('/services/payments/recharge', [ServiceController::class, 'mobileRecharge'])->name('services.payments.recharge');

    Route::get('/rewards',             [ServiceController::class, 'rewardsIndex'])->name('services.rewards');
    Route::post('/rewards/redeem',      [ServiceController::class, 'redeemRewards'])->name('services.rewards.redeem');
    Route::get('/support',             [SupportController::class, 'index'])->name('support.index');
    Route::post('/support',            [SupportController::class, 'store'])->name('support.store');
    Route::get('/support/{id}',        [SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{id}/reply', [SupportController::class, 'reply'])->name('support.reply');
});

// ----------------------------------------------------------------------
// Admin Portal Routes (Requires 'auth' and 'admin' middleware)
// ----------------------------------------------------------------------
// Admin portal
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');

    Route::get('/users',         [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create',  [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users',        [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}',  [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/loans',                 [ServiceController::class, 'adminLoansIndex'])->name('loans');
    Route::post('/loans/{loan}/approve', [ServiceController::class, 'approveLoan'])->name('loans.approve');
    Route::post('/loans/{loan}/reject',  [ServiceController::class, 'rejectLoan'])->name('loans.reject');

    Route::get('/cards-accounts', [ServiceController::class, 'adminCardsAccountsIndex'])->name('cards_accounts');
    Route::post('/accounts/{account}/toggle', [AccountController::class, 'adminToggleAccountStatus'])->name('accounts.toggle');
    Route::post('/accounts/{account}/approve-closure', [AccountController::class, 'adminApproveClosure'])->name('accounts.approve_closure');
    Route::post('/cards/{card}/toggle', [ServiceController::class, 'adminToggleCardStatus'])->name('cards.toggle');

    Route::get('/support',             [SupportController::class, 'index'])->name('support');
    Route::get('/support/{id}',        [SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{id}/reply', [SupportController::class, 'reply'])->name('support.reply');
    Route::post('/support/{id}/resolve', [SupportController::class, 'resolve'])->name('support.resolve');
    Route::get('/transactions', [TransactionController::class, 'adminIndex'])->name('transactions');
});
