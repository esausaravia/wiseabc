<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //horarios lun mie vie 9-10 y 11-12
        $horarios = [1 => [9, 10, 11, 12], 3 => [9, 10, 11, 12], 5 => [9, 10, 11, 12]];

        $teacher = \App\Models\User::create([
            'user_type'=>3,
            'email'=>'MarioJimenez@wiseabcenglish.com',
            'name'=>'Mario Jimenez',
            'password' => bcrypt('qwerasdf')
        ]);
        $teacher->created_at = now('UTC')->subMonths(3)->subDay();
        $teacher->save();

        $arrName = explode(' ', $teacher->name);
        $teacher->saveMetas([
            'lname' => 'Jimenez',
            'fname' => 'Mario',
            'personal_email' => 'wiseabcenglish@gmail.com',
            'timezone' => '-0600'
        ]);
        $teacher->saveHorarios($horarios);

        $teacher = \App\Models\User::factory()->create(['user_type'=>3]);
        $oldEmail = $teacher->email;
        $teacher->email = 'teacher'.$teacher->id.'@wiseabcenglish.com';
        $teacher->created_at = now('UTC')->subMonths(3)->subDay();
        $teacher->save();

        $arrName = explode(' ', $teacher->name);
        $teacher->saveMetas([
            'lname' => array_pop($arrName),
            'fname' => implode(' ', $arrName),
            'personal_email' => $oldEmail,
            'timezone' => '-0600'
        ]);
        $teacher->saveHorarios($horarios);
    }
}
