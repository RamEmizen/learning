@extends('layout')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">View User list</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    {{-- <li class="breadcrumb-item active"><a href="{{ route('admin.cms.page') }}">User List</a></li> --}}
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-secondary">
                <div class="row">
                <div class="col-sm-12 mb-4">
                    <div class="card-header">
                        <h3 class="card-title">User Details</h3>
                    </div>
                </div>
                </div>
                <div class="card-body">
                <table id="dataTable" class="table table-striped table-bordered table-hover">

                    @foreach ($user as $users)
        
          
                    <tbody>
                        <tr>
                            <th>First Name:</th>
                            <td>
                                {{ $users->first_name }}
                            </td>
                        </tr>
                        <tr>
                            <th> Last Name:</th>
                            <td>
                                {!! @$users->last_name!!}
                            </td>
                        </tr>

                        <tr>
                            <th> Email:</th>
                            <td>
                                {{ $users->email}}
                            </td>
                        </tr>
                        <tr>
                            <th> Mobile:</th>
                            <td>
                                {{ $users->mobile}}
                            </td>
                        </tr>
                        <tr>
                            <th> Image:</th>
                            <td>
                                <img src="{{ asset($users->image) }}" width="200px" alt="">
                            </td>
                        </tr>
                    </tbody>
                    @endforeach
                </table>
                </div>       
        </div>
    </div>
</div>
    </div>
</section>
@endsection

