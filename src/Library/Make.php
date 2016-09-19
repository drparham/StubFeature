<?php namespace Drparham\StubFeature\Library;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;

abstract class Make extends Command
{
    /**
     * Store instance of Filesystem
     * @var Filesystem
     */
    protected $files;
    protected $composer;
    protected $type;
    protected $namespace;
    protected $name;
    protected $resource;

    /**
     * Make constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        $this->composer = app()['composer'];
    }

    final public function build($name, $namespace, $type, $resource)
    {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->type = $type;
        $this->resource = $resource;
        $this->make();
    }


    /**
     * Create Directory
     * @param $path
     */
    final protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0775, true, true);
        }
    }

    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    final protected function replaceClassName(&$stub)
    {
        $className = ucwords(camel_case($this->resource));
        $stub = str_replace('{{class}}', $className, $stub);
        return $this;
    }

    /**
     * Replace the Namespace in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    final protected function replaceNameSpace(&$stub)
    {
        $nameSpace = ucwords(camel_case($this->namespace));
        $stub = str_replace('{{NameSpace}}', $nameSpace, $stub);
        return $this;
    }

    /**
     * Get the class name for the Eloquent model generator.
     *
     * @return string
     */
    final protected function getModelName()
    {
        return ucwords(str_singular(camel_case($this->resource)));
    }

    /**
     * Get the class name for the generators.
     *
     * @return string
     */
    final protected function getClassName()
    {
        return ucwords(camel_case($this->resource));
    }

    /**
     * Get the path to where we should store the file.
     *
     * @param  string $name
     * @return string
     */
    abstract protected function getPath($name);

    /**
     * Create Stub
     * @return mixed
     */
    abstract protected function compileStub();

    /**
     * begin scaffolding files
     * @return mixed
     */
    abstract protected function make();


}