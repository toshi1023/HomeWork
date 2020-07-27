<?php

use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 一旦テーブル削除
        DB::table('companies')->delete();

        // faker使う(引数には日本語を設定している)
        $faker = Faker\Factory::create('ja_JP');

        $company_name = [];

        // a~zまでの4文字をランダムに組み合わせて会社名を作成
        for($j=0; $j < 10; $j++){
            $company_name[$j] = "株式会社" . chr(mt_rand(65,90)).chr(mt_rand(65,90)).chr(mt_rand(65,90)).chr(mt_rand(65,90));
        }
        

        // レコード10社分出力
        for($i=0; $i < 10; $i++){
            \App\Models\Company::create([
                'name' => $faker->randomElement($company_name), // 配列の値をランダムに配布
                'address' => $faker->address(),
                'tel' => $faker->phoneNumber(),
                'del_flg' => 0,

            ]);
        }
    }
}
