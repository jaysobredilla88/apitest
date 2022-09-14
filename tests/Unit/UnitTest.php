<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\Dtr;
use Tests\TestCase;
use Faker;

class UnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_employee_create()
    {
        $faker = Faker\Factory::create('en_EN');
        $email = $faker->unique()->safeEmail();
        Employee::where('email', $email)->delete();

        $response = $this->post(route('api.employee.create'), [
            'first_name' => $faker->name(),
            'last_name' => $faker->name(),
            'email' => $email,
        ]);
        Employee::where('email', $email)->delete();

        $response->assertStatus(204);
    }

    public function test_employee_duplicate()
    {
        $faker = Faker\Factory::create('en_EN');
        $email = $faker->unique()->safeEmail();

        $emp1 = Employee::create(
            [
                'first_name' => $faker->name(),
                'last_name' => $faker->name(),
                'email' => $email,
            ]
        );

        $response = $this->post(route('api.employee.create'), [
            'first_name' => $faker->name(),
            'last_name' => $faker->name(),
            'email' => $email,
        ]);

        $emp1->delete();

        $response->assertStatus(400);
    }

    public function test_employee_invalid()
    {
        $faker = Faker\Factory::create('en_EN');

        //missing email
        $response = $this->post(route('api.employee.create'), [
            'first_name' => $faker->name(),
            'last_name' => $faker->name(),
        ]);

        $response->assertStatus(400);
    }

    public function test_dtr_create()
    {
        $faker = Faker\Factory::create('en_EN');
        $email = $faker->unique()->safeEmail();

        $emp1 = Employee::create(
            [
                'first_name' => $faker->name(),
                'last_name' => $faker->name(),
                'email' => $email,
            ]
        );

        $response = $this->postJson(route('api.dtr.create'), [
            [
                "email" => $email,
                "date" => "2022-09-07",
                "time_in" => "7:45 AM",
                "time_out" => "3:00 PM"
            ],
            [
                "email" => $email,
                "date" => "2022-09-08",
                "time_in" => "8:00 AM",
                "time_out" => "6:02 PM"
            ]
        ]);

        Dtr::where('employee_id', $emp1->id)->delete();
        $emp1->delete();

        $response->assertStatus(204);
    }

    public function test_dtr_log()
    {
        $faker = Faker\Factory::create('en_EN');
        $email = $faker->unique()->safeEmail();

        $response = $this->get(route('api.dtr.get'), [
            'email' => $email,
        ]);

        $response->assertStatus(400);
    }

    public function test_dtr_log_invalid()
    {
        $faker = Faker\Factory::create('en_EN');

        //missing email
        $response = $this->post(route('api.employee.create'), [
            'first_name' => $faker->name(),
            'last_name' => $faker->name(),
        ]);

        $response->assertStatus(400);
    }
}
