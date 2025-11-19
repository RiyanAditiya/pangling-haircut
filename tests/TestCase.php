<?php

namespace Tests;

// Import Trait yang diperlukan untuk Feature Testing
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase; // ⭐ BARU: Untuk reset database antar tes

abstract class TestCase extends BaseTestCase
{
   use RefreshDatabase; 
    protected bool $seed = true; // ⭐ Pastikan ini TRUE
    protected string $seeder = \Database\Seeders\DatabaseSeeder::class;
}