@extends('layouts.app')
@section('title')
    Home
@endsection
@section('content')
    <button class="btn btn-success btn-sm" data-toggle='modal' data-target="#addProduct">Add Product</button>
    <a href="posts/create" class="btn btn-primary btn-sm">Create Product</a>
    <table class="table table-bordered" id="datatable">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Post By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($posts) > 0)
                @foreach ($posts as $post)
                    <tr>
                    <th>{{$post->id}}</th>
                    <th>
                        @if ($post->product_image !== 'No Image')
                         <img src="/storage/product_images/{{$post->product_image}}" alt="" width="150">
                        @else
                        <img src="" alt="" width="150">
                        @endif
                    </th>
                    <th>{{$post->product_name}}</th>
                    <th>{{$post->price}}</th>
                    <th>{{$post->qty}}</th>
                    <th>{{$post->user->name}}</th>
                    <th>
                        @if (auth()->user()->id !== $post->user->id)
                        <button disabled="disabled" class="btn btn-primary btn-sm">Edit</button>
                        <button disabled="disabled" class="btn btn-danger btn-sm">Delete</button>
                        @else
                            <a href="#" class="btn btn-primary btn-sm edit" data-id='{{$post->id}}' data-toggle='modal' data-target='#modal'>Edit</a>
                            <a href="#" class="btn btn-danger btn-sm delete" data-id='{{$post->id}}' >Delete</a> </th>
                        @endif
                        
                    </tr>
                @endforeach                
            @endif
        </tbody>
    </table>

    <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                        <div class="error_msg">

                        </div>
                </div>
                <div class="modal-body">
                    <div class="container">
                        {!! Form::open(['id'=>'dataForm', 'method' => 'POST' ,'enctype' => 'multipart/form-data'])  !!}
                        <div class="class form-group">
                                {{Form::label('product_name', 'Product Name')}}
                                {{Form::text('product_name','', ['class' => 'form-control', 'placeholder' => 'Product Name'])}}
                                {{Form::label('price', 'Price')}}
                                {{Form::text('price','', ['class' => 'form-control', 'placeholder' => 'Price'])}}
                                {{Form::label('qty', 'Quantity')}}
                                {{Form::text('qty','', ['class' => 'form-control', 'placeholder' => 'Quantity'])}}
                                {{Form::textarea('description','',['class' => 'form-control mt-4', 'placeholder' => 'Description'])}}
                                {{Form::file('product_image',['class' => 'form-control', 'id' => 'filename'])}}
                                {{Form::hidden('_method','PUT')}}
                                
                        </div>
                           
                        </div>
                </div>
                <div class="modal-footer ">
                    {!! Form::submit('Update', ['class' => 'btn btn-primary pull-right']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProduct">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <div class="error_message">

                </div>
                </div>
                <div class="modal-body">
                   
                    <input type="text" name="pname" placeholder="Product Name">
                    <input type="text" name="pr" placeholder="Price">
                    <input type="text" name="qtty" placeholder="Quantity">
              
                </div>
                <div class="modal-footer ">
                        <button id="save" class="btn btn-primary btn-sm">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
         });
         var id = 0;
        $('.edit').click(function(){
             id = $(this).attr('data-id');
            console.log(id);
            $.ajax({
                url:'/posts/'+id,
                success:function(data){
                    $('input[name=product_name]').val(data.product_name);
                    $('input[name=price]').val(data.price);
                    $('input[name=qty]').val(data.qty);
                    $('textarea[name=description]').val(data.description);
                }
            })
        })

        $('#dataForm').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
                $.ajax({
                        url:'/posts/'+id,
                        method:'POST',
                        enctype:'multipart/form-data',
                        data:formData,
                        contentType:false,
                        processData:false,
                        success:function(data){
                            if(data == 1){
                                location.reload();
                            }else{
                                $('.error_msg').attr('class' , 'error_msg alert alert-danger')
                                $.each(data.error , function(){
                                        $('.error_msg').html(data.error);
                                })
                            }
                        }
                    })
             })

        $('.delete').click(function(){
            let id = $(this).attr('data-id');
            const confrm = confirm('You Want To Delete?');
            if(confrm == true){
                $.ajax({
                    url:'/posts/'+id,
                    method:'DELETE',
                    data:{
                        _token:'{{csrf_token()}}'
                    },
                    success:function(data){
                       location.reload();
                    }
                })
            }
        })
        
        $('#save').click(function(){
             $.ajax({
                 url:'posts',
                 method:'POST',
                 data:{
                     _token: '{{csrf_token()}}',
                     product_name:$('input[name=pname]').val(),
                     price:$('input[name=pr]').val(),
                     qty:$('input[name=qtty]').val()
                 },
                 success:function(data){
                     console.log(data);
                     if(data.error){
                        $('.error_message').attr('class' ,'error_message alert alert-danger');
                         $.each(data.error, function(key,value) {
                            $('.error_message').append('<li>'+value+'</li>');
                        })
                     }
                     if(data.success){
                        $('.error_message').attr('class' ,'error_message alert alert-success');
                        $('.error_message').html('<li>'+data.success+'</li>');
                        location.reload();
                     }
                 }
             })
        })      
    })
</script>
@endsection