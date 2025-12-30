@extends('layouts.app')
@section('title', 'Referrals | DecentraX Admin')
@section('ogtitle', 'Referrals | DecentraX Admin')
@section('header')

@section('content')
    <style>
        .parent {
            position: relative;
            /* Enables positioning of the after pseudo-element */
        }

        .parent:after {
            content: "";
            position: absolute;
            left: 50%;
            /* Center the line horizontally */
            transform: translateX(-50%);
            /* Offset for centering */
            bottom: 0;
            /* Start at the bottom of the parent */
            width: 1px;
            /* Set the width of the line */
            height: 15%;
            /* Extend the line to the top of child elements */
            background-color: #ddd;
            /* Set the line color */
        }

        .child-1 {
            position: relative;
            /* Allows child elements to overlap the line */
        }

        .parent {
            /* border-bottom: 1px solid #ddd; */
            /* Define line style */
        }

        .child-1 {
            padding-top: 1px;
            /* Offset content to avoid overlapping the line */
        }



        .half-border:after {
            content: "";
            position: absolute;
            bottom: 0;
            /* Position at the bottom */
            left: 50%;
            /* Start from the center */
            width: 50%;
            /* Set width to half */
            height: 1px;
            /* Match the border thickness */
            background-color: #ccc;
            /* Set the background color (same as border) */
        }
    </style>

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Referrals</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Referrals Tree</li>
            </ol>
        </nav>

        <!-- Content Row -->

        <div class="row">

            <!-- Tree view -->
            <iframe src="{{ url('/referrals/tree-view') }}" width="100%" height="1000" frameborder="0"></iframe>

        </div>

    </div>
@endsection
@push('style')
<style>
    /*----------------genealogy-scroll----------*/

.genealogy-scroll::-webkit-scrollbar {
    width: 5px;
    height: 8px;
}
.genealogy-scroll::-webkit-scrollbar-track {
    border-radius: 10px;
    background-color: #e4e4e4;
}
.genealogy-scroll::-webkit-scrollbar-thumb {
    background: #212121;
    border-radius: 10px;
    transition: 0.5s;
}
.genealogy-scroll::-webkit-scrollbar-thumb:hover {
    background: #d5b14c;
    transition: 0.5s;
}


/*----------------genealogy-tree----------*/
.genealogy-body{
    white-space: nowrap;
    overflow-y: hidden;
    padding: 50px;
    min-height: 500px;
    padding-top: 10px;
    text-align: center;
}
.genealogy-tree{
  display: inline-block;
}
.genealogy-tree ul {
    padding-top: 20px;
    position: relative;
    padding-left: 0px;
    display: flex;
    justify-content: center;
}
.genealogy-tree li {
    float: left; text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;
}
.genealogy-tree li::before, .genealogy-tree li::after{
    content: '';
    position: absolute;
  top: 0;
  right: 50%;
    border-top: 2px solid #ccc;
    width: 50%;
  height: 18px;
}
.genealogy-tree li::after{
    right: auto; left: 50%;
    border-left: 2px solid #ccc;
}
.genealogy-tree li:only-child::after, .genealogy-tree li:only-child::before {
    display: none;
}
.genealogy-tree li:only-child{
    padding-top: 0;
}
.genealogy-tree li:first-child::before, .genealogy-tree li:last-child::after{
    border: 0 none;
}
.genealogy-tree li:last-child::before{
    border-right: 2px solid #ccc;
    border-radius: 0 5px 0 0;
    -webkit-border-radius: 0 5px 0 0;
    -moz-border-radius: 0 5px 0 0;
}
.genealogy-tree li:first-child::after{
    border-radius: 5px 0 0 0;
    -webkit-border-radius: 5px 0 0 0;
    -moz-border-radius: 5px 0 0 0;
}
.genealogy-tree ul ul::before{
    content: '';
    position: absolute; top: 0; left: 50%;
    border-left: 2px solid #ccc;
    width: 0; height: 20px;
}
.genealogy-tree li a{
    text-decoration: none;
    color: #666;
    font-family: arial, verdana, tahoma;
    font-size: 11px;
    display: inline-block;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
}

.genealogy-tree li a:hover+ul li::after,
.genealogy-tree li a:hover+ul li::before,
.genealogy-tree li a:hover+ul::before,
.genealogy-tree li a:hover+ul ul::before{
    border-color:  #fbba00;
}

/*--------------memeber-card-design----------*/
.member-view-box{
    padding:0px 20px;
    text-align: center;
    border-radius: 4px;
    position: relative;
    width: 200px;
}
.member-image{
    width: 60px;
    position: relative;
}
.member-image img{
    width: 60px;
    height: 60px;
    border-radius: 6px;
  background-color :#000;
  z-index: 1;
}

</style>
@endpush
@push('scripts')
    <script>
        $(function () {
    $('.genealogy-tree ul').hide();
    $('.genealogy-tree>ul').show();
    $('.genealogy-tree ul.active').show();
    $('.genealogy-tree li').on('click', function (e) {
        var children = $(this).find('> ul');
        if (children.is(":visible")) children.hide('fast').removeClass('active');
        else children.show('fast').addClass('active');
        e.stopPropagation();
    });
});

    </script>
@endpush
