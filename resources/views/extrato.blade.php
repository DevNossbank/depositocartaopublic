@extends('layouts.app')

@section('content')

<link rel="stylesheet" type="text/css" href="/css/style.css">

<style>
    .img-lado{
        
    height: 450px;

    }

    .leftPart {
    color: #fff;
    text-align: center;
}
</style>

<div class="row">
    <h4 class="display-6 text-center mt-3">Extrato Cartão</h4>
    <div class="col-md-5 leftPart">
        <img src="/img/CARTAO-1.png" class="img-fluid img-lado">
    </div>
    <form method="post" action="{{ route('loadExtract') }}" class="col-md-6 form mt-5 mb-5">
        @csrf
        <div class="mb-3 mt-5">
            <label for="exampleFormControlInput1" class="form-label">Card Kit Number:</label>
            <input type="text" class="form-control" id="cardkitFront" name="cardkitFront" required>
        </div>
        
        <h5>Moeda: TRX.USDT</h5>
        <button type="submit" class="btn btn-primary mt-3">Visualizar Extrato</button>
    </form>

    @if(isset($balance) || isset($transactions))
    <div id="results-section" class=" container col-md-8 mt-5">
        @if(isset($balance))
        <h4 class="text-center">Saldo Disponível: {{ $balance }}</h4>
        @endif

        @if(isset($transactions) && count($transactions) > 0)
        <h4 class="text-center">Transações</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Card Number</th>
                    <th>Time</th>
                    <th>Description</th>
                    <th>Processing Amount</th>
                    <th>Authorization No</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction['card_number'] }}</td>
                    <td>{{ $transaction['time'] }}</td>
                    <td>{{ $transaction['description'] }}</td>
                    <td>{{ $transaction['processing_amount'] }}</td>
                    <td>{{ $transaction['authorizationNo'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a hash in the URL to determine if we should scroll
    if (window.location.hash === '#results-section') {
        // Scroll to the results section
        document.querySelector('#results-section').scrollIntoView({ behavior: 'smooth' });
    }
});
</script>

@endsection
