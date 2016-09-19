<?php namespace Drparham\StubFeature\Commands;

use Drparham\StubFeature\Library\MakeMigration;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class FeatureMakeCommand extends Command
{
    protected $signature = 'make:feature {name : The Name of the Feature ex. Blog} {namespace : Namespace for Application}';
    protected $description = 'Create a new Feature for a Laravel App';
    protected $meta;
    protected $type;
    protected $resource;

    protected function handle()
    {
        $this->name = $this->argument('name');
        $this->namespace = $this->argument('namespace');
        $this->type = $this->ask('Will this be a package or a feature? (package/feature)');
        $this->makeResource();
    }

    protected function makeResource()
    {
        $this->resource = $this->ask('What\'s the name of the first resource we want to build?');
        $this->makeMigration();
        $this->makeModel();
        $this->makeRepository();
        $this->makeLibrary();
        $this->makeFacade();


        if($this->type == 'package'){
            $this->makeServiceProvider();
        }
    }

    protected function makeMigration()
    {
        $migration = $this->ask('Do you want to build migrations? (Y/n)');

        $makeMigration = new MakeMigration(new Filesystem());
        $makeMigration->build($this->name, $this->namespace, $this->type, $this->resource);

    }

    protected function makeModel(){
        $model = $this->ask('Do you want to build a model?  (Y/n)');

    }

    protected function makePivot()
    {
        $pivot = $this->ask('Do you want to build a pivot table? (Y/n)');
    }

    protected function makeRepository()
    {
        $repository = $this->ask('Do you want to build a repository? (Y/n)');
    }

    protected function makeLibrary()
    {
        $library = $this->ask('Do you want to build a library class? (Y/n)');
    }

    protected function makeFacade()
    {
        //If we built a Library prompt for a Facade
        $facade = $this->ask('Do you want to build a Facade for your library? (Y/n)');
    }

    protected function makeTest()
    {
        //if we built a Library prompt for a test
        $test = $this->ask('Do you want to build a test for your library? (Y/n)');
    }

    protected function makeServiceProvider()
    {
        //If they chose a package, offer to build a serviceProvider
        $serviceProvider = $this->ask('Do you want to build a Service Provider for our Package? (Y/n)');
    }

    protected function makeConfig()
    {
        //if they chose a packge, offer to build a config file
        $config = $this->ask('Do you want to build a config file? (Y/n)');
    }
    protected function makeCommand()
    {
        $command = $this->ask('Do you want to build a command? (Y/n)');
    }

    protected function makeController()
    {
        $controller = $this->ask('Do you want to build a Controller? (Y/n)');
    }

    protected function makeViews()
    {
        $view = $this->ask('Do you want to build CRUD Views? (Y/n)');
    }

    protected function makeRoute()
    {
        $route = $this->ask('Do you want to build CRUD routes? (Y/n)');
    }






}