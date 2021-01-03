@extends('layouts.app')

@section('main')
Welcome to Book area
<br>
<form id="logout">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>
@endsection