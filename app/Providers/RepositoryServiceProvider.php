<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\LabelRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\StatusRepositoryInterface;
use App\Interfaces\PriorityRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\TicketRepositoryInterface;
use App\Interfaces\CommentRepositoryInterface;
use App\Repositories\Eloquent\TicketRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\StatusRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\PriorityRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\LabelRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * List of repository bindings.
     */
    protected $repositories = [
        LabelRepositoryInterface::class => LabelRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        CategoryRepositoryInterface::class => CategoryRepository::class,
        StatusRepositoryInterface::class => StatusRepository::class,
        PriorityRepositoryInterface::class => PriorityRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        TicketRepositoryInterface::class => TicketRepository::class,
        CommentRepositoryInterface::class => CommentRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
