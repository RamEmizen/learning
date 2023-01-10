    @extends('layout')
    @section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
    
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
    
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="box bg-white">
                                {{-- <a href="{{route('users')}}"> --}}
                                    <div class="box-row flex-wrap">
                                        <div class="box-content">
                                            <div class="text-muted text-uppercase font-weight-bold small">{{ __('Total User') }}</div>
                                            {{-- <p class="h1 m-0"><i class="fal fa-users"></i>&nbsp;&nbsp; {{count_users()}}</p> --}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="box bg-white">
                                {{-- <a href="{{route('users')}}"> --}}
                                    <div class="box-row flex-wrap">
                                        <div class="box-content">
                                            <div class="text-muted text-uppercase font-weight-bold small">{{ __('Deactive User') }}</div>
                                            {{-- <p class="h1 m-0"><i class="fal fa-user-minus"></i>&nbsp;&nbsp; {{count_deactive_users()}}</p> --}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="box bg-white">
                                {{-- <a href="{{route('users')}}"> --}}
                                    <div class="box-row flex-wrap">
                                        <div class="box-content">
                                            <div class="text-muted text-uppercase font-weight-bold small">{{ __('Active User') }}</div>
                                            {{-- <p class="h1 m-0"><i class="fal fa-user-check"></i>&nbsp;&nbsp; {{count_active_users()}}</p> --}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection



@section('css')
<style>
    .card-body {
    position: relative;
    padding-bottom: 40%;
    height: 0;
    overflow:hidden;
}

.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -760px;
    margin-left: -581px;
}

#chart_div {
    position: absolute;
    top: 0;
    left: 0;
    width:100%;
    height:100%;
}
</style>
@endsection
@section('js')

@endsection 