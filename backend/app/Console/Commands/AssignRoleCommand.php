<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssignRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-role {email} {role_slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a specific role to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $slug = $this->argument('role_slug');

        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found with email: {$email}");
            return;
        }

        $role = \App\Models\Role::where('slug', $slug)->first();
        if (!$role) {
            $this->error("Role not found with slug: {$slug}");
            $this->info("Available roles: " . implode(', ', \App\Models\Role::pluck('slug')->toArray()));
            return;
        }

        $user->role_id = $role->id;
        $user->save();

        $this->info("Success! Assigned role '{$role->name}' ({$slug}) to user '{$user->name}' ({$email}).");
    }
}
