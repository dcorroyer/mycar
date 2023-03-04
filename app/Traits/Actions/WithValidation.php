<?php

namespace App\Traits\Actions;

use Illuminate\Support\Facades\Validator;

trait WithValidation
{
    /**
     * @var array $attributes
     */
    protected array $attributes = [];

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function fill(array $attributes): static
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * @return array
     */
    public function validated(): array
    {
        if (!method_exists($this, 'rules')) {
            return $this->attributes;
        }

        return Validator::validate($this->attributes, $this->rules());
    }

    /**
     * Merge $rules parameter with rules of the $class.
     * Each entry must have as key model and as value the class.
     *
     * @param array $rules
     * @param array $classes
     *
     * @return array
     */
    public function mergeWithRulesAction(array $rules, array $classes): array
    {
        $mergedRules = [];

        foreach ($classes as $prefix => $class) {
            if (!method_exists($class, 'rules')) {
                continue;
            }

            $classRules = app($class)->rules();
            $mergedRules = array_merge(
                $mergedRules,
                array_combine(
                    array_map(fn ($attr) => $prefix . '.' . $attr, array_keys($classRules)),
                    $classRules
                )
            );
        }

        return array_merge(
            $rules,
            $mergedRules
        );
    }
}
