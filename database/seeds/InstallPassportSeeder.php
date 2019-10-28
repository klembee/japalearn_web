<?php
/**
 * Created by PhpStorm.
 * User: cleme
 * Date: 2019-10-27
 * Time: 8:22 PM
 */

class InstallPassportSeeder extends \Illuminate\Database\Seeder
{
    public function run(){
        $oauthClient = \Illuminate\Support\Facades\DB::table('oauth_clients')->count();
        if($oauthClient == 0){
            //run the passport install
            exec("php artisan passport:install");
        }
    }
}