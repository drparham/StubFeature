<?php namespace Drparham\StubFeature\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Drparham\StubFeature\Library\MakeCommand;
use Drparham\StubFeature\Library\MakeConfig;
use Drparham\StubFeature\Library\MakeController;
use Drparham\StubFeature\Library\MakeFacade;
use Drparham\StubFeature\Library\MakeFormRequest;
use Drparham\StubFeature\Library\MakeLibrary;
use Drparham\StubFeature\Library\MakeMigration;
use Drparham\StubFeature\Library\MakeModel;
use Drparham\StubFeature\Library\MakePivot;
use Drparham\StubFeature\Library\MakeRepository;
use Drparham\StubFeature\Library\MakeRepositoryContract;
use Drparham\StubFeature\Library\MakeRoutes;
use Drparham\StubFeature\Library\MakeServiceProvider;
use Drparham\StubFeature\Library\MakeTest;
use Drparham\StubFeature\Library\MakeView;

class FeatureMakeCommand extends Command
{
    protected $signature = 'make:feature {name : The Name of the Feature ex. Blog} {namespace : Namespace for Application}';
    protected $description = 'Create a new Feature for a Laravel App';
    protected $meta;
    protected $form;
    protected $resource = [];
    protected $files;
    protected $name;
    protected $namespace;
    protected $bar;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    protected function handle()
    {
        $this->name = $this->argument('name');
        $this->namespace = $this->argument('namespace');
        $this->form = $this->choice('Will this be a package or a feature?', ['Package', 'Feature'], 'Feature');
        $this->makeResource();
    }

    protected function makeResource()
    {
        $this->resource[] = $this->ask('What\'s the name of the resource we want to build?');
        $this->info('You\'ll be asked a series of questions while we build out your resource');
        $this->bar = $this->output->createProgressBar();
        $this->bar->start();

        $this->makeMigration();
        $this->makeModel();
        $this->makeRepositoryContract();
        $this->makeLibrary();
        $this->makeController();
        $this->makeCommand();

        if(count($this->resource) > 1){
            $this->makePivot();
        }

        if(strtolower($this->form) === 'package'){
            $this->makeServiceProvider();
            $this->makeConfig();
        }

        if($this->confirm('Do you want to build another resource? [y|N]')){
            $this->makeResource();
        }
        $this->bar->finish();
    }

    protected function makeMigration()
    {
        if($this->confirm('Do you want to build migrations? [y|N]')){
            $makeMigration = new MakeMigration($this->files);
            $makeMigration->build($this->name, $this->namespace, $this->form, $this->resource);
        }
        $this->bar->advance();
    }

    protected function makeModel(){
        if($this->confirm('Do you want to build a model? [y|N]')){
            $makeModel = new MakeModel($this->files);
            $makeModel->build($this->name, $this->namespace, $this->form, $this->resource);
        }
        $this->bar->advance();
    }

    protected function makePivot()
    {
        if($this->confirm('Do you want to build a pivot table? [y|N]')){
            $makeModel = new MakeModel($this->files);
            $makeModel->build($this->name, $this->namespace, $this->form, $this->resource);
        }
        $this->bar->advance();
    }

    protected function makeRepositoryContract()
    {
        if($this->confirm('Do you want to build a repository? [y|N]')){
            $makeRepositoryContract = new MakeRepositoryContract($this->files);
            $makeRepositoryContract->build($this->name, $this->namespace, $this->form, $this->resource);
            $this->makeRepository();
        }
        $this->bar->advance();
    }

    protected function makeRepository()
    {
        $makeRepository = new MakeRepository($this->files);
        $makeRepository->build($this->name, $this->namespace, $this->form, $this->resource);
        $this->bar->advance();
    }

    protected function makeLibrary()
    {
        if($this->confirm('Do you want to build a library class? [y|N]')){
            $makeLibrary = new MakeLibrary($this->files);
            $makeLibrary->build($this->name, $this->namespace, $this->form, $this->resource);
            $this->makeFacade();
            $this->makeTest();
        }
        $this->bar->advance();
    }

    protected function makeFacade()
    {
        //If we built a Library prompt for a Facade
        if($this->confirm('Do you want to build a Facade for your library? [y|N]')){
            $makeFacade = new MakeFacade($this->files);
            $makeFacade->build($this->name, $this->namespace, $this->form, $this->resource);
        }
        $this->bar->advance();

    }

    protected function makeTest()
    {
        //if we built a Library prompt for a test
        if($this->confirm('Do you want to build a test for your library? [y|N]')){
            $makeTest = new MakeTest($this->files);
            $makeTest->build($this->name, $this->namespace, $this->form, $this->resource);
        }
        $this->bar->advance();
    }

    protected function makeController()
    {
        if($this->confirm('Do you want to build a Controller? [y|N]')){
            $makeController = new MakeController($this->files);
            $makeController->build($this->name, $this->namespace, $this->form, $this->resource);
            $this->makeRoute();
            $this->makeViews();
        }
        $this->bar->advance();
    }

    protected function makeViews()
    {
        if($this->confirm('Do you want to build CRUD Views? [y|N]')){
            $makeViews = new MakeView($this->files);
            $makeViews->build($this->name, $this->namespace, $this->form, $this->resource);
        }
    }

    protected function makeRoute()
    {
        if($this->confirm('Do you want to build CRUD routes? [y|N]')){
            $makeRoute = new MakeRoutes($this->files);
            $makeRoute->build($this->name, $this->namespace, $this->form, $this->resource);
        }
        $this->bar->advance();
    }

    protected function makeCommand()
    {
        if($this->confirm('Do you want to build a command? [y|N]')){
            $makeCommand = new MakeCommand($this->files);
            $makeCommand->build($this->name, $this->namespace, $this->form, $this->resource);
        }
        $this->bar->advance();
    }

    protected function makeServiceProvider()
    {
        //If they chose a package, offer to build a serviceProvider
        $serviceProvider = $this->ask('Do you want to build a Service Provider for our Package? (Y/n)');
        if($this->confirm('Do you want to build a pivot table? [y|N]')){
            $makeServiceProvider = new MakeServiceProvider($this->files);
            $makeServiceProvider->build($this->name, $this->namespace, $this->form, $this->resource);
        }
        $this->bar->advance();
    }

    protected function makeConfig()
    {
        //if they chose a package, offer to build a config file
        if($this->confirm('Do you want to build a config file? [y|N]')){
            $makeConfig = new MakeConfig($this->files);
            $makeConfig->build($this->name, $this->namespace, $this->form, $this->resource);
        }
        $this->bar->advance();
    }





}