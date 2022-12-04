<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\{
    CategoryRepositoryInterface,
    SubcategoryRepositoryInterface,
    ExpenseRepositoryInterface,
    IncomeRepositoryInterface,
    DashboardRepositoryInterface,
    UserRepositoryInterface,
    RecurringExpenseRepositoryInterface,
};

use App\Repositories\{
    CategoryRepository,
    SubcategoryRepository,
    ExpenseRepository,
    IncomeRepository,
    DashboardRepository,
    UserRepository,
    RecurringExpenseRepository,
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            SubcategoryRepositoryInterface::class,
            SubcategoryRepository::class
        );

        $this->app->bind(
            ExpenseRepositoryInterface::class,
            ExpenseRepository::class
        );

        $this->app->bind(
            IncomeRepositoryInterface::class,
            IncomeRepository::class
        );

        $this->app->bind(
            DashboardRepositoryInterface::class,
            DashboardRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            RecurringExpenseRepositoryInterface::class,
            RecurringExpenseRepository::class
        );
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
