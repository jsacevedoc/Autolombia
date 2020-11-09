@extends('layouts.master')
@section("title", 'Show')
@section('content')
</header>
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-7">
                    <h3 class="card-title"> {{  $data['car']->getBrand() . ' ' . $data['car']->getModel() }} </h3>
                    <i class="fas fa-tachometer-alt" style="font-size:25px; float:left; margin: 0 10px 0 0"></i> 
                    <h4> {{  $data['car']->getMileage() . ' ' . __('app.miles') }} </h4>
                </div>
                <div class="col-lg-5">
                    <i class="fas fa-dollar-sign" style="font-size:25px; float:left; margin: 0 10px 0 0"></i> 
                    <h4 class="card-title"> {{ 'USD' . ' ' . $data['car']->getPrice() }} </h4>
                    @guest
                    <a class="btn btn-info btn-xs" href="{{ route('login') }}" > 
                        <h4 class="d-inline"> {{ __('app.login') }} </h4>
                        <i class="fas fa-user" style="font-size:20px; margin:auto"></i>
                    </a>
                    @else
                    <a class="btn btn-success btn-xs" href="{{ route('order', $data['car']->getId()) }}"> 
                        <h4 class="d-inline"> {{ __('app.buy') }} </h4>
                        <i class="fas fa-shopping-cart" style="font-size:20px; margin:auto"></i>
                    </a>
                    @endguest
                </div>
                <hr style="width:95%">
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <img src="{{ asset('./storage/' . $data['car']->getImagePath()) }}" 
                    style="height: 100%; width: 100%; object-fit: contain;">
                </div>
                <div class="col-lg-5">
                    <p class="card-body"> 
                        <b> {{ __('app.color') . ': ' }} </b> {{ trans($data['car']->getColor()) }} <br/>
                        <b> {{ __('app.license_plate') . ': ' }} </b> {{ trans($data['car']->getLicensePlate()) }} <br/>
                        <b> {{ __('app.description') . ': ' }} </b> {{  $data['car']->getDescription() }} 
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="col-lg-12">
                <h4 class="card-title d-flex justify-content-center"> {{ __('app.questions_title') }} </h4>
                <hr style="width:100%">
            </div>
            <div class="col-lg-12">
                <form method="POST" action="{{ route('car.question', $data['car']->getId()) }}">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-10 float-left">
                            <input class="form-control" type="text" placeholder=" {{ __('app.questions_placeholder') }} " 
                             name="question" value="{{ old('question') }}" style="width:100%">
                            <input class="form-control" type="hidden" name="car_id" value="{{ $data['car']->getId() }}" >
                            @guest
                            @else
                            <input class="form-control" type="hidden" name="user_id" value="{{ Auth::user()->id }}" >
                            @endguest
                        </div>
                        <div class="col-lg-2 float-left">
                            @guest
                            <a class="btn btn-info btn-xs" href="{{ route('login') }}" > 
                                <h4 class="d-inline"> {{ __('app.login') }} </h4>
                                <i class="fas fa-user" style="font-size:20px; margin:auto"></i>
                            </a>
                            @else
                            <input type="submit" class="btn btn-info" value="{{ __('app.post') }}">
                            @endguest
                        </div>
                    </div>
                </form>
            </div>
            <ul>
                @foreach($data["questions"] as $question)
                <div class="col-lg-12">
                    <li>
                    @if ($question->user->getId() == Auth::user()->id)
                        <form method="POST" action="{{ route('car.question.delete', $question->getId()) }}">
                            {{ $question->getQuestion() }}
                            <b> {{ __('app.questions_by') . $question->user->getName() }} </b>
                            <input type="submit" class="btn btn-danger float-right" value="{{ __('app.questions_delete') }}">
                            @method('delete')
                            @csrf
                        </form>
                    @else
                        {{ $question->getQuestion() }}
                        <b> {{ __('app.questions_by') . $question->user->getName() }} </b>
                    @endif
                        <ul>
                            <div class="card-body">
                                @if(!empty($question->answers->toArray()))
                                    <h6> {{ __('app.answers_title') }} </h6>
                                @endif
                                @foreach($question->answers as $answer)
                                    <li>
                                        {{ $answer->getAnswer() }}
                                    </li>
                                @endforeach  
                            </div>  
                        </ul>
                    </li>
                </div>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection