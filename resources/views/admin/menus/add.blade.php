@extends('layouts.admin')

@section('title', 'Trang Chu')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('partials.content-header',['name' =>'Menu', 'key'=> 'add'])
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                <form action="{{ route('menus.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label>Tên Menu</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            placeholder="Nhập tên menu"
                            name="name"
                        >
                    </div>
                    <div class="form-group">
                        <label>Chọn menu cha</label>
                        <select class="form-control" name="parent_id">
                            <option value="0">Chọn menu cha</option>
                            {!! $optionSelect !!}
                        </select>
                        
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                </div>   
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
@endsection