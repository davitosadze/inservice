<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    /**
     * Route name to Georgian permission mapping
     */
    private $routeToPermission = [
        // Dashboard & Calendar
        'dashboard' => null, // Allow all authenticated users
        'calendar.index' => 'სერვისის ნახვა|რემონტის ნახვა',
        'calendar.data' => 'სერვისის ნახვა|რემონტის ნახვა',
        'profile' => null, // Allow all authenticated users

        // Chats
        'chats.index' => 'ჩატი',
        'chats.show' => 'ჩატი',
        'chats.reply' => 'ჩატი',
        'chats.startChat' => 'ჩატი',

        // Invoices (evaluations)
        'invoices.index' => 'ინვოისის ნახვა',
        'invoices.show' => 'ინვოისის ნახვა',
        'invoices.create' => 'ინვოისის შექმნა',
        'invoices.store' => 'ინვოისის შექმნა',
        'invoices.edit' => 'ინვოისის რედაქტირება',
        'invoices.update' => 'ინვოისის რედაქტირება',
        'invoices.destroy' => 'ინვოისის წაშლა',
        'invoices.pdf' => 'ინვოისის ნახვა',
        'invoices.excel' => 'ექსელის გადმოწერა',
        'invoices.export' => 'ექსელის გადმოწერა',

        // Reports
        'reports.index' => 'რეპორტის ნახვა',
        'reports.show' => 'რეპორტის ნახვა',
        'reports.create' => 'რეპორტის შექმნა',
        'reports.store' => 'რეპორტის შექმნა',
        'reports.edit' => 'რეპორტის რედაქტირება',
        'reports.update' => 'რეპორტის რედაქტირება',
        'reports.destroy' => 'რეპორტის წაშლა',

        // Clients
        'clients.index' => 'კლიენტის ნახვა',
        'clients.show' => 'კლიენტის ნახვა',
        'clients.create' => 'კლიენტის შექმნა',
        'clients.store' => 'კლიენტის შექმნა',
        'clients.edit' => 'კლიენტის რედაქტირება',
        'clients.update' => 'კლიენტის რედაქტირება',
        'clients.destroy' => 'კლიენტის წაშლა',

        // Purchasers
        'purchasers.index' => 'მყიდველის ნახვა',
        'purchasers.show' => 'მყიდველის ნახვა',
        'purchasers.create' => 'მყიდველის შექმნა',
        'purchasers.store' => 'მყიდველის შექმნა',
        'purchasers.edit' => 'მყიდველის რედაქტირება',
        'purchasers.update' => 'მყიდველის რედაქტირება',
        'purchasers.destroy' => 'მყიდველის წაშლა',
        'purchasers.special-attributes.index' => 'მყიდველის ნახვა',
        'purchasers.special-attributes.show' => 'მყიდველის ნახვა',
        'purchasers.special-attributes.create' => 'მყიდველის შექმნა',
        'purchasers.special-attributes.store' => 'მყიდველის შექმნა',
        'purchasers.special-attributes.edit' => 'მყიდველის რედაქტირება',
        'purchasers.special-attributes.update' => 'მყიდველის რედაქტირება',
        'purchasers.special-attributes.destroy' => 'მყიდველის წაშლა',

        // Services
        'services.index' => 'სერვისის ნახვა',
        'services.show' => 'სერვისის ნახვა',
        'services.new' => 'სერვისის ნახვა',
        'services.create' => 'სერვისის შექმნა',
        'services.store' => 'სერვისის შექმნა',
        'services.edit' => 'სერვისის რედაქტირება',
        'services.update' => 'სერვისის რედაქტირება',
        'services.destroy' => 'სერვისის წაშლა',
        'services.arrived' => 'სერვისის რედაქტირება',

        // Responses
        'responses.index' => 'რეაგირების ნახვა',
        'responses.show' => 'რეაგირების ნახვა',
        'responses.new' => 'რეაგირების ნახვა',
        'responses.create' => 'რეაგირების შექმნა',
        'responses.store' => 'რეაგირების შექმნა',
        'responses.edit' => 'რეაგირების რედაქტირება',
        'responses.update' => 'რეაგირების რედაქტირება',
        'responses.destroy' => 'რეაგირების წაშლა',
        'responses.arrived' => 'რეაგირების რედაქტირება',
        'responses.assign-manager' => 'რეაგირების რედაქტირება',

        // Repairs
        'repairs.index' => 'რემონტის ნახვა',
        'repairs.show' => 'რემონტის ნახვა',
        'repairs.new' => 'რემონტის ნახვა',
        'repairs.create' => 'რემონტის შექმნა',
        'repairs.store' => 'რემონტის შექმნა',
        'repairs.edit' => 'რემონტის რედაქტირება',
        'repairs.update' => 'რემონტის რედაქტირება',
        'repairs.destroy' => 'რემონტის წაშლა',
        'repairs.arrived' => 'რემონტის რედაქტირება',
        'repairs.assign-performer' => 'რემონტის რედაქტირება',
        'repairs.change-mode' => 'რემონტის რედაქტირება',

        // Acts (for responses)
        'acts.index' => 'აქტის ნახვა',
        'acts.show' => 'აქტის ნახვა',
        'acts.create' => 'აქტის შექმნა',
        'acts.store' => 'აქტის შექმნა',
        'acts.edit' => 'აქტის რედაქტირება',
        'acts.update' => 'აქტის რედაქტირება',
        'acts.destroy' => 'აქტის წაშლა',
        'acts.export' => 'აქტის ნახვა',

        // Service Acts
        'service-acts.index' => 'სერვისის აქტის ნახვა',
        'service-acts.show' => 'სერვისის აქტის ნახვა',
        'service-acts.create' => 'სერვისის აქტის შექმნა',
        'service-acts.store' => 'სერვისის აქტის შექმნა',
        'service-acts.edit' => 'სერვისის აქტის რედაქტირება',
        'service-acts.update' => 'სერვისის აქტის რედაქტირება',
        'service-acts.destroy' => 'სერვისის აქტის წაშლა',
        'service-acts.export' => 'სერვისის აქტის ნახვა',

        // Repair Acts
        'repair-acts.index' => 'რემონტის აქტის ნახვა',
        'repair-acts.show' => 'რემონტის აქტის ნახვა',
        'repair-acts.create' => 'რემონტის აქტის შექმნა',
        'repair-acts.store' => 'რემონტის აქტის შექმნა',
        'repair-acts.edit' => 'რემონტის აქტის რედაქტირება',
        'repair-acts.update' => 'რემონტის აქტის რედაქტირება',
        'repair-acts.destroy' => 'რემონტის აქტის წაშლა',
        'repair-acts.export' => 'რემონტის აქტის ნახვა',

        // Instructions
        'instructions.index' => 'მომხმარებლის პარამეტრების ნახვა',
        'instructions.show' => 'მომხმარებლის პარამეტრების ნახვა',
        'instructions.create' => 'მომხმარებლის პარამეტრების ნახვა',
        'instructions.store' => 'მომხმარებლის პარამეტრების ნახვა',
        'instructions.edit' => 'მომხმარებლის პარამეტრების ნახვა',
        'instructions.update' => 'მომხმარებლის პარამეტრების ნახვა',
        'instructions.destroy' => 'მომხმარებლის პარამეტრების ნახვა',

        // Settings (Main Options) - Roles, Permissions, Users
        'roles.index' => 'ძირითადი პარამეტრების ნახვა',
        'roles.show' => 'ძირითადი პარამეტრების ნახვა',
        'roles.create' => 'ძირითადი პარამეტრების ნახვა',
        'roles.store' => 'ძირითადი პარამეტრების ნახვა',
        'roles.edit' => 'ძირითადი პარამეტრების ნახვა',
        'roles.update' => 'ძირითადი პარამეტრების ნახვა',
        'roles.destroy' => 'ძირითადი პარამეტრების ნახვა',

        'permissions.index' => 'ძირითადი პარამეტრების ნახვა',
        'permissions.show' => 'ძირითადი პარამეტრების ნახვა',
        'permissions.create' => 'ძირითადი პარამეტრების ნახვა',
        'permissions.store' => 'ძირითადი პარამეტრების ნახვა',
        'permissions.edit' => 'ძირითადი პარამეტრების ნახვა',
        'permissions.update' => 'ძირითადი პარამეტრების ნახვა',
        'permissions.destroy' => 'ძირითადი პარამეტრების ნახვა',

        'users.index' => 'ძირითადი პარამეტრების ნახვა',
        'users.show' => 'ძირითადი პარამეტრების ნახვა',
        'users.create' => 'ძირითადი პარამეტრების ნახვა',
        'users.store' => 'ძირითადი პარამეტრების ნახვა',
        'users.storeNew' => 'ძირითადი პარამეტრების ნახვა',
        'users.edit' => 'ძირითადი პარამეტრების ნახვა',
        'users.update' => 'ძირითადი პარამეტრების ნახვა',
        'users.destroy' => 'ძირითადი პარამეტრების ნახვა',

        'options.index' => 'ძირითადი პარამეტრების ნახვა',
        'options.store' => 'ძირითადი პარამეტრების ნახვა',

        // Other Settings
        'categories.index' => 'მასალის ნახვა',
        'categories.show' => 'მასალის ნახვა',
        'categories.create' => 'მასალის შექმნა',
        'categories.store' => 'მასალის შექმნა',
        'categories.edit' => 'მასალის რედაქტირება',
        'categories.update' => 'მასალის რედაქტირება',
        'categories.destroy' => 'მასალის წაშლა',
        'categories.category-attributes.index' => 'მასალის ნახვა',
        'categories.category-attributes.show' => 'მასალის ნახვა',
        'categories.category-attributes.create' => 'მასალის შექმნა',
        'categories.category-attributes.store' => 'მასალის შექმნა',
        'categories.category-attributes.edit' => 'მასალის რედაქტირება',
        'categories.category-attributes.update' => 'მასალის რედაქტირება',
        'categories.category-attributes.destroy' => 'მასალის წაშლა',

        'settings.index' => 'ლოკაციის ნახვა',

        'repair-devices.index' => 'რემონტის მოწყობილობის ნახვა',
        'repair-devices.show' => 'რემონტის მოწყობილობის ნახვა',
        'repair-devices.create' => 'რემონტის მოწყობილობის შექმნა',
        'repair-devices.store' => 'რემონტის მოწყობილობის შექმნა',
        'repair-devices.edit' => 'რემონტის მოწყობილობის რედაქტირება',
        'repair-devices.update' => 'რემონტის მოწყობილობის რედაქტირება',
        'repair-devices.destroy' => 'რემონტის მოწყობილობის წაშლა',

        // Card Settings
        'regions.index' => 'რეგიონის ნახვა',
        'regions.show' => 'რეგიონის ნახვა',
        'regions.create' => 'რეგიონის შექმნა',
        'regions.store' => 'რეგიონის შექმნა',
        'regions.edit' => 'რეგიონის რედაქტირება',
        'regions.update' => 'რეგიონის რედაქტირება',
        'regions.destroy' => 'რეგიონის წაშლა',

        'systems.index' => 'სისტემის ნახვა',
        'systems.show' => 'სისტემის ნახვა',
        'systems.create' => 'სისტემის შექმნა',
        'systems.store' => 'სისტემის შექმნა',
        'systems.edit' => 'სისტემის რედაქტირება',
        'systems.update' => 'სისტემის რედაქტირება',
        'systems.destroy' => 'სისტემის წაშლა',

        'device-types.index' => 'მოწყობილობის ტიპის ნახვა',
        'device-types.show' => 'მოწყობილობის ტიპის ნახვა',
        'device-types.create' => 'მოწყობილობის ტიპის შექმნა',
        'device-types.store' => 'მოწყობილობის ტიპის შექმნა',
        'device-types.edit' => 'მოწყობილობის ტიპის რედაქტირება',
        'device-types.update' => 'მოწყობილობის ტიპის რედაქტირება',
        'device-types.destroy' => 'მოწყობილობის ტიპის წაშლა',

        'device-brands.index' => 'მოწყობილობის ბრენდის ნახვა',
        'device-brands.show' => 'მოწყობილობის ბრენდის ნახვა',
        'device-brands.create' => 'მოწყობილობის ბრენდის შექმნა',
        'device-brands.store' => 'მოწყობილობის ბრენდის შექმნა',
        'device-brands.edit' => 'მოწყობილობის ბრენდის რედაქტირება',
        'device-brands.update' => 'მოწყობილობის ბრენდის რედაქტირება',
        'device-brands.destroy' => 'მოწყობილობის ბრენდის წაშლა',

        'locations.index' => 'ლოკაციის ნახვა',
        'locations.show' => 'ლოკაციის ნახვა',
        'locations.create' => 'ლოკაციის შექმნა',
        'locations.store' => 'ლოკაციის შექმნა',
        'locations.edit' => 'ლოკაციის რედაქტირება',
        'locations.update' => 'ლოკაციის რედაქტირება',
        'locations.destroy' => 'ლოკაციის წაშლა',

        // Performers
        'performers.index' => 'შემსრულებლის ნახვა',
        'performers.show' => 'შემსრულებლის ნახვა',
        'performers.create' => 'შემსრულებლის შექმნა',
        'performers.store' => 'შემსრულებლის შექმნა',
        'performers.edit' => 'შემსრულებლის რედაქტირება',
        'performers.update' => 'შემსრულებლის რედაქტირება',
        'performers.destroy' => 'შემსრულებლის წაშლა',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission = null, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);
        return $next($request);


        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        // If permission is explicitly passed to middleware, use it
        if (! is_null($permission)) {
            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);
        } else {
            // Get route name and map to Georgian permission
            $routeName = $request->route()->getName();

            // Check if we have a mapping for this route
            if (isset($this->routeToPermission[$routeName])) {
                $mappedPermission = $this->routeToPermission[$routeName];

                // null means allow all authenticated users
                if ($mappedPermission === null) {
                    return $next($request);
                }

                $permissions = explode('|', $mappedPermission);
            } else {
                // No mapping found - use route name as permission (fallback)
                $permissions = [$routeName];
            }
        }

        // Check if user has any of the required permissions
        foreach ($permissions as $perm) {
            if ($authGuard->user()->can($perm)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
