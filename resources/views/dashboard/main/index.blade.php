@extends('dashboard.layout.master')
@section('title', 'Beranda | RSKM Regina Eye Center')
@section('menuDashboard', 'active')

@section('content')
    {{--  Admin  --}}
    @if ($users->level_id == '1')
        @include('admin.index')

        {{--  Karyawan  --}}
    @elseif ($users->level_id != '1')
        @include('karyawan.index')
    @endif
@endsection
