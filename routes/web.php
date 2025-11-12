<?php

use App\Livewire\Customer\Home;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Users\DataUser;
use App\Livewire\Admin\Users\EditUser;
use App\Livewire\Admin\Service\Category;
use App\Livewire\Admin\Users\CreateUser;
use App\Livewire\Admin\Users\DetailUser;
use App\Livewire\Admin\Report\ReportIndex;
use App\Livewire\Admin\Service\ServiceEdit;
use App\Livewire\Customer\CustBookingIndex;
use App\Livewire\Admin\Service\ServiceIndex;
use App\Livewire\Customer\CustBookingCreate;
use App\Livewire\Customer\CustBookingDetail;
use App\Livewire\Admin\Bookings\BookingIndex;
use App\Livewire\Admin\Service\ServiceCreate;
use App\Livewire\Admin\Schedules\ScheduleEdit;
use App\Livewire\Admin\Service\CategoryCreate;
use App\Livewire\Admin\Schedules\ScheduleIndex;
use App\Livewire\Admin\Dashboard\DashboardIndex;
use App\Livewire\Admin\Schedules\ScheduleCreate;
use App\Livewire\Admin\Barbershops\BarbershopEdit;
use App\Livewire\Admin\Barbershops\BarbershopIndex;
use App\Livewire\Admin\Transactions\TransaksiIndex;
use App\Livewire\Admin\Barbershops\BarbershopCreate;
use App\Livewire\Admin\Transactions\TransaksiCreate;
use App\Livewire\Customer\Profile\Index;
use App\Livewire\Customer\Profile\Edit;

Route::get('/', Home::class)->name('home');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/customer/profile', Index::class)->name('customer.profile');
    Route::get('/customer/profile/edit', Edit::class)->name('customer.profileEdit');
    
    // Route ini hanya diakses oleh Customer (dan Admin, karena Admin punya semua permission)
    Route::middleware('permission:create booking')->group(function () {
        Route::get('/booking', CustBookingIndex::class)->name('customer.booking');
        Route::get('/booking/create', CustBookingCreate::class)->name('customer.bookingCreate');
        Route::get('/booking/detail/{id}', CustBookingDetail::class)->name('customer.bookingDetail');
    });

    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    
    // Hanya user dengan role 'admin'
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Users (memerlukan permission 'manage users', yang dimiliki oleh Admin)
        Route::get('/users', DataUser::class)->name('admin.users');
        Route::get('/users/create-user', CreateUser::class)->name('admin.userCreate');
        Route::get('/users/edit-user/{id}', EditUser::class)->name('admin.editUser');
        Route::get('/users/detail-user/{id}', DetailUser::class)->name('admin.detailUser');

        Route::get('/barbershops', BarbershopIndex::class)->name('admin.barbershop');
        Route::get('/barbershops/create', BarbershopCreate::class)->name('admin.barbershopCreate');
        Route::get('/barbershops/edit/{id}', BarbershopEdit::class)->name('admin.barbershopEdit');

        Route::get('/schedules', ScheduleIndex::class)->name('admin.schedule');
        Route::get('/schedules/create', ScheduleCreate::class)->name('admin.scheduleCreate');
        Route::get('/schedules/edit/{id}', ScheduleEdit::class)->name('admin.scheduleEdit');

        Route::get('/services', ServiceIndex::class)->name('admin.service');
        Route::get('/services/create', ServiceCreate::class)->name('admin.serviceCreate');
        Route::get('/services/edit/{id}', ServiceEdit::class)->name('admin.serviceEdit');

        Route::get('/services/category', Category::class)->name('admin.category');
        Route::get('/services/category-create', CategoryCreate::class)->name('admin.categoryCreate');
    });

  
    // BOOKING & TRANSAKSI (Akses penuh untuk Barber)
    Route::middleware('permission:manage bookings|manage transactions')->group(function () {
        Route::get('/bookings', BookingIndex::class)->name('staff.booking');

        Route::get('/transactions', TransaksiIndex::class)->name('staff.transactions');
        Route::get('/transactions/create', TransaksiCreate::class)->name('staff.transactionCreate');
    });

    // LAPORAN (Hanya melihat, diakses oleh Barber/Admin)
    Route::middleware('permission:manage reports')->group(function () {
        Route::get('/reports', ReportIndex::class)->name('staff.report');
        // Route::get('/admin/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export.pdf');

    });
    
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
