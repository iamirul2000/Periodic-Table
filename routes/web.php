<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});

// Route::get('/periodic-table', function () {
//     return view('periodic_table');
// });

// Redirect to Google login
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

// Handle Google callback
Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    // Find or create a user in your database
    $user = User::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ]
    );

    // Log in the user
    Auth::login($user);

    // Redirect to the periodic table page
    return redirect('/periodic-table');
});

// Protected route for periodic table page
Route::get('/periodic-table', function () {
    if (Auth::check()) {
        return view('periodic_table');
    }
    return redirect('/auth/google');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
});