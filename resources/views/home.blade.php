@extends('layouts.app')
@section('css')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection
@section('content')
    @php
        $stripe_key = config('services.stripe.key');
    @endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table">
                        <tbody>
                        <tr>
                            <td>Name</td>
                            <td>{{Auth::user()->name}}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{Auth::user()->email}}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{Auth::user()->status==1?'Active':'Inactive'}}</td>
                        </tr>
                        </tbody>
                    </table>
                        @if(Auth::user()->status!=1)
                            <button onclick="activeUser()" class="btn btn-success">Activate</button>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Active Your Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="paymentUserForm">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        cardFormCreated=()=>{
            let style = {
                base: {
                    color: '#32325d',
                    lineHeight: '18px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            const stripe = Stripe('{{ $stripe_key }}', { locale: 'en' });
            const elements = stripe.elements();
            const cardElement = elements.create('card', { style: style });
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;
            cardElement.mount('#card-element');
            cardElement.addEventListener('change', function(event) {
                let displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            let form = document.getElementById('payment-form');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.handleCardPayment(clientSecret, cardElement, {
                    payment_method_data: {
                        //billing_details: { name: cardHolderName.value }
                    }
                })
                    .then(function(result) {
                        console.log(result);
                        if (result.error) {
                            let errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                        } else {
                            form.submit();
                        }
                    });
            });
        }
        activeUser=()=>{
            $.ajax({
                url:'{{route('home')}}',
                type:'GET',
                success: function (data) {
                    $('#paymentUserForm').html(data);
                    $('#modelId').modal('show');
                    cardFormCreated();
                }
            })
        }
    </script>
@endsection
