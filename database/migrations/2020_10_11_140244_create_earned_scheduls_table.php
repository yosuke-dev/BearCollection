<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEarnedSchedulsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earned_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('milestone_id')->constrained();
            // 横軸（時間軸ベース）のアーンド・スケジュール（英: Earned Schedule, ES）
            // 時間軸の日付。
            $table->date('earned_schedule_date');
            // 完成時総予算 (BAC, Budget At Completion)
            // プロジェクトの総予算。
            $table->double('budget_at_completion', 8, 2);
            // プランド・バリュー（計画価値） (PV, Planned Value)
            // プロジェクト開始当初、現時点までに計画されていたワーク・パッケージに対する予算の累計額。
            // これを基準に遅延やコストオーバーを判断。プロジェクト完了まで、パフォーマンス測定のベースラインとして利用される。
            // なお、プランド・バリューが描く積み上げ折れ線グラフの形状より、マスター・スケジュールにおける作業負荷のバランスのチェックもできる。
            $table->double('planned_value', 8, 2);
            // アクチュアル・コスト（実コスト） (AC, Actual Cost)
            // 現時点までに完了した作業に対して実際に投入したコスト。
            $table->double('actual_cost', 8, 2);
            // アーンド・バリュー（達成価値/出来高） (EV, Earned Value)
            // 現時点までに完了した作業量を、プロジェクトの総予算で換算したコスト。
            // EV = BAC × 作業進捗率
            $table->double('earned_value', 8, 2);
            // コスト差異 (CV, Cost Variance)
            // どの程度、投入したコストに対して成果を上げているかの差異。
            // CV = EV - AC（0以上であれば良好・現時点での実コストは予算以内に収まっている）
            // $table->double('cost_variance', 8, 2);
            // スケジュール差異 (SV, Schedule Variance)
            // どの程度、予定に対して進捗しているかのコスト差異。
            // SV = EV - PV （0以上であれば良好・現時点でのスケジュールは予定よりも早く進行している）
            // $table->double('schedule_variance', 8, 2);
            // コスト効率指数 (CPI, Cost Performance Index)
            // どの程度、投入したコストに対して成果を上げているかの割合。
            // CPI = EV ÷ AC（1以上であれば良好・現時点での実コストは予算以内に収まっている）
            // $table->double('cost_performance_index', 8, 2);
            // スケジュール効率指数(SPI, Schedule Performance Index)
            // どの程度、予定に対して進捗しているかのコスト割合。
            // SPI = EV ÷ PV（1以上であれば良好・現時点でのスケジュールは予定よりも早く進行している）
            // $table->double('schedule_performance_index', 8, 2);
            // 完成時総コスト見積り (EAC, Estimate At Completion)
            // 現状のまま進捗した場合、プロジェクト完了時点までにかかる最終コストの見積り額。
            // EAC = AC + (BAC - EV) ÷ CPI = AC + ETC
            // $table->double('estimate_at_completion', 8, 2);
            // 残作業のコスト見積り (ETC, Estimate To Completion)
            // 現状のまま進捗した場合、現時点からプロジェクト完了時点までに遂行すべき残作業コストの見積り額。
            // ETC = (BAC - EV) ÷ CPI = EAC - AC
            // $table->double('estimate_to_completion', 8, 2);
            // 完了時コスト差異
            // 現状のまま進捗した場合、プロジェクトの総予算と最終コストの見積り額の差異。
            // VAC = bac-eac（0以下であれば良好・プロジェクト完了時点での実コストは予算以内に収まっている）
            // $table->double('variance_at_completion', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('earned_schedules');
    }
}
