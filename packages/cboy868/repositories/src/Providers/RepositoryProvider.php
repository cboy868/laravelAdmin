<?php
namespace Cboy868\Repositories\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:11
 */

class RepositoryProvider extends ServiceProvider
{
    /**
     * @var bool 延迟加载
     */
    protected $defer = true;

    public function boot()
    {
        // Config path.
        $config_path = __DIR__ . '/../../../config/repositories.php';
        // Publish config.
        $this->publishes(
            [$config_path => config_path('repositories.php')],
            'repositories'
        );
    }


    public function register()
    {
        // Register bindings.
        $this->registerBindings();
        // Register make repository command.
        $this->registerMakeRepositoryCommand();
        // Register make criteria command.
        $this->registerMakeCriteriaCommand();
        // Register commands
        $this->commands(['command.repository.make', 'command.criteria.make']);
        // Config path.
        $config_path = __DIR__ . '/../../../config/repositories.php';
        // Merge config.
        $this->mergeConfigFrom(
            $config_path,
            'repositories'
        );
    }


    protected function registerBindings()
    {
        // FileSystem.
        $this->app->instance('FileSystem', new Filesystem());
        // Composer.
        $this->app->bind('Composer', function ($app)
        {
            return new Composer($app['FileSystem']);
        });
        // Repository creator.
        $this->app->singleton('RepositoryCreator', function ($app)
        {
            return new RepositoryCreator($app['FileSystem']);
        });
        // Criteria creator.
        $this->app->singleton('CriteriaCreator', function ($app)
        {
            return new CriteriaCreator($app['FileSystem']);
        });
    }

    /**
     * Register the make:repository command.
     */
    protected function registerMakeRepositoryCommand()
    {
        // Make repository command.
        $this->app['command.repository.make'] = $this->app->share(
            function($app)
            {
                return new MakeRepositoryCommand($app['RepositoryCreator'], $app['Composer']);
            }
        );
    }
    /**
     * Register the make:criteria command.
     */
    protected function registerMakeCriteriaCommand()
    {
        // Make criteria command.
        $this->app['command.criteria.make'] = $this->app->share(
            function($app)
            {
                return new MakeCriteriaCommand($app['CriteriaCreator'], $app['Composer']);
            }
        );
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.repository.make',
            'command.criteria.make'
        ];
    }
}