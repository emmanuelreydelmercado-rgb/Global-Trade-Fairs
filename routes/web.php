<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Form;

use App\Http\Controllers\InputBoxController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| HOME → Global Fairs Page
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| EVENT FORM
|--------------------------------------------------------------------------
*/
Route::get('/event-form', [InputBoxController::class, 'viewform'])
    ->name('event.form');

Route::post('/event-form', [InputBoxController::class, 'addtable'])
    ->name('event.submit');

// API endpoint for getting cities by country
Route::get('/get-cities/{country}', [InputBoxController::class, 'getCities'])
    ->name('get.cities');





/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // -------------------------
    // DASHBOARDS
    // -------------------------
    Route::get('/admin/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard.old');

    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');


    // -------------------------
    // PROFILE (FIXED & WORKING)
    // -------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


    // -------------------------
    // APPROVE / REJECT (SECURED)
    // -------------------------
    Route::get('/approve/{id}', function ($id) {
        Form::where('id', $id)->update(['status' => 'approved']);
        
        // 🚀 Clear map analytics cache when event is approved
        \Cache::forget('map-analytics-data');
        
        return back()->with('success', 'Approved successfully');
    })->name('approve.form');

    Route::get('/reject/{id}', function ($id) {
        Form::where('id', $id)->update(['status' => 'rejected']);
        
        // 🚀 Clear map analytics cache when event is rejected
        \Cache::forget('map-analytics-data');
        
        return back()->with('success', 'Rejected successfully');
    })->name('disapprove.form');

    // -------------------------
    // EDIT / UPDATE FORM
    // -------------------------
    Route::get('/form/{id}/edit', function ($id) {
        $form =Form::findOrFail($id);
        return view('event-form', compact('form'));
    })->name('form.edit');

    Route::put('/form/{id}', function (Request $request, $id) {
        $form = Form::findOrFail($id);
        $form->update($request->only(['Orgname', 'VenueName', 'city', 'country', 'Date', 'ExponName', 'phone', 'email', 'hallno', 'reglink']));
        
        // 🚀 Clear map analytics cache when event is updated
        \Cache::forget('map-analytics-data');
        
        return redirect()->route('admin.dashboard')->with('success', 'Event updated successfully');
    })->name('form.update');
});


/*
|--------------------------------------------------------------------------
| FAIR DETAILS
|--------------------------------------------------------------------------
*/
Route::get('/fair/{id}', function ($id) {
    $form = Form::findOrFail($id);
    return view('fair-details', compact('form'));
})->name('fair.details');

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\AdminLoginController;

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin/login', [AdminLoginController::class, 'login'])
    ->name('admin.login.submit');

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD (Email-restricted)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin.email'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');

});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| REDIS TEST ROUTE (Development Only)
|--------------------------------------------------------------------------
*/
Route::get('/test-redis', function() {
    try {
        // Test 1: Cache
        Cache::put('test-key', 'Redis is working! ✅', 600);
        $cached = Cache::get('test-key');
        
        // Test 2: Direct Redis
        Redis::set('direct-test', 'Direct Redis works! 🚀');
        $direct = Redis::get('direct-test');
        
        // Test 3: Performance
        $start = microtime(true);
        for ($i = 0; $i < 100; $i++) {
            Cache::put("perf-test-$i", "value-$i", 60);
            Cache::get("perf-test-$i");
        }
        $time = round((microtime(true) - $start) * 1000, 2);
        
        return response()->json([
            'status' => '✅ SUCCESS',
            'driver' => config('cache.default'),
            'cache_test' => $cached,
            'redis_test' => $direct,
            'performance' => $time . 'ms for 100 operations',
            'message' => 'Redis is configured correctly!',
            'redis_info' => [
                'client' => config('database.redis.client'),
                'host' => config('database.redis.default.host'),
                'port' => config('database.redis.default.port'),
            ]
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => '❌ ERROR',
            'driver' => config('cache.default'),
            'error' => $e->getMessage(),
            'solution' => 'Make sure Redis/Memurai is running and CACHE_DRIVER is set in .env'
        ], 500);
    }
});



