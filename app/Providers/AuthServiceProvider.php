<?php
namespace App\Providers;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Auth;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot()
    {
        $this->registerPolicies();

        if (\DB::connection()->getDatabaseName() && \Schema::hasTable("permissions")) {
            $permissions = \App\Models\Permission::all();

            foreach ($permissions as $permission) {
                Gate::define($permission["key"], function ($user = null) use ($permission) {
                    if (!auth()->guard("admin")->check()) {
                        return false;
                    }

                    if (auth()->guard("admin")->user()->id == 1) {
                        return true;
                    }

                    $roles = \App\Models\UserRole::where("user_id", auth()->guard("admin")->user()["id"])
                        ->select("role_id")
                        ->get();

                    foreach ($roles as $role) {
                        $hasPermission = \App\Models\RolePermission::where([
                            ["role_id", $role["role_id"]],
                            ["permission_id", $permission["id"]]
                        ])->exists();

                        if ($hasPermission) {
                            return true;
                        }
                    }

                    return false;
                });
            }
        }

        Gate::define("admin", function ($user = null) {
            return auth()->guard("admin")->check();
        });

        Gate::define("patient", function ($user = null) {
            return auth()->guard("patient")->check();
        });

    }
}
