<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';
    
    public function setUp()
    {
        parent::setUp();
        Config::set('database.default', 'kt_unit_testing');
        Artisan::call('migrate:reset');
        Artisan::call('migrate');
    }

    public function tearDown()
    {
        #Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
