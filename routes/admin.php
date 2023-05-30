<?php

use App\Actions\Admin\Auth\GetLoginAction;
use App\Actions\Admin\Auth\LoginAction;
use App\Actions\Admin\Contact\GetAllContactAction;
use App\Actions\Admin\Dashboard\DashboardAction;
use App\Actions\Admin\Events\AddEventDocAction;
use App\Actions\Admin\Events\EventDetailAction;
use App\Actions\Admin\Events\EventsAction;
use App\Actions\Admin\Events\EventStatusAction;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

//Route::get('/', function () {
//    return view('welcome');
//});

Route::group(['middleware' => ['guest']], function () {
    Route::get('/', GetLoginAction::class)->name('login.view');
    Route::post('login', LoginAction::class)->name('login');
});
Route::group(['prefix' => '', 'middleware' => ['auth']], function () {
//    events
    Route::get('/dashboard', DashboardAction::class)->name('dashboard');
    Route::get('/events', EventsAction::class)->name('events');
    Route::get('/event/{id}', EventDetailAction::class)->name('event.detail');
    Route::post('/event/status', EventStatusAction::class)->name('event.status');
    Route::post('/add/event/doc', AddEventDocAction::class)->name('add.event.doc');

//    flights
    Route::get('flights',\App\Actions\Admin\Flight\FlightAction::class)->name('flights');

//  contact us
    Route::get('/contact-us', GetAllContactAction::class)->name('contact-us');
    Route::get('logout', function () {
        auth()->logout();
        return redirect('/admin');
    })->name('logout');

});
