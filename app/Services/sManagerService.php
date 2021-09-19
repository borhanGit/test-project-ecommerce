<?php

namespace App\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;

class sManagerService
{
    /**
     * initiatePayment
     * @param array $info
     * @return Application|RedirectResponse|Redirector
     */
    public static function initiatePayment(array $info)
    {
       
        try {
            $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dev-sheba.xyz/v1/ecom-payment/initiate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $info,
            CURLOPT_HTTPHEADER => array(
                'client-id: 481436540',
                'client-secret: 6mgcQtWDARS8FRncZzAokkOtuaVd1Mwdc097hrjoYIOsVstMhex6BDf6ze8yWAuOICFVKgEGwIOSlcIy5R0ybqN65tzSvo7yKmB9HpusS5rcac2qhf9WgAFf',
                'Accept: application/json'
            ),
        ));
    
            $response = curl_exec($curl);
            curl_close($curl);
            $responseJSON = json_decode($response, true);
            $code    = $responseJSON['code'];
            $message = $responseJSON['message'];
            

            if ($code !== 200) {
                dd($responseJSON);
                session()->flash('error',$message->error());
                // flash($message)->error();
                return redirect()->route('checkout');
            }
            return redirect(url($responseJSON['data']['link']));

        } catch (\Exception $ex) {
            session()->flash('error',$ex->getMessage());
            return redirect()->route('checkout');
        }
    }
 

}