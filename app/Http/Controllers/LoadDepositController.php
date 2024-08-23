<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GuzzleService;
use App\Http\Controllers\Helper\EncryptionController;
use App\Services\AuthenticationHeaderService;

class LoadDepositController extends Controller
{
    protected $guzzleService;
    protected $authenticationHeaderService;

    public function __construct(GuzzleService $guzzleService, AuthenticationHeaderService $authenticationHeaderService)
    {
        $this->guzzleService = $guzzleService;

        $this->authenticationHeaderService = $authenticationHeaderService;

    }

    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLoadDeposit(Request $request)
    {
        $request->validate([
            'cardkitFront' => 'required',
            'amountFront'=>'required'
        ]);
        

        //return "ola";

        //dd("oi");
       

        $cardWithoutFormatation = $request->input('cardkitFront');

        $amountFrontWithoutFormatation  = $request->input('amountFront');

        //\Log::info('Iniciando getLoadDeposit');
        //\Log::info('Dados recebidos', ['$cardWithoutFormatation' => $cardWithoutFormatation, 'amountFrontWithoutFormatation' => $amountFrontWithoutFormatation]);

        $cardKIT = str_replace('.', '', $cardWithoutFormatation);

        $amountFee = str_replace(',', '.', $amountFrontWithoutFormatation);
        
        //\Log::info('Dados recebidos', ['$cardKIT' => $cardKIT, '$amountFee' => $amountFee]);

        $apiUrl = "https://dashboard.nossdaq.com/api/physical-cards/load-deposit";

        $headers = $this->authenticationHeaderService->getHeaders();

        //\Log::info('Dados recebidos', [' $apiUrl' =>  $apiUrl, '$headers' => $headers]);

        $body = '{"card_number": "'.$cardKIT.'", "amount": "'.$amountFee.'", "token_symbol": "TRX.USDT"}';

       // \Log::info('Dados recebidos', [' $body' =>  $body]);

        $response = $this->guzzleService->sendRequest('POST', $apiUrl, $body, $headers);

        //\Log::info('Dados recebidos', [' $response' =>  $response]);

        $content = $response->getBody()->getContents();

        //\Log::info('Dados recebidos', ['$content' =>  $content]);

        $data = json_decode($content, true);

        if ($data['success']) {

            $link = $data['data']['checkout_page_url'];
            return  $link;

        } else {

            return 'ERRO';

        }

    }
}
