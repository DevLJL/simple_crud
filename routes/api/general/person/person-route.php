<?php

use App\Modules\General\Person\Controller\PersonController;
use Illuminate\Support\Facades\Route;

/**
 * Person (Pessoa)
 */
Route::group(
['prefix' => 'general', /*'middleware' => ['auth:sanctum']*/],
  function () {
    Route::get("persons",         [PersonController::class, 'index'])->name("general-persons.index");
    Route::post("persons",        [PersonController::class, 'store'])->name("general-persons.store");
    Route::get("persons/{id}",    [PersonController::class, 'show'])->name("general-persons.show");
    Route::put("persons/{id}",    [PersonController::class, 'update'])->name("general-persons.update");
    Route::delete("persons/{id}", [PersonController::class, 'destroy'])->name("general-persons.destroy");  
  }
);

