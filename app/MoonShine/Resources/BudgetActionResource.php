<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\BudgetAction;

use MoonShine\Decorations\Flex;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Date;
use MoonShine\Fields\Enum;
use MoonShine\Fields\Number;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class BudgetActionResource extends Resource {
    public static string $model = BudgetAction::class;

    public static string $title = 'BudgetActions';

    protected bool $createInModal = true;

    public function fields(): array {
        return [
            ID::make()->sortable(),
            Flex::make([
                Select::make( 'In/Out', 'type' )->options( [
                    'in'  => 'In',
                    'out' => 'Out'
                ] ),
                Select::make( 'Currency', 'currency' )->options( [
                    'usd' => 'USD',
                    'eur' => 'EUR',
                    'rub' => 'RUB',
                    'byn' => 'BYN',
                    'pl'  => 'PL',
                ] ),
                Number::make('Sum')->min(0),
            ]),
            Text::make('Comment')->hideOnIndex(),
            Flex::make([
                Date::make('Date')->format('d.m.Y')->sortable(),
                BelongsTo::make('User', 'moonshine_user', 'name'),
            ]),
        ];
    }

    public function rules( Model $item ): array {
        return [];
    }

    public function search(): array {
        return [ 'id' ];
    }

    public function filters(): array {
        return [];
    }

    public function actions(): array {
        return [
            FiltersAction::make( trans( 'moonshine::ui.filters' ) ),
        ];
    }
}
