<?php
/**
 *Name: Alauddin F-a
 *Designation: Software Engineer
 *Date: 22-Dec-20 8:32 PM
 */ ?>
<form action="{{route('home')}}"  method="post" id="payment-form">
    @csrf
    <div class="form-group">
        <small class="text-muted">Card Number: 4242424242424242</small><br/>
        <small class="text-muted">MM/YY: 12/32</small><br/>
        <small class="text-muted">CVC: 123</small><br/>
        <small class="text-muted">ZIP: 12345</small>
        <div class="card-header">
            <label for="card-element">
                Enter your credit card information
            </label>
        </div>
        <div class="card-body">
            <div id="card-element">
            </div>
            <div id="card-errors" role="alert"></div>
            <input type="hidden" name="amount" value="10" />
        </div>
    </div>
    <div class="card-footer">
        <button
            id="card-button"
            class="btn btn-dark"
            type="submit"
            data-secret="{{ $intent }}">$10 Pay </button>
    </div>
</form>
