@extends('layouts.app')
@section('title')
    Register Account
@endsection
@section('content')
    <div class="container">
    {!! Form::open(['action' => 'PostsController@createAccount', 'method' => 'POST' , 'autocomplete' => 'off', 'enctype' => 'multipart/form-data'])  !!}
    <div class="class form-group">
            {{Form::label('product_name', 'Product Name')}}
            {{Form::text('product_name','', ['class' => 'form-control', 'placeholder' => 'Product Name'])}}
            {{Form::label('price', 'Price')}}
            {{Form::text('price','', ['class' => 'form-control', 'placeholder' => 'Price'])}}
            {{Form::label('qty', 'Quantity')}}
            {{Form::text('qty','', ['class' => 'form-control', 'placeholder' => 'Quantity'])}}

            {{Form::textarea('description','',['class' => 'form-control mt-4', 'placeholder' => 'Description'])}}

            {{Form::file('product_image',['class' => 'form-control'])}}
            {{Form::submit('Submit' , ['class' => 'btn btn-primary mt-2'])}}
    </div>
        {!! Form::close() !!}
    </div>

        
@endsection