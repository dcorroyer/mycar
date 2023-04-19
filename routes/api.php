<?php

use App\Actions\Authentication\Login;
use App\Actions\Authentication\Register;
use App\Actions\Maintenance\CreateMaintenance;
use App\Actions\Maintenance\DeleteMaintenance;
use App\Actions\Maintenance\GetMaintenances;
use App\Actions\Maintenance\UpdateMaintenance;
use App\Actions\User\GetCurrentUser;
use App\Actions\Vehicule\CreateVehicule;
use App\Actions\Vehicule\DeleteVehicule;
use App\Actions\Vehicule\GetVehicules;
use App\Actions\Vehicule\UpdateVehicule;
use App\Http\Middleware\ForceAcceptHeader;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;

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

$middlewares = [
    ForceAcceptHeader::class,
    SubstituteBindings::class,
];

Route::prefix('/')->middleware($middlewares)->group(function () {
    /**
     * AUTHENTICATION
     */
    Route::post('/auth/register', Register::class)
        ->name('register');
    Route::post('/auth/login', Login::class)
        ->name('login');

    /**
     * AUTH USER ONLY
     */
    Route::middleware('auth:sanctum')->group(function () {
        /**
         * USERS
         */
        Route::prefix('users')->group(function () {
            Route::get('/me', GetCurrentUser::class)
                ->name('user.me');
        });

        /**
         * VEHICULES
         */
        Route::prefix('vehicules')->group(function () {
            Route::get('/', GetVehicules::class)
                ->name('vehicule.index');
            Route::post('/', CreateVehicule::class)
                ->name('vehicule.store');

            Route::prefix('{vehicule:uuid}')->group(function () {
                Route::get('/', GetVehicules::class)
                    ->name('vehicule.show');
                Route::patch('/', UpdateVehicule::class)
                    ->name('vehicule.update');
                Route::delete('/', DeleteVehicule::class)
                    ->name('vehicule.destroy');

                /**
                 * MAINTENANCES
                 */
                Route::prefix('maintenances')->group(function () {
                    Route::get('/', GetMaintenances::class)
                        ->name('maintenance.index');
                    Route::post('/', CreateMaintenance::class)
                        ->name('maintenance.store');

                    Route::prefix('{maintenance:uuid}')->group(function () {
                        Route::get('/', GetMaintenances::class)
                            ->name('maintenance.show');
                        Route::patch('/', UpdateMaintenance::class)
                            ->name('maintenance.update');
                        Route::delete('/', DeleteMaintenance::class)
                            ->name('maintenance.destroy');
                    });
                });
            });
        });
    });
});
