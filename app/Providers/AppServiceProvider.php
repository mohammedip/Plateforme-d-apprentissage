<?php

namespace App\Providers;

use App\Repositories\TagRepository;
use App\Repositories\CoursRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepositoryInterface;
use App\Repositories\CoursRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;

 /**
 * @OA\Info(
 *     version="1.0",
 *     title="REST API E-Learning Platform"
 * )
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CoursRepositoryInterface::class, CoursRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
