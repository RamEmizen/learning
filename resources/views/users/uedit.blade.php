@extends('layout')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        {{-- <li class="breadcrumb-item active"><a href="{{ route('admin.cms.page') }}">Cms pages</a></li> --}}
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
                        <div class="card-header">
                            <h3 class="card-title">Edit User</h3>
                        </div>
                        <div class="card-body">
                            <form id='cms-page-form' action="" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" value="{{ $user->id }}" name="id" />
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{ __('First Name') }}</span></label>
                                            <input class="form-control" id="title" name="title" type="text"
                                                minlength="1" maxlength="255" title="Title"
                                                placeholder="{{ __('Enter Title Name') }}"
                                                autocomplete="off" value="{{ $user->first_name }}">
                                            @if ($errors->has('title'))
                                                <div class="invalid-feedback" style="display:block;">
                                                    {{ $errors->first('title') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{ __('Last Name') }}</span></label>
                                            <input class="form-control" id="title" name="Last_name" type="text"
                                                minlength="1" maxlength="255" title="Title"
                                                placeholder="{{ __('Enter Title Name') }}"
                                                autocomplete="off" value="{{$user->last_name }}">
                                            @if ($errors->has('title'))
                                                <div class="invalid-feedback" style="display:block;">
                                                    {{ $errors->first('title') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{ __('Mobile') }}</span></label>
                                            <input class="form-control" id="title" name="title" type="text"
                                                minlength="1" maxlength="255" title="Title"
                                                placeholder="{{ __('Enter Title Name') }}"
                                                autocomplete="off" value="{{ $user->mobile }}">
                                            @if ($errors->has('title'))
                                                <div class="invalid-feedback" style="display:block;">
                                                    {{ $errors->first('title') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{ __('Email') }}</span></label>
                                            <input class="form-control" id="title" name="title" type="text"
                                                minlength="1" maxlength="255" title="Title"
                                                placeholder="{{ __('Enter Title Name') }}"
                                                autocomplete="off" value="{{ $user->email }}">
                                            @if ($errors->has('title'))
                                                <div class="invalid-feedback" style="display:block;">
                                                    {{ $errors->first('title') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{ __('Image') }}</span></label>
                                                <div class="my-2">
                                                    <input type="file" name="file" id="file" accept="image/*" class="form-control">
                                                  </div>
                                        
                                                  <img src="{{ asset(''.$user->image) }}" class="img-fluid img-thumbnail" width="150">


                                            @if ($errors->has('title'))
                                                <div class="invalid-feedback" style="display:block;">
                                                    {{ $errors->first('title') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                   
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-primary" type="submit"
                                        onclick='saveData()'>{{ __('Submit') }}</button>
                                   
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection