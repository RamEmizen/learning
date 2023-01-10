<html>
    <head>
        <title>Learning - @yield('title')</title>
    </head>
    <body>
        @include('include.sidebar')
            This is the master sidebar.
        @show
 
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>

   
@include('include.css')
@yield('css')
@include('include.script')
@yield('js')
<style>
    /* .pcoded-container  */
    #pcoded{
        float:left;
    }
    .m-0 {
    margin: 41!important;
}
    </style>

