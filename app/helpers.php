<?php

use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

if (!function_exists('roles_list')) {
    /**
     * Return roles list as an array of KEY (role id) => VALUE (role title)
     *
     * @return array
     */
    function roles_list(): array
    {
        $roles = [];
        foreach (config('system.roles') as $key => $value) {
            $roles[$key] = __($value['title']);
        }
        return $roles;
    }
}

if (!function_exists('roles_list_badges')) {
    /**
     * Return roles list as an array of KEY (role id) => VALUE (role title)
     *
     * @return array
     */
    function roles_list_badges(): array
    {
        $roles = [];
        foreach (config('system.roles') as $key => $value) {
            $roles[$value['badge-color']] = $key;
        }
        return $roles;
    }
}

if (!function_exists('has_all_permissions')) {
    /**
     * Check if the user has all the permissions passed as parameters
     *
     * @param User|Authenticatable $user
     * @param ...$permissions
     * @return bool
     */
    function has_all_permissions(User|Authenticatable $user, ...$permissions): bool
    {
        if ($user->role) {
            $role = config('system.roles.' . $user->role);
            if ($role) {
                return sizeof(array_intersect($role['permissions']['functions'], $permissions)) === sizeof($permissions);
            }
            return false;
        }
        return false;
    }
}

if (!function_exists('has_any_permissions')) {
    /**
     * Check if the user has any of the permissions passed as parameters
     *
     * @param User|Authenticatable $user
     * @param ...$permissions
     * @return bool
     */
    function has_any_permissions(User|Authenticatable $user, ...$permissions): bool
    {
        if ($user->role) {
            $role = config('system.roles.' . $user->role);
            if ($role) {
                return sizeof(array_intersect($role['permissions']['functions'], $permissions));
            }
            return false;
        }
        return false;
    }
}

if (!function_exists('can_access_page')) {
    /**
     * Check if the user can access the pages passed as parameters
     *
     * @param User|Authenticatable $user
     * @param ...$pages
     * @return bool
     */
    function can_access_page(User|Authenticatable $user, ...$pages): bool
    {
        if ($user->role) {
            $role = config('system.roles.' . $user->role);
            if ($role) {
                return sizeof(array_intersect($role['permissions']['pages'], $pages));
            }
            return false;
        }
        return false;
    }
}

if (!function_exists('statuses_list')) {
    /**
     * Return statuses list as an array of KEY (status id) => VALUE (status title)
     *
     * @return array
     */
    function statuses_list(): array
    {
        return TicketStatus::all()->pluck('title', 'slug')->toArray();
    }
}

if (!function_exists('statuses_list_for_kanban')) {
    /**
     * Return statuses list as an array for kanban board
     *
     * @return array
     */
    function statuses_list_for_kanban(): array
    {
        return TicketStatus::all()->map(fn($item) => [
            'id' => $item->slug,
            'title' => $item->title,
            'text-color' => $item->text_color,
            'bg-color' => $item->bg_color
        ])->toArray();
    }
}

if (!function_exists('priorities_list')) {
    /**
     * Return priorities list as an array of KEY (priority id) => VALUE (priority title)
     *
     * @return array
     */
    function priorities_list(): array
    {
        return TicketPriority::all()->pluck('title', 'slug')->toArray();
    }
}

if (!function_exists('default_ticket_status')) {
    /**
     * Return the default status configured on system configurations
     *
     * @return string|null
     */
    function default_ticket_status(): string|null
    {
        if ($default = TicketStatus::where('default', true)->first()) {
            return Str::slug($default->title);
        }
        return null;
    }
}

if (!function_exists('types_list')) {
    /**
     * Return types list as an array of KEY (type id) => VALUE (type title)
     *
     * @return array
     */
    function types_list(): array
    {
        return TicketType::all()->pluck('title', 'slug')->toArray();
    }
}

if (!function_exists('locales')) {
    /**
     * Return application locales as an array of KEY (locale id) => VALUE (locale title)
     *
     * @return array
     */
    function locales(): array
    {
        $roles = [];
        foreach (config('system.locales') as $key => $value) {
            $roles[$key] = __($value);
        }
        return $roles;
    }
}
