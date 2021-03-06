<?php

namespace Factories;

use Contracts\Factories\FactoryContract;
use Faker\Factory;

class ApiError implements FactoryContract
{
    /**
     * Construct the ApiError.
     *
     * @param Factory $faker
     */
    public function __construct(Factory $faker)
    {
        $this->faker = $faker->create();
    }

    /**
     * {@inheritdoc}
     */
    public function create($limit = 1)
    {
        for ($i = 1; $i <= $limit; $i++) {
            $data[$i] = [
                'error' => [
                    'message' => $this->faker->sentence,
                ],
            ];
        }

        if ($limit == 1) {
            return current($data);
        }

        return $data;
    }
}
