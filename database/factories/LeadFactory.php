<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        $statuses = ['new', 'contacted', 'qualified', 'handed_off', 'lost'];
        $sources = ['Website', 'Facebook', 'Instagram', 'Manual'];
        $services = ['Premier', 'VIP Coaching', 'Matchmaking Standard'];
        $budgets = ['$5k–$10k', '$10k–$15k', '$15k–$20k', '$20k+'];
        
        $setter = User::role('setter')->inRandomOrder()->first();

        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'timezone' => fake()->timezone(),
            'source' => fake()->randomElement($sources),
            'status' => fake()->randomElement($statuses),
            'interested_service' => fake()->randomElement($services),
            'budget_range' => fake()->randomElement($budgets),
            'assigned_setter_id' => $setter ? $setter->id : null,
            'next_followup_at' => fake()->optional()->dateTimeBetween('+1 days', '+2 weeks'),
            'created_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
