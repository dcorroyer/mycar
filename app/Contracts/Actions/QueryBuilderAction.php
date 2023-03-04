<?php

namespace App\Contracts\Actions;

interface QueryBuilderAction
{
    /**
     * Define which fields can be requested ([] = all fields).
     * An InvalidFieldQuery exception will be thrown if field is not specified.
     *
     * @return array
     */
    public function getFields(): array;

    /**
     * Define which attributes can be used to add where clause to the request ([] = all attributes).
     * An InvalidFilterQuery exception will be thrown if filter is not specified.
     *
     * @return array
     */
    public function getFilters(): array;

    /**
     * Define which relationships can be included ([] = all relationships).
     * An InvalidIncludeQuery exception will be thrown if relationship is not allowed.
     *
     * @return array
     */
    public function getIncludes(): array;

    /**
     * Define which property can be used to sort the results ([] = all properties).
     * An InvalidSortQuery exception will be thrown if field is not specified.
     *
     * @return array
     */
    public function getSorts(): array;
}
