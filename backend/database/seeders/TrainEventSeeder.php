<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Training\Trainer;
use App\Models\Training\TrainerDetail;
use App\Models\Training\TrainerEducation;
use App\Models\Training\TrainerProvider;
use App\Models\Training\TrxClass;
use App\Models\Training\TrxEvent;
use App\Models\Training\TrxSchedule;
use App\Models\Training\TrxSyllabus;
use App\Models\Training\TrxSyllabusDetail;
use App\Models\Training\TrxTrainer;
use Illuminate\Support\Facades\DB;

class TrainEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
        $trxEvents = TrxEvent::factory()->count(15)->create();

        foreach ($trxEvents as $trxEvent) {
            $sylabus = TrxSyllabus::factory()->create([
                'trx_train_event_id' => $trxEvent->id,
            ]);

            TrxSyllabusDetail::factory()->count(5)->create([
                'syllabus_id' => $sylabus->id,
            ]);

            $class = TrxClass::factory()->create([
                'trx_train_event_id' => $trxEvent->id,
            ]);
            TrxSchedule::factory()->count(rand(1,3))->create([
                'trx_train_event_id' => $trxEvent->id,
                'class_public_id' => $class->type_class=='1' ? $class->id : null,
                'class_inhouse_id' => $class->type_class=='2' ? $class->id : null,
            ]);
            $trainers = TrxTrainer::factory()->count(rand(1,2))->create([
                'trx_train_event_id' => $trxEvent->id,
            ]);

            foreach ($trainers as $trainer) {
                TrainerProvider::factory()->count(rand(1,3))->create([
                    'trainer_id' => $trainer->trainer_id,
                ]);
                TrainerDetail::factory()->count(rand(1,2))->create([
                    'trainer_id' => $trainer->trainer_id,
                ]);
                TrainerEducation::factory()->count(rand(1,2))->create([
                    'trainer_id' => $trainer->trainer_id,
                ]);
            }
        }
        });
    }
}
