<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GuzzleService;
use App\Services\AuthenticationHeaderService;

class ExtratoController extends Controller
{
    protected $guzzleService;
    protected $authenticationHeaderService;

    public function __construct(GuzzleService $guzzleService, AuthenticationHeaderService $authenticationHeaderService)
    {
        $this->guzzleService = $guzzleService;
        $this->authenticationHeaderService = $authenticationHeaderService;
    }

    /**
     * Handle the request to get all transactions and balance.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function loadDeposit(Request $request)
    {
        // Valida o campo cardkitFront
        $request->validate([
            'cardkitFront' => 'required',
        ]);

        // Captura o valor do campo cardkitFront
        $cardWithoutFormatation = $request->input('cardkitFront');

        \Log::info('Iniciando loadDeposit');
        \Log::info('Dados recebidos', ['$cardWithoutFormatation' => $cardWithoutFormatation]);

        // Remove formatações desnecessárias
        $cardKIT = str_replace('.', '', $cardWithoutFormatation);

        \Log::info('Dados formatados', ['$cardKIT' => $cardKIT]);

        // Define a URL do endpoint da API para transações
        $transactionsApiUrl = "https://dashboard.nossdaq.com/api/physical-cards/get-all-transactions";

        // Gera os cabeçalhos de autenticação
        $headers = $this->authenticationHeaderService->getHeaders();

        \Log::info('URL e Headers', ['$transactionsApiUrl' => $transactionsApiUrl, '$headers' => $headers]);

        // Cria o corpo da requisição para transações
        $transactionsBody = '{"card_number": "'.$cardKIT.'"}';

        \Log::info('Corpo da requisição', ['$transactionsBody' => $transactionsBody]);

        // Faz a requisição para o endpoint de transações
        $transactionsResponse = $this->guzzleService->sendRequest('POST', $transactionsApiUrl, $transactionsBody, $headers);

        // Obtém o conteúdo da resposta das transações
        $transactionsContent = $transactionsResponse->getBody()->getContents();

        \Log::info('Resposta da API de transações', ['$transactionsContent' => $transactionsContent]);

        // Decodifica o JSON da resposta das transações
        $data = json_decode($transactionsContent, true);

        // Filtra as transações para exibir apenas as informações necessárias
        $transactions = [];
        if (isset($data['message']) && is_array($data['message'])) {
            foreach ($data['message'] as $transaction) {
                $transactions[] = [
                    'card_number' => $transaction['card_number'],
                    'time' => $transaction['time'],
                    'description' => $transaction['description'],
                    'processing_amount' => $transaction['processing_amount'],
                    'authorizationNo' => $transaction['authorizationNo'],
                ];
            }
        }

        // Define a URL do endpoint da API para saldo
        $balanceApiUrl = "https://dashboard.nossdaq.com/api/physical-cards/get-balance";

        // Cria o corpo da requisição para saldo
        $balanceBody = '{"card_number": "'.$cardKIT.'"}';

        \Log::info('Corpo da requisição de saldo', ['$balanceBody' => $balanceBody]);

        // Faz a requisição para o endpoint de saldo
        $balanceResponse = $this->guzzleService->sendRequest('POST', $balanceApiUrl, $balanceBody, $headers);

        // Obtém o conteúdo da resposta de saldo
        $balanceContent = $balanceResponse->getBody()->getContents();

        \Log::info('Resposta da API de saldo', ['$balanceContent' => $balanceContent]);

        // Decodifica o JSON da resposta de saldo
        $balanceData = json_decode($balanceContent, true);

        // Obtém o saldo
        $balance = isset($balanceData['message']) ? $balanceData['message'] : 'Não disponível';

        // Retorna a view com os dados das transações e saldo
        return view('extrato', [
            'transactions' => $transactions,
            'balance' => $balance
        ]);
    }
}
