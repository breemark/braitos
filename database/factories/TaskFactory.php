<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(4),
        'content' => $faker->paragraphs(3, true),
        'finished' => $faker->boolean,
        'deadline' => $faker->dateTime(),
        'project_id' => factory(\App\Project::class),
    ];
});
