@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" style="float:left; width:100%;">
                    <span style="float:left;padding-top:10px;">Dashboard</span>
                    <a class="btn btn-default" href="/reporting" style="float:right;">See report</a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <!-- Client info -->
                    <div class="col-md-12">
                        <h3>Client information</h3>
                        <p>Balance: <span>{{auth()->user()->balance}}</span></p>
                        <p>Bonus: <span>{{auth()->user()->bonus}}</span></p>
                        <p>Bonus params: <span>{{auth()->user()->bonus_param}}%</span></p>
                    </div>
                    <!-- Client actions -->
                    <div class="col-md-12">
                        <h3>Client actions</h3>
                        <div class="col-md-6" style="padding-left: 0;">
                            <form>
                                <div class="col-md-12" style="padding-left: 0; display:flex; flex-direction: column">
                                    <label>Deposit amount</label>
                                    <input type="text" name="deposit" />
                                    <span style="color:red;">Error message here</span>
                                    <input class="col-md-4" type="submit" class="btn" value="Send"/>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form>
                                <div class="col-md-12" style="display:flex; flex-direction: column">
                                    <label>Withdraw amount</label>
                                    <input type="text" name="withdraw" />
                                    <span style="color:red;">Error message here</span>
                                    <input class="col-md-4" type="submit" class="btn" value="Send"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
