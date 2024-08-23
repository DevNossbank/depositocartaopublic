@extends('layouts.app')

@section('content')

<link rel="stylesheet" type="text/css" href="/css/style.css">


    <div>
        <div class="row">
            <h4 class="display-6 text-center mt-3">Gerar depósito para cartão</h4>
            <div class="col-md-5 leftPart">
                <img src="/img/CARTAO-1.png" class="img-fluid">
            </div>
            <div class="col-md-6 form mt-5 mb-5">
                <div class="mb-3 mt-5">
                    <label for="exampleFormControlInput1" class="form-label">Card Kit Number:</label>
                    <input type="text" class="form-control" id="cardkitFront">
                  </div>
                  <div class="">
                    <label for="exampleFormControlTextarea1" class="form-label">Quantia:</label>
                    <input type="text" class="form-control" id="amountFront">
                    <p>Inserir apenas duas casas decimais e separador decimal deve ser "."</p>
                  </div>
                  <h5>Moeda: TRX.USDT</h5>
                  <button type="button" class="btn btn-primary mt-3 btn" onclick="fetchAPI()">Gerar link</button>
                </br> </br>
                  <a href="#" id="link" style="display: none;" target="_blank"><button type="button" class="btn btn-warning mt-3 btn" onclick="reload()">Abrir Checkout</button></a>
            </div>
        </div>
    </div>

    <script>
        function fetchAPI(){
            cardkitFront = document.getElementById('cardkitFront').value;
            amountFront = document.getElementById('amountFront').value;

            if(cardkitFront!="" && amountFront!=""){

                try{
                    $.ajax({
                        type: 'POST',
                        url: '/loadDeposit',
                        data: {
                            cardkitFront: cardkitFront,
                            amountFront: amountFront,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            console.log(response);
                            
                            var meuLink = document.getElementById("link");
                            meuLink.href = response;
                            meuLink.style.display = "inline";

                        
                        },
                        error: function (error) {
                            console.error(error);
                            alert('Tente novamente mais tarde');
                            //location.reload();
                        }
                    });
                }
                catch(error){
                    console.log(error)
                }
            }

        }

        function reload(){
            location.reload();
        }
    </script>
@endsection
