<?php

namespace App\Traits\Actions;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;

trait WithQueryBuilder
{
    /**
     * Initialize QueryBuilder to handle request's parameters from external input.
     *
     * @param string $class
     * @param array $request
     *
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function getQueryBuilder(string $class, array $request = []): QueryBuilder
    {
        $request = app(QueryBuilderRequest::class)->merge($request);
        $builder = QueryBuilder::for($class, $request);

        if (method_exists($this, 'getFields') && $this->getFields()) {
            $builder->allowedFields($this->getFields());
        }

        if (method_exists($this, 'getFilters') && $this->getFilters()) {
            $builder->allowedFilters($this->getFilters());
        }

        if (method_exists($this, 'getIncludes') && $this->getIncludes()) {
            $builder->allowedIncludes($this->getIncludes());
        }

        if (method_exists($this, 'getSorts') && $this->getSorts()) {
            $builder->allowedSorts($this->getSorts());
        }

        return $builder;
    }
}
