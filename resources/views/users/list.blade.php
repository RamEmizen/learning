@extends('layout')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">User list</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active">User list</li>
            </ol>
        </div>
    </div>

    <!--date picker -->
    <form action="{{route('user.list')}}" enctype="multipart/form-data" method="post">
     @csrf
    <p>Date: <input type="text" name="date" id="datepicker" value="{{$date}}"readonly></p>

    <p>Filter: <input type="text" name="first_name"  value="{{$name}}"></p>
    <button id='Submit' type="submit" class="btn btn-primary">Submit</button>
   </form>

   <form action="{{route('date.filter')}}" method="GET">
    <div class="input-group mb-3">
        <input type="date" class="form-control" name="start_date">
        <input type="date" class="form-control" name="end_date">
        <button class="btn btn-primary" type="submit">GET</button>
        <a href="{{route('user.list')}}" class="btn btn-primary" >Reset</a>
    </div>
</form>
    <!-- /.content-header -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header-admin d-flex justify-content-between">
                    <h3 class="card-title">User list</h3>
                    @if (Auth::user()->roles[0]->id != 2)
                        <a href="{{ route('add.user') }}" class="card-title">+ Add user</a>
                    @endif
                </div>


                <div class="d-flex justify-content-end mb-4">
                    <a class="btn btn-primary" href="{{route('user.list',['download'=>'pdf'])}}">Download PDF</a></a>
                </div> 

                <div class="card-body">
                    <table id="category_tbl" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">SR. #</th>
                                <th scope="col">first Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Mobile</th>
                                <th scope="col">Image</th>
                                <th scope='col'>Time</th>
                                <th scope='col'>Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @php $i=1; @endphp
                            @foreach ($userData as $pages)
                                <tr class="remove_row_<?php echo $pages->id; ?>">
                                    <th scope="row" class="sr.#"> {{ $i++ }}</th>
                                    <td>{{ !empty($pages->first_name) ? $pages->first_name : 'N/A' }}</td>
                                    <td>{{ !empty($pages->last_name) ? $pages->last_name : 'N/A' }}</td>
                                    <td>{{ !empty($pages->email) ? $pages->email : 'N/A' }}</td>
                                    <td>{{ !empty($pages->mobile) ? $pages->mobile : 'N/A' }}</td>
                                    <td>
                                        @if ($pages->image)
                                            <div class="img-wrap">
                                                <img src="{{ asset($pages->image) }}" width="50px" alt="">
                                            </div>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                     <td>{{($pages->created_at->format('Y.m.d'))}}</td>


                                     <td>
                                        @if($pages->status == 1)
                                        <a onclick=""  href="javascript:void(0);" class="actions status_update" data-status="0" id="{{ $pages->id }}"> 
                                            <span class="badge badge-success">Active</span>
                                        </a> 
                                        @else 
                                        <a  onclick="" href="javascript:void(0);" class="actions status_update" data-status="1" id="{{ $pages->id }}">
                                            <span class="badge badge-danger">Deactive</span>
                                        </a> 
                                        @endif 
                                    </td>

                                    <td class="action">
                                        <a href="{{ route('user.edit', $pages->id) }}"><i class="fa fa-edit"></i></a>
                                         <a href="{{route('user.show', $pages->id)}}"><i class="fa fa-eye"></i></a>
                                        <a href="javascript:void(0)" onclick="deletePages('{{ $pages->id }}')"><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                </tr>
                            @endforeach
                        </tbody>
                        <tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script>
    $(document).ready(function() {
      $('#category_tbl').DataTable();
  } );
   </script>

<script>
    $( function() {
      $( "#datepicker" ).datepicker();
    } );
    </script>

   <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function deletePages(id) {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('user.delete') }}",
                            data: {
                                'id': id,
                                '_token': '{{ csrf_token() }}',
                            },
                            type: 'POST',
                            success: function(result) {
                                // var data = JSON.parse(result);
                                if (result.status == 200) {
                                    $('.remove_row_'+id).remove();
                                    swal("Your data has been deleted!", {
                                        icon: "success",
                                    });
                                }
                            }
                        });
                    } else {
                        swal("Your data is safe!");
                    }
                });
        }
    </script>




<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).on("click", ".status_update", function () {
      var status = $(this).attr("data-status");
      var id = $(this).attr("id");
      var this_id = $(this);
      $.ajax({
          url: '{{ route("users.status") }}',
          type: "POST",
          data: {_token: '{{ csrf_token() }}', status: status, id: id},
          dataType: "json",
          success: function (response) {
            if(response.status == "200") {
                if(status == 1){
                  $(this_id).attr("data-status",'0');
                  $(this_id).html('<span class="badge badge-success">Active</span>');
                }else{
                  $(this_id).attr("data-status",'1');
                  $(this_id).html('<span class="badge badge-danger">Deactive</span>');
                }
                swal.fire("Done!", response.message, "success");
            }else{
              swal.fire("Error status!", "Please try again", "error");
            }
          },
      });
    });
    </script>

@endsection
