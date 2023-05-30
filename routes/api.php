<?php

use App\Models\Event;
use App\Models\Calendar;
use App\Jobs\SyncEventsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Api\Event\GetEventAction;
use App\Actions\Api\Event\CreateEventAction;
use App\Actions\Api\Event\DeleteEventAction;
use App\Actions\Api\Event\UpdateEventAction;
use App\Actions\Api\Event\GetAllEventsAction;
use App\Services\Google\GoogleCalendarService;
use App\Jobs\Google\AddEventsToGoogleCalendarJob;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function (Request $request) {
    $service = new GoogleCalendarService(config('calendar.id'));
    return [
        $service->createEvent("Test", "testing", "2023-05-01T09:00:00-04:00", "2023-05-01T10:00:00-04:00", "test", "test", "test@test.com", "123"),
    ];
});

Route::group(['prefix' => '/google/calendar/{calendar}/'], function () {
    Route::get('/events', GetAllEventsAction::class);
    Route::post('/events', CreateEventAction::class);
    Route::get('/events/{event}', GetEventAction::class);
    Route::put('/events/{event}', UpdateEventAction::class);
    Route::delete('/events/{event}', DeleteEventAction::class);
});

Route::get('google/calendar/sync', function (Request $request) {
    $service = new GoogleCalendarService(config('calendar.id'));
    $calendar = Calendar::first();
    return [
        $service->incrementalSync($calendar->sync_token),
        // $service->getEvents(),
    ];
});
Route::post('contact', \App\Actions\Api\Contact\ContactAction::class);
Route::post('event/slots', \App\Actions\Api\Event\GetAllEventsSlotAction::class);
Route::post('flight', \App\Actions\Api\Flight\CreateFlightAction::class);

Route::get('test/job', function (Request $request) {
    SyncEventsJob::dispatch(Calendar::first())->onQueue('events_sync');
});

// stripe token get for testing purpose
Route::get('get/stripe/token', \App\Actions\Api\Event\GetStripeTokenAction::class);
