<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');
Artisan::command('foo', function () {
    echo 'salam foo';
})->describe('for test commend');



const STUDENTS_CACHE_KEY = 'STUDENTS_CACHE_KEY';

Artisan::command('student:list {--t|type=table : can either be table or array} {--l|limit=10 : limit output count}', function () {
    $students = Cache::get(STUDENTS_CACHE_KEY);

    if (empty($students)) {
        /** @var \Illuminate\Foundation\Console\ClosureCommand $this */
        return $this->info('students list is empty');
    }

    $limit = $this->option('limit');
    if (!is_numeric($limit) || $limit < 0) {
        $limit = 10;
    }
    $students = array_slice($students, 0, $limit);

    if ($this->option('type') === 'table') {
        return $this->table(['id', 'name', 'family', 'age', 'grade'], $students);
    }

    dd($students);
})->describe('show students list');

Artisan::command('student:add', function () {
    /** @var \Illuminate\Foundation\Console\ClosureCommand $this */
    $students = Cache::get(STUDENTS_CACHE_KEY, []);
    $id = empty($students) ? 1 : end($students)['id'] + 1;

    do {
        if (isset($name)) {
            $this->error('name is invalid enter a valid name with 2 more characters');
        }
        $name = $this->ask('Enter name', 'name is required');
    } while ($name === 'name is required' || strlen($name) <= 1);

    do {
        if (isset($family)) {
            $this->error('family is invalid enter a valid family with 2 more characters');
        }
        $family = $this->ask('Enter family', 'family is required');
    } while ($family === 'family is required' || strlen($family) <= 1);

    do {
        if (isset($age)) {
            $this->error('age is invalid enter a valid age between 10, 100');
        }
        $age = $this->ask('Enter age', 'age is required, between 10, 100');
    } while ($age === 'age is required, between 10, 100' || !is_numeric($age) || $age < 10 || $age > 100);

    do {
        if (isset($term)) {
            $this->error('term is invalid enter a valid term between 1, 4');
        }
        $term = $this->ask('Enter term', 'term is required, between 1, 4');
    } while ($term === 'term is required, between 1, 4' || !is_numeric($term) || $term < 1 || $term > 4);
    
    $students[] = compact('id', 'name', 'family', 'age', 'term');
    Cache::forever(STUDENTS_CACHE_KEY, $students);
})->describe('add new student');



Artisan::command('student:remove {ids* : student ids to remove}', function ($ids) {
    /** @var \Illuminate\Foundation\Console\ClosureCommand $this */
    $students = Cache::get(STUDENTS_CACHE_KEY);
    $filtered = array_filter($students, function ($item) use ($ids) {
        return !in_array($item['id'], $ids);
    });
    
    if (count($students) === count($filtered)) {
        return $this->getOutput()->error('no student found with given id!');
    }

    Cache::forever(STUDENTS_CACHE_KEY, $filtered);

    $this->getOutput()->success('student removed successfully');
})->describe('remove a student');






