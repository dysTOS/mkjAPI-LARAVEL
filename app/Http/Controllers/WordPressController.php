<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ConfigController;

class WordPressController extends Controller
{
        public function savePost()
        {
            $token = $this->getWordPressJWT();
            $rest_api_url = "https://mk-jainzen.at/wp-json/wp/v2/posts";

           $data_string = json_encode([
               'title'    => 'Test title',
               'content'  => 'asödfkjsdjfölsakjfölksadjfö',
               'status'   => 'draft',
           ]);

           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $rest_api_url);
           curl_setopt($ch, CURLOPT_POST, 1);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

           curl_setopt($ch, CURLOPT_HTTPHEADER, [
               'Content-Type: application/json',
               'Content-Length: ' . strlen($data_string),
               'Authorization: Bearer ' . $token,
           ]);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

           $result = curl_exec($ch);

           curl_close($ch);

           if ($result) {
               return $result;
           } else {
               // ...
           }
        }

        private function getWordPressJWT(){
            $token = ConfigController::getValueByKey('wordpress_api_token');
            if($token && $this->validateJWT($token)){
                return $token;
            }

            $rest_api_url = "https://mk-jainzen.at/wp-json/jwt-auth/v1/token";
               $data_string = json_encode([
                              'username'    => env('MKJ_WP_USER'),
                              'password'  => env('MKJ_WP_PW'),

                          ]);


              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $rest_api_url);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
                  'Content-Type: application/json',
                  'Content-Length: ' . strlen($data_string),
              ]);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $result = json_decode(curl_exec($ch));
              curl_close($ch);
//              RESPONSE FORMAT:
//                   "token": "..",
//                   "user_display_name": "admin",
//                   "user_email": "admin@localhost.dev",
//                   "user_nicename": "admin"

            $token = $result->token;
            ConfigController::storeKeyValue('wordpress_api_token', $token);

            return $token;
        }

      private function validateJWT($token){
            $rest_api_url = "https://mk-jainzen.at/wp-json/jwt-auth/v1/token/validate";
            $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $rest_api_url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_HTTPHEADER, [
                 'Authorization: Bearer ' . $token,
          ]);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          $result = json_decode(curl_exec($ch));
          curl_close($ch);

            if($result->data->status == 200){
                return true;
            }else{
                return false;
            }
        }
}
