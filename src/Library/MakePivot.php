<?php namespace Drparham\StubFeature\Library;


class MakePivot extends Make
{
    /**
     * begin scaffolding files
     * @return mixed
     */
    protected function make()
    {
        if ($this->files->exists($path = $this->getPath($this->getPivotTableName()))) {
            return $this->error($this->type . ' already exists!');
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->compileStub());
        $this->info('Pivot created successfully.');
        $this->composer->dumpAutoloads();
    }

    /**
     * Create Stub
     * @return mixed
     */
    protected function compileStub()
    {
        $stub = $this->files->get($this->getStub());
        return $this->replacePivotTableName($stub)
            ->replaceSchema($stub)
            ->replaceNameSpace($stub)
            ->replaceClassName($stub);
    }

    /**
     * Get the path to where we should store the file.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        return base_path() . '/database/migrations/' . date('Y_m_d_His') .
        '_create_' . $name . '_pivot_table.php';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->files->get( __DIR__ . '/../stubs/pivot.stub');
    }

    /**
     * Parse the name and format.
     *
     * @param  string $name
     * @return string
     */
    protected function parseName($name)
    {
        $tables = array_map('str_singular', $this->getSortedTableNames());
        $name = implode('', array_map('ucwords', $tables));
        return "Create{$name}PivotTable";
    }


    /**
     * Apply the name of the pivot table to the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replacePivotTableName(&$stub)
    {
        $stub = str_replace('{{pivotTableName}}', $this->getPivotTableName(), $stub);
        return $this;
    }

    /**
     * Apply the correct schema to the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceSchema(&$stub)
    {
        $tables = $this->getSortedTableNames();
        $stub = str_replace(
            ['{{columnOne}}', '{{columnTwo}}', '{{tableOne}}', '{{tableTwo}}'],
            array_merge(array_map('str_singular', $tables), $tables),
            $stub
        );
        return $this;
    }

    /**
     * Sort the two tables in alphabetical order.
     *
     * @return array
     */
    protected function getSortedTableNames()
    {
        $tables = [
            strtolower($this->options['tableOne']),
            strtolower($this->options['tableTwo'])
        ];
        sort($tables);
        return $tables;
    }

    /**
     * Get the name of the pivot table.
     *
     * @return string
     */
    protected function getPivotTableName()
    {
        return implode('_', array_map('str_singular', $this->getSortedTableNames()));
    }
}