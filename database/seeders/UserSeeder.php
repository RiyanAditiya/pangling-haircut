<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan Cache Permission Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // DEFINISI DAN BUAT PERMISSIONS
        $permissions = [
            'manage users', 'manage reports', 'manage barbershops', 'manage schedules', 'manage services',
            'manage bookings', 'manage transactions', 'view reports',
            'create booking',  
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // DEFINISI DAN KAITKAN ROLES (Role memiliki Permission)
        
        // --- ADMIN ROLE ---
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions([
            'manage users', 
            'manage reports', 
            'manage barbershops', 
            'manage schedules', 
            'manage services',
        ]); 

        // --- BARBER ROLE ---
        $roleBarber = Role::firstOrCreate(['name' => 'barber']);
        $roleBarber->syncPermissions([
            'manage bookings', 
            'manage transactions', 
            'manage reports',
        ]);

        // --- CUSTOMER ROLE ---
        $roleCustomer = Role::firstOrCreate(['name' => 'customer']);
        $roleCustomer->syncPermissions([
            'create booking',
        ]);


        // 4. BUAT DAN ASSIGN USER (TIDAK ADA LAGI FIELD 'role' di array User::create)
        $defaultPassword = Hash::make('password123');

        $usersData = [
            ['name' => 'Admin', 'email' => 'admin@gmail.com', 'role' => 'admin'],
            ['name' => 'Wawan', 'email' => 'wawan@gmail.com', 'role' => 'barber'],
            ['name' => 'Widi', 'email' => 'widi@gmail.com', 'role' => 'barber'],
            ['name' => 'Fitron', 'email' => 'fitron@gmail.com', 'role' => 'barber'],
            ['name' => 'Bayu', 'email' => 'bayu@gmail.com', 'role' => 'barber'],
            ['name' => 'Fajar', 'email' => 'fajar@gmail.com', 'role' => 'barber'],
            ['name' => 'Riyan', 'email' => 'riyan@gmail.com', 'role' => 'customer'],
        ];

        foreach ($usersData as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            // ✅ Solusi: Gunakan assignRole() dari Spatie, BUKAN field 'role' di tabel user.
            $user->assignRole($userData['role']);
        }
    }
}
