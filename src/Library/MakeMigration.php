<?php namespace Drparham\StubFeature\Library;


class MakeMigration extends Make
{
    protected $table;
    protected $schema;
    protected $meta;

    protected function getPath($name)
    {
        return base_path() . '/database/migrations/' . date('Y_m_d_His') . '_' . $name . '.php';
    }

    protected function make()
    {
        $this->schema = $this->options['schema'];
        $this->meta = $this->options['meta'];

        if ($this->files->exists($path = $this->getPath($this->name))) {
            return $this->error($this->type . ' already exists!');
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->compileStub());
        $this->info('Migration created successfully.');
        $this->composer->dumpAutoloads();
    }

    protected function compileStub()
    {
        $stub = $this->getStub();
        $this->replaceClassName($stub)
            ->replaceNameSpace($stub)
            ->replaceSchema($stub)
            ->replaceTableName($stub);
        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->files->get(__DIR__ . '/../Stubs/migration.stub');
    }

    /**
     * Replace the schema for the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceSchema(&$stub)
    {
        if ($this->schema) {
            $this->schema = (new SchemaParser)->parse($this->schema);
        }
        $schema = (new SyntaxBuilder)->create($this->schema, $this->meta);
        $stub = str_replace(['{{schema_up}}', '{{schema_down}}'], $schema, $stub);
        return $this;
    }

    /**
     * Replace the table name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceTableName(&$stub)
    {
        $table = $this->table;
        $stub = str_replace('{{table}}', $table, $stub);
        return $this;
    }


}