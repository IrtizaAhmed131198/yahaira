<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Package;
use App\Models\Client;
use App\Models\Payment;
use App\Models\MatchRecord;
use App\Models\MatchDate;
use App\Models\Activity;
use App\Models\LeadNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SeederController extends Controller
{
    public function run()
    {
        set_time_limit(300); // Allow enough time
        $faker = Faker::create();

        try {
            // 1. Wipe specific tables (to prevent duplicates if run multiple times)
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            MatchDate::truncate();
            MatchRecord::truncate();
            Payment::truncate();
            Client::truncate();
            Activity::truncate();
            Deal::truncate();
            Lead::truncate();
            LeadNote::truncate();
            Package::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::beginTransaction();

            // 2. Packages
            $packages = [
                Package::create(['name' => 'Magnetic Woman', 'price' => 8000.00]),
                Package::create(['name' => 'VIP Matchmaking', 'price' => 25000.00]),
                Package::create(['name' => 'Executive Match', 'price' => 45000.00]),
            ];

            // Get users for roles
            $closer = User::role('closer')->first();
            $setter = User::role('setter')->first();
            $matchmaker = User::role('matchmaker')->first();

            // Fallbacks in case roles don't exist
            if (!$closer) $closer = User::first();
            if (!$setter) $setter = User::first();
            if (!$matchmaker) $matchmaker = User::first();

            $statuses = ['new', 'contacted', 'qualified', 'handed_off', 'lost'];
            $dealStatuses = ['assigned', 'booked', 'proposal', 'won', 'lost'];
            
            // 3. Leads & Pipeline
            for ($i = 0; $i < 150; $i++) {
                // Ensure a higher chance of being handed_off so we get more deals
                $status = $faker->randomElement(['new', 'contacted', 'qualified', 'handed_off', 'handed_off', 'handed_off', 'lost']);
                
                $firstName = $faker->firstName;
                $lastName = $faker->lastName;
                
                $lead = Lead::create([
                    'full_name' => $firstName . ' ' . $lastName,
                    'email' => $faker->unique()->safeEmail,
                    'phone' => $faker->phoneNumber,
                    'status' => $status,
                    'assigned_setter_id' => $setter->id,
                    'source' => $faker->randomElement(['Facebook', 'Instagram', 'Referral', 'Google']),
                ]);
                
                LeadNote::create([
                    'lead_id' => $lead->id,
                    'user_id' => $setter->id,
                    'note' => $faker->sentence
                ]);

                // Create activity for lead
                Activity::create([
                    'user_id' => $setter->id,
                    'action' => 'created lead',
                    'subject_type' => Lead::class,
                    'subject_id' => $lead->id,
                    'description' => 'Lead came in via ' . $lead->source
                ]);

                // 4. Deals for handed_off leads
                if ($status === 'handed_off') {
                    $dealStatus = $faker->randomElement(['assigned', 'booked', 'proposal', 'won', 'won', 'won', 'lost']);
                    $consultation_at = null;
                    $zoom_link = null;

                    if (in_array($dealStatus, ['booked', 'proposal', 'won', 'lost'])) {
                        $consultation_at = $faker->dateTimeBetween('-1 month', '+1 month');
                        $zoom_link = 'https://zoom.us/j/' . $faker->randomNumber(9);
                    }

                    $deal = Deal::create([
                        'lead_id' => $lead->id,
                        'assigned_closer_id' => $closer->id,
                        'status' => $dealStatus,
                        'consultation_at' => $consultation_at,
                        'zoom_link' => $zoom_link,
                        'notes' => $faker->boolean(70) ? $faker->sentence : null,
                    ]);

                    Activity::create([
                        'user_id' => $closer->id,
                        'action' => 'assigned deal',
                        'subject_type' => Lead::class,
                        'subject_id' => $lead->id,
                        'description' => 'Deal assigned to closer'
                    ]);

                    // 5. Clients for Won Deals
                    if ($dealStatus === 'won') {
                        $client = Client::create([
                            'lead_id' => $lead->id,
                            'deal_id' => $deal->id,
                            'full_name' => $lead->full_name,
                            'email' => $lead->email,
                            'phone' => $lead->phone,
                            'timezone' => $faker->timezone,
                            'status' => 'active',
                            'city' => $faker->city,
                            'state' => $faker->stateAbbr,
                            'date_of_birth' => $faker->dateTimeBetween('-40 years', '-25 years'),
                            'occupation' => $faker->jobTitle,
                            'relationship_goal' => 'Long-term partnership, marriage-minded',
                            'commitment_timeline' => 'Within 1–2 years',
                            'core_values' => 'Honesty, ambition, family',
                            'lifestyle' => 'Active, travels often, values health',
                            'current_stage' => 'Ready',
                            'review_notes' => 'Strong self-awareness, clear on what they want.',
                            'application_status' => 'Approved'
                        ]);

                        Activity::create([
                            'user_id' => $closer->id,
                            'action' => 'created client',
                            'subject_type' => Client::class,
                            'subject_id' => $client->id,
                            'description' => 'Deal won, converted to Client.'
                        ]);

                        // 6. Payments
                        $package = $faker->randomElement($packages);
                        $paidAt = $faker->dateTimeBetween('-2 months', 'now');
                        
                        Payment::create([
                            'client_id' => $client->id,
                            'package_id' => $package->id,
                            'amount' => $package->price,
                            'status' => 'paid',
                            'payment_method' => $faker->randomElement(['credit_card', 'bank_transfer', 'stripe']),
                            'paid_at' => $paidAt,
                            'contract_signed_at' => $paidAt,
                        ]);

                        // 7. Matchmaking (Only for VIP or Executive packages)
                        if (in_array($package->name, ['VIP Matchmaking', 'Executive Match']) && $faker->boolean(50)) {
                            $matchRecord = MatchRecord::create([
                                'client_id' => $client->id,
                                'candidate_name' => $faker->name,
                                'status' => $faker->randomElement(['identified', 'reviewed', 'proposed', 'approved', 'scheduled', 'completed']),
                            ]);

                            // Match Dates
                            for ($j = 0; $j < rand(1, 3); $j++) {
                                MatchDate::create([
                                    'match_id' => $matchRecord->id,
                                    'date_time' => $faker->dateTimeBetween('-1 week', '+2 weeks')->format('Y-m-d H:i:s'),
                                    'location' => $faker->randomElement(['Coffee Shop', 'Dinner', 'Virtual Zoom']),
                                    'status' => $faker->randomElement(['scheduled', 'completed']),
                                    'notes' => $faker->boolean ? 'Great date, would like to see again.' : null,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Dummy data successfully generated!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
