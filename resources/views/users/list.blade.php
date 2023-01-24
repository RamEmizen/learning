@extends('layout')

@section('content')
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0">Cms Pages </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">User list</li>
                    </ol>
                </div>
            </div>
   
    <!-- /.content-header -->
    <section class="content">
            <div class="row">
                <div class="col-md-12">
                        <div class="card-header-admin d-flex justify-content-between">
                            <h3 class="card-title">User list</h3>
                            @if(Auth::user()->roles[0]->id!=1)
                            <a href="{{ route('add.user')}}" class="card-title">+ Add user</a>
                            @endif
                        </div>
                        <div class="card-body">
                            <table id="category_tbl" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">SR. #</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Mobile</th>
                                        <th scope="col">Image</th>
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
                                            <td>@if ($pages->image)
                                                <div class="img-wrap">
                                                    <img src="{{ asset($pages->image) }}" alt="">
                                                </div>
                                            @else
                                                N/A
                                            @endif</td>
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


