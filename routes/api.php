<?php

use App\Actions\Authentication\ForgotPassword;
use App\Actions\Authentication\Login;
use App\Actions\Authentication\Register;
use App\Actions\Invoice\CreateInvoice;
use App\Actions\Invoice\DeleteInvoice;
use App\Actions\Invoice\DownloadInvoice;
use App\Actions\Invoice\GetInvoices;
use App\Actions\Maintenance\CreateMaintenance;
use App\Actions\Maintenance\DeleteMaintenance;
use App\Actions\Maintenance\GetMaintenances;
use App\Actions\Maintenance\UpdateMaintenance;
use App\Actions\User\GetCurrentUser;
use App\Actions\User\ResetPassword;
use App\Actions\User\UpdatePassword;
use App\Actions\User\UpdateProfile;
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
    Route::prefix('auth')->group(function () {
        Route::post('/register', Register::class)
            ->name('register');
        Route::post('/login', Login::class)
            ->name('login');
    });

    /**
     * USERS
     */
    Route::prefix('users')->group(function () {
        Route::post('/forgot-password', ForgotPassword::class)
            ->name('password.forgot');
        Route::post('/reset-password', ResetPassword::class)
            ->name('password.reset');
    });

    /**
     * AUTH USER ONLY
     */
    Route::middleware('auth:sanctum')->group(function () {
        /**
         * USERS
         */
        Route::prefix('users/me')->group(function () {
            Route::get('/', GetCurrentUser::class)
                ->name('user.me');
            Route::patch('/', UpdateProfile::class)
                ->name('user.update');
            Route::post('/update-password', UpdatePassword::class)
                ->name('user.update-password');
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

                        /**
                         * INVOICES
                         */
                        Route::prefix('invoices')->group(function () {
                            Route::get('/', GetInvoices::class)
                                ->name('invoice.index');
                            Route::post('/', CreateInvoice::class)
                                ->name('invoice.store');

                            Route::prefix('{invoice:uuid}')->group(function () {
                                Route::get('/', GetInvoices::class)
                                    ->name('invoice.show');
                                Route::delete('/', DeleteInvoice::class)
                                    ->name('invoice.destroy');
                                Route::get('/download', DownloadInvoice::class)
                                    ->name('invoice.download');
                            });
                        });
                    });
                });
            });
        });
    });
});
