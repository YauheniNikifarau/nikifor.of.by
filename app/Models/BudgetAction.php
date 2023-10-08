<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use MoonShine\Models\MoonshineUser;

class BudgetAction extends Model {
    use HasFactory;

    public function moonshine_user() {
        return $this->belongsTo( MoonshineUser::class );
    }

    public static function calculate_sum_in_usd(): int {
        $budget_actions = self::select( [ 'type', 'currency', 'sum' ] )->get();

        $json               = Http::get( 'https://api.nbrb.by/exrates/rates?periodicity=0' );
        $exchange           = json_decode( $json );
        $pretified_exchange = [];

        foreach ( $exchange as $currency_exchange ) {
            $pretified_exchange[ strtolower( $currency_exchange->Cur_Abbreviation ) ] = $currency_exchange->Cur_OfficialRate;
        }

        $pretified_exchange['byn'] = 1;
        $pretified_exchange['pl']  = $pretified_exchange['pln'] / 10;
        $pretified_exchange['rub'] = $pretified_exchange['rub'] / 100;

        $result_sum_in_byn = 0;

        foreach ( $budget_actions as $budget_action ) {
            $sum      = $budget_action->sum;
            $type     = $budget_action->type;
            $currency = $budget_action->currency;

            $sum_in_byn = $sum * $pretified_exchange[ $currency ];

            if ( $type === 'in' ) {
                $result_sum_in_byn += $sum_in_byn;
            } else {
                $result_sum_in_byn -= $sum_in_byn;
            }
        }

        return round( $result_sum_in_byn / $pretified_exchange['usd'] );
    }
}
