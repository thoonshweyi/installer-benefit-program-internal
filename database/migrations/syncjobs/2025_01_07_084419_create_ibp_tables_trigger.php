<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIbpTablesTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ibptables = [
            "point_promotions",
            "installer_cards",
            "collection_transactions",
            "installer_card_points",
            "redemption_transactions",
            "points_redemptions",
            "return_banners",
            "grouped_returns",
            "reference_return_collection_transactions",
            "reference_return_installer_card_points",
            "preused_slips",
            "point_pays",
            "double_profit_slips",
        ];
        // Postgre
        foreach($ibptables as $ibptable){
            DB::unprepared("
                -- Create Trigger
                CREATE OR REPLACE FUNCTION ".$ibptable."_after_insert()
                RETURNS TRIGGER AS $$
                BEGIN
                    INSERT INTO sync_logs(table_name, operation_type, record_id)
                    VALUES ('$ibptable', 'insert', NEW.id);
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER ".$ibptable."_afc
                AFTER INSERT ON $ibptable
                FOR EACH ROW
                EXECUTE FUNCTION ".$ibptable."_after_insert();



                -- Update Trigger
                CREATE OR REPLACE FUNCTION ".$ibptable."_after_update()
                RETURNS TRIGGER AS $$
                BEGIN
                    INSERT INTO sync_logs (table_name, operation_type, record_id)
                    VALUES ('$ibptable', 'update', NEW.id);
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER ".$ibptable."_afu
                AFTER UPDATE ON $ibptable
                FOR EACH ROW
                EXECUTE FUNCTION ".$ibptable."_after_update();


                -- Delete Trigger
                CREATE OR REPLACE FUNCTION ".$ibptable."_after_delete()
                RETURNS TRIGGER AS $$
                BEGIN
                    INSERT INTO sync_logs (table_name, operation_type, record_id)
                    VALUES ('$ibptable', 'delete', OLD.id);
                    RETURN OLD;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER ".$ibptable."_afd
                AFTER DELETE ON $ibptable
                FOR EACH ROW
                EXECUTE FUNCTION ".$ibptable."_after_delete();
            ");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $ibptables = [
            "point_promotions",
            "installer_cards",
            "collection_transactions",
            "installer_card_points",
            "redemption_transactions",
            "points_redemptions",
            "return_banners",
            "grouped_returns",
            "reference_return_collection_transactions",
            "reference_return_installer_card_points",
            "preused_slips",
            "point_pays",
            "double_profit_slips",
        ];

        foreach($ibptables as $ibptable){

            DB::unprepared("DROP TRIGGER IF EXISTS ".$ibptable."_afc ON ".$ibptable."");
            DB::unprepared("DROP FUNCTION IF EXISTS ".$ibptable."_after_insert()");

            DB::unprepared("DROP TRIGGER IF EXISTS ".$ibptable."_afu ON ".$ibptable."");
            DB::unprepared("DROP FUNCTION IF EXISTS ".$ibptable."_after_update()");

            DB::unprepared("DROP TRIGGER IF EXISTS ".$ibptable."_afd ON ".$ibptable."");
            DB::unprepared("DROP FUNCTION IF EXISTS ".$ibptable."_after_delete()");
        }

        // DB::unprepared("DROP TRIGGER IF EXISTS installer_cards_afc ON installer_cards");
        // DB::unprepared("DROP FUNCTION IF EXISTS installer_cards_after_insert()");

        // DB::unprepared("DROP TRIGGER IF EXISTS installer_cards_afu ON installer_cards");
        // DB::unprepared("DROP FUNCTION IF EXISTS installer_cards_after_update()");

        // DB::unprepared("DROP TRIGGER IF EXISTS installer_cards_afd ON installer_cards");
        // DB::unprepared("DROP FUNCTION IF EXISTS installer_cards_after_delete()");
    }
}
