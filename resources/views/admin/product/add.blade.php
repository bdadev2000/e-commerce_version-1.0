@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('css')
    <link href="{{ asset('/vendors/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/admins/product/add/add.css') }}" rel="stylesheet" />

@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('partials.content-header',['name' =>'Product', 'key'=> 'add'])
    <!-- /.content-header -->

    <!-- Main content -->
    <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Tên sản phẩm</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Nhập tên sản phẩm"
                                name="name"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label>Giá sản phẩm</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Nhập giá sản phẩm"
                                name="price"
                            >
                        </div>

                        <div class="form-group">
                            <label>Ảnh đại diện</label>
                            <input 
                                type="file" 
                                class="form-control-file" 
                                name="feature_image_path"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label>Ảnh chi tiết</label>
                            <input 
                                type="file" 
                                multiple
                                class="form-control-file" 
                                name="image_path[]"
                            >
                        </div>

                        <div class="form-group">
                            <label>Chọn danh mục</label>
                            <select class="form-control select2_init" name="category_id">
                                <option value="">Chọn danh mục</option>
                                {!! $htmlOption !!}
                            </select>  
                        </div>

                        

                        <div class="form-group">
                            <label>Nhập tags</label>
                            <select name="tags[]" class="form-control tags_select_choose" multiple="multiple">

                            </select>
                        </div>

                        
                    </div>   

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nhập nội dung</label>
                            <textarea name="contents" class="form-control my-editor" rows="8"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Submit</button>                 
                   </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
    <!-- /.content -->
    </form>
</div>
@endsection

@section('js')
    <script src=" {{ asset('/vendors/select2/select2.min.js') }} "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.9.1/tinymce.min.js" integrity="sha512-wL4f713UTdXFhzoGj57R7cKAwWMp48nzQ4Z/OLy7r8Hrqsa53x3y9Wl1N27hNktcmmHUABHuIX5xQazAl0VMRg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src=" {{ asset('/admins/product/add/add.js') }} "></script>   
@endsection