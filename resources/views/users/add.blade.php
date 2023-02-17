@extends('layout')

@section('content')
    <!-- Content Header (Page header) -->

    <style>
        .eye-info .form-control {
            position: relative;
        }

        .eye-info .input-group-append {
            position: absolute;
            right: 6px;
            top: 32px;
        }

        .eye-info .input-group-text {
            padding: 10px 15px;
        }

        .iti {
            width: 100%;
        }

        .error {
            color: rgb(235, 18, 18);
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        {{-- <li class="breadcrumb-item active"><a href="{{route('admin.mentee.list')}}"> Mentee List</a></li> --}}
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">

                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Add User</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('store.user') }}" enctype="multipart/form-data" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" name="first_name"
                                                placeholder="Enter First Name">
                                            @if ($errors->has('first_name'))
                                                <div class="error">{{ $errors->first('first_name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control" name="last_name"
                                                placeholder="Enter Last Name">
                                            @if ($errors->has('last_name'))
                                                <div class="error">{{ $errors->first('last_name') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email"
                                                placeholder="Enter Email">
                                            @if ($errors->has('email'))
                                                <div class="error">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-block">Mobile</label>
                                            <input type="tel" id="txtPhone" class="form-control w-100 txtbox"
                                                name="mobile" placeholder="Enter mobile">
                                            @if ($errors->has('mobile'))
                                                <div class="error">{{ $errors->first('mobile') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Profile Image</label> <br>
                                            <input type="file" onchange="openFile(this)" class="form-control h-100"
                                                name="image">
                                        </div>

                                        <div class="image_preview">

                                        </div>
                                    </div> --}}

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-block">Country</label>
                                            <select id="country-dropdown" class="form-control">
                                                <option value="">-- Select Country --</option>
                                                @foreach ($countries as $data)
                                                    <option value="{{ $data->id }}">
                                                        {{ $data->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-block">State</label>
                                            <select id="country-dropdown" class="form-control">
                                                <option value="">-- Select state --</option>
                                                @foreach ($state as $data)
                                                    <option value="{{ $data->id }}">
                                                        {{ $data->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-block">city</label>
                                            <select id="country-dropdown" class="form-control">
                                                <option value="">-- Select state --</option>
                                                @foreach ($city as $data)
                                                    <option value="{{ $data->id }}">
                                                        {{ $data->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-group recipe-step-0 mb-1">Step</label>
                                            <input type="text" name="step" id="step-text_0"
                                                class="form-control total-steps" value="" autocomplete="off">
                                            <div class="plus-minus ml-2">
                                                <a type="button" href="javascript:void(0)" step-id="0"
                                                    onclick="stepMinus(this)"><i class="fa fa-minus-circle"></i></a>
                                                <a type="button" href="javascript:void(0)" step-id="0"
                                                    onclick="stepPlus(this)"><i class="fa fa-plus-circle"></i></a>
                                            </div>
                                            @if ($errors->has('steps'))
                                                <div class="error">{{ $errors->first('steps') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- <div class="col-lg-6">
                              <div class="form-group">
                                  <label>Password</label>
                                  <div class="eye-info">
                                  <input type="password" id="password-field" class="form-control" name="password" placeholder="Enter Password">
                                
                                  <div class="input-group-append">
                                    <div class="input-group-text">
                                      <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    </div>
                                  </div>
                                  </div>
                                  @if ($errors->has('password'))
                                  <div class="error">{{ $errors->first('password') }}</div>
                                  @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                  <label>Confirm Password</label>
                                  <div class="eye-info">
                                  <input type="password"  id="password" class="form-control" name="confirm_password"  placeholder="Enter Confirm password">
                                  <div class="input-group-append">
                                    <div class="input-group-text">
                                      {{-- <span class="far fa-eye"></span> --}}
                                    {{-- <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    </div>
                                  </div>
                                  </div>
                                  @if ($errors->has('confirm_password'))
                                  <div class="error">{{ $errors->first('confirm_password') }}</div>
                                  @endif
                                </div>
                            </div> --}}
                                    {{-- </div>  --}}
                                    <!-- /.card-body -->
                                    <div class="col-md-6 pl-0">
                                        <button id='Submit' type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    {{-- <script>
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script> --}}
    <script>
        function stepPlus(event) {
            result = checkSteps();
            if (result == false) {
                return false;
            } else {
                stepLength = $('.total-steps').length;
                stepId = $(event).attr('step-id');

                $('.recipe-step-' + stepId).after('<div class="form-group mb-1 recipe-step-' + stepLength + '">\
                                <div class="d-flex align-items-center">\
                                    <input type="text" name="step[' + stepLength + ']" id="step-text_' + stepLength +
                    '" data-id="' + stepLength + '"  class="form-control total-steps" value="" autocomplete="off">\
                                    <div class="plus-minus ml-2">\
                                        <a type="button" href="javascript:void(0)" step-id="' + stepLength + '" onclick="stepMinus(this)" ><i class="fa fa-minus-circle"></i></a>\
                                        <a type="button" href="javascript:void(0)" step-id="' + stepLength + '" onclick="stepPlus(this)" ><i class="fa fa-plus-circle"></i></a>\
                                    </div>\
                                </div>\
                                    <div style="color:#dc3545;"><span id="stepErr_' + stepLength + '"></span></div>\
                                </div>\
                            ');
            }
        }

        function stepMinus(event) {
            stepLength = $('.total-steps').length;
            if (stepLength > 1) {
                stepId = $(event).attr('step-id');
                $('.recipe-step-' + stepId).remove();
            }
        }

        function checkSteps() {
            isVaildStep = true;
            $.each($('.total-steps'), function(index, value) {
                rowId = value.id.replace("step-text_", "");

                text = $.trim(value.value);
                // if (text == '') {
                //     isVaildStep = false;
                //     $('#stepErr_' + rowId).html('Step is required!');
                // } else {
                //     $('#stepErr_' + rowId).html('');
                // }
            });

            return isVaildStep;
        }
    </script>
@endsection
