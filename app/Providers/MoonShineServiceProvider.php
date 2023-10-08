<?php

namespace App\Providers;

use App\MoonShine\Resources\BudgetActionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use MoonShine\Models\MoonshineUserRole;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;
use MoonShine\Utilities\AssetManager;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MoonShine::class)->menu([
            MenuGroup::make('moonshine::ui.resource.system', [
                MenuItem::make('moonshine::ui.resource.admins_title', new MoonShineUserResource())
                    ->translatable()
                    ->icon('users'),
                MenuItem::make('moonshine::ui.resource.role_title', new MoonShineUserRoleResource())
                    ->translatable()
                    ->icon('bookmark'),
            ])->translatable()->canSee(function(Request $request) {
                return $request->user('moonshine')?->moonshine_user_role_id === MoonshineUserRole::DEFAULT_ROLE_ID;
            }) ,

            MenuItem::make('Нычка', new BudgetActionResource(), 'heroicons.outline.banknotes'),
        ]);

        app(AssetManager::class)->add([
            Vite::asset('resources/css/app.css'),
        ]);


    }
}
