<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    @include('includes.head')
    </head>

    <body>
<div class="container-fluid">
   <header class="row w100">
       @include('includes.header')
   </header>
   <div id="main" class="row" style="height: calc(100% - 50px)">
           @yield('content')
   </div>
   <footer class="row text-center fixed-bottom" >
       @include('includes.footer')
   </footer>
</div>
    </body>
</html>

