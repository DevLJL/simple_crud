<?php

namespace App\Modules\General\Person\Provider;

use App\Modules\General\Person\Repository\Eloquent\PersonRepositoryEloquent;
use App\Modules\General\Person\Repository\Eloquent\Model\PersonModelEloquent;
use App\Modules\General\Person\Repository\PersonRepositoryInterface;
use App\Shared\Repository\RepositoryTypeEnum;
use Illuminate\Support\ServiceProvider;

class PersonProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    // Instanciar repositório
    $type = RepositoryTypeEnum::from(env('DB_REPOSITORY', 'eloquent'));
    match ($type) {
      RepositoryTypeEnum::ELOQUENT => $this->app->bind(
        PersonRepositoryInterface::class, 
        fn () => new PersonRepositoryEloquent(new PersonModelEloquent())
      ),
      //RepositoryTypeEnum::DOCTRINE   => $this->app->bind(
      //  PersonRepositoryInterface::class, 
      //  fn () => new PersonRepositoryDoctrine(new PersonEntityDoctrine())
      //),
      //RepositoryTypeEnum::PDO   => $this->app->bind(
      //  PersonRepositoryInterface::class, 
      //  fn () => new PersonRepositoryPDO()
      //),
      //RepositoryTypeEnum::MEMORY   => $this->app->bind(
      //  PersonRepositoryInterface::class, 
      //  fn () => new PersonRepositoryMemory()
      //),
      //RepositoryTypeEnum::OTHER    => null,
    };
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }
}
