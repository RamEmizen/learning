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
    {{-- <p>Date: <input type="text" name="date" id="datepicker" value="{{$date}}"readonly></p> --}}

    {{-- <p>Filter: <input type="text" name="first_name"  value="{{$name}}"></p> --}}
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


                <h2 class="text-center mb-3">Laravel HTML to PDF Example</h2>
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

{{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script> --}}
<script type="text/javascript">
    function DownloadFile(fileName) {
        //Set the File URL.
        var url = "Files/" + fileName;

        $.ajax({
            url: "{{route('user.list')}}",
            cache: false,
            xhr: function () {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            xhr.responseType = "text";
                        }
                    }
                };
                return xhr;
            },
            success: function (data) {
                //Convert the Byte Data to BLOB object.
                var blob = new Blob([data], { type: "application/octetstream" });
             
                //Check the Browser type and download the File.
                var isIE = false || !!document.documentMode;
                if (isIE) {
                    window.navigator.msSaveBlob(blob, fileName);
                } else {
                    var url = window.URL || window.webkitURL;
                    link = url.createObjectURL(blob);
                    var a = $("<a />");
                    a.attr("download", fileName);
                    a.attr("href", link);
                    $("body").append(a);
                    a[0].click();
                    $("body").remove(a);
                }
            }
        });
    };
</script>

@endsection
