<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 一旦テーブル削除
        DB::table('users')->delete();

        // faker使う(引数には日本語を設定している)
        $faker = Faker\Factory::create('ja_JP');

        // レコード20人分出力
        for($i=0; $i < 20; $i++){
            \App\User::create([
                'email' => $faker->email(),
                'password' => Hash::make("aaaabbbb"),
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'company_id' => $faker->numberBetween(1, 10), // 1~10の間で乱数
                'del_flg' => 0,

            ]);
        }
    }
}
