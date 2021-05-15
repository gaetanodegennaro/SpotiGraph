@extends('app')

@section('content')


    <div class="wrapper fadeInDown" style="height: 100%;">
        <div id="formContent">

            <br />

            <!-- Icon -->
            <div class="fadeIn first" style="margin-top: 5%;">
                <img src="{{asset('images/logo.png')}}" style="width:300px;" id="icon"/>
                <br /><br />
                <h1 style="color:white; font-size: 66px;" class="fadeIn second">SPOTIGRAPH</h1>
            </div>



            <!-- Login Form -->
            <form style="margin-top:3%;" method="GET" action="{{Route("start")}}">
                <input type="submit" class="fadeIn third" value="Avvia">
            </form>

            <form action="{{Route('start')}}" method="POST">

            </form>
        </div>
    </div>

@endsection
