@extends('backEnd.layouts.master') 

@section('title','Product Create') 
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
  .increment_btn,
  .remove_btn {
    margin-top: -17px;
    margin-bottom: 10px;
  }
</style>

<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/css/variable.css" rel="stylesheet" type="text/css" />
@endsection @section('content')
<div class="container-fluid">
  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <a href="{{route('products.index')}}" class="btn btn-primary rounded-pill">Manage</a>
        </div>
        <h4 class="page-title">Product Create</h4>
      </div>
    </div>
  </div>
  <!-- end page title -->
    <form action="{{route('products.store')}}" method="POST" data-parsley-validate="" enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-center">
      
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                    <div class="col-sm-12"><h3>Basic Information</h3></div>
                  
                    <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <div class="input-group">
                              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" required />
                              <button type="button" class="btn btn-primary" id="translateBtn" onclick="autoTranslate()">Translate</button>
                            </div>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
            
                        <div class="col-sm-6">
                          <div class="form-group mb-3">
                            <label for="name_bn" class="form-label">Product Bangla Name *</label>
                            <input type="text" class="form-control @error('name_bn') is-invalid @enderror" name="name_bn" value="{{ old('name_bn') }}" id="name_bn" required="" />
                            @error('name_bn')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        
                        <!-- col-end -->
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="category_id" class="form-label">Categories *</label>
                            <select class="form-control select2 @error('category_id') is-invalid @enderror" name="category_id" value="{{ old('category_id') }}" id="category_id" required>
                              <option value="">Select..</option>
                              @foreach($categories as $category)
                              <option value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                            </select>
                            @error('category_id')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="subcategory_id" class="form-label">SubCategories (Optional)</label>
                            <select class="form-control select2 @error('subcategory_id') is-invalid @enderror" id="subcategory_id" name="subcategory_id" data-placeholder="Choose ...">
                              <optgroup>
                                <option value="">Select..</option>
                              </optgroup>
                            </select>
                            @error('subcategory_id')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="childcategory_id" class="form-label">Child Categories (Optional)</label>
                            <select class="form-control select2 @error('childcategory_id') is-invalid @enderror" id="childcategory_id" name="childcategory_id" data-placeholder="Choose ...">
                              <optgroup>
                                <option value="">Select..</option>
                              </optgroup>
                            </select>
                            @error('childcategory_id')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col end -->
                    </div>
                    <!-- row-end -->
                    
                    <div class="row prev_step">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-info" id="nextStepBtn" disabled>Confirm</button>
                        </div>
                    </div>
                    
                    <div class="row d-none next_step">
                        <div class="col-sm-6 mb-3">
                          <label for="image">Image *</label>
                          <div class="clone hide" style="display: none;">
                            <div class="control-group input-group">
                              <input type="file" name="image[]" class="form-control" />
                              <div class="input-group-btn">
                                <button class="btn btn-danger" type="button"><i class="fa fa-trash"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="input-group control-group increment">
                            <input type="file" name="image[]" class="form-control @error('image') is-invalid @enderror" />
                            <div class="input-group-btn">
                              <button class="btn btn-success btn-increment" type="button"><i class="fa fa-plus"></i></button>
                            </div>
                            @error('image')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col end -->
                        
                        <div class="col-sm-6">
                          <div class="form-group mb-3">
                            <label for="pro_video" class="form-label">Product Video (Optional)</label>
                            <input type="text" class="form-control @error('pro_video') is-invalid @enderror" name="pro_unit" value="{{ old('pro_video') }}" id="pro_video" />
                            @error('pro_video')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col-end -->
                    </div>
                    <!-- row-end -->
                    
                </div>
                <!-- end card-body-->
              </div>
              <!-- end card-->
            </div>
            <!-- end col-->
            
            <div class="col-lg-12 d-none next_step">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-sm-12"><h3>Product Specification</h3></div>
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="category_id" class="form-label">Sku</label>
                           <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') }}" id="sku" />
                            @error('sku')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col-end -->
            
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="tags" class="form-label">Tags 
                              <small class="text-muted">(Press Enter after each)</small>
                            </label>
                            <input name="tags" id="tagsInput"
                                   class="form-control @error('tags') is-invalid @enderror"
                                   placeholder="e.g. electronics, mobile, gadget"
                                   value="{{ old('tags') }}" />
                            @error('tags')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
            
            
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="category_id" class="form-label">Brands</label>
                            <select class="form-control select2 @error('brand_id') is-invalid @enderror" value="{{ old('brand_id') }}" name="brand_id">
                              <option value="">Select..</option>
                              @foreach($brands as $value)
                              <option value="{{$value->id}}">{{$value->name}}</option>
                              @endforeach
                            </select>
                            @error('brand_id')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col end -->
                    </div>
                </div>
            </div>
            <!-- end col-->
            
            <div class="col-lg-12 d-none next_step">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-sm-12"><h3>Price, Stock & Variants</h3></div>
                        <div class="col-sm-6">
                          <div class="form-group mb-3">
                            <label for="pro_unit" class="form-label">Product Unit (Optional)</label>
                            <input type="text" class="form-control @error('pro_unit') is-invalid @enderror" name="pro_unit" value="{{ old('pro_unit') }}" id="pro_unit" />
                            @error('pro_unit')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        
            
                        <div class="col-sm-6">
                          <div class="form-group mb-3">
                            <label for="roles" class="form-label">Size (Option)</label>
                            <select class="form-control select2" name="proSize[]" multiple="multiple">
                              <option value="">Select</option>
                              @foreach($sizes as $size)
                              <option value="{{$size->id}}">{{$size->sizeName}}</option>
                              @endforeach
                            </select>
                            @error('sizes')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                         <!--col end -->
                        <div class="col-sm-6">
                          <div class="form-group mb-3">
                            <label for="color" class="form-label">Color (Optional)</label>
                            <select class="form-control select2" name="proColor[]" multiple="multiple">
                              <option value="">Select</option>
                              @foreach($colors as $color)
                              <option value="{{$color->id}}">{{$color->colorName}}</option>
                              @endforeach
                            </select>
                            @error('colors')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-sm-12">
                         @include('backEnd.product.variable')
                        </div>
                         <!--col end -->
                         
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="purchase_price" class="form-label">Purchase Price *</label>
                            <input type="text" class="form-control @error('purchase_price') is-invalid @enderror" name="purchase_price" value="{{ old('purchase_price') }}" id="purchase_price" required />
                            @error('purchase_price')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col-end -->
                        <!-- col-end -->
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="old_price" class="form-label">Old Price *</label>
                            <input type="text" class="form-control @error('old_price') is-invalid @enderror" name="old_price" value="{{ old('old_price') }}" id="old_price" />
                            @error('old_price')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="new_price" class="form-label">New Price *</label>
                            <input type="text" class="form-control @error('new_price') is-invalid @enderror" name="new_price" value="{{ old('new_price') }}" id="new_price" required />
                            @error('new_price')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-4">
                          <div class="form-group mb-3">
                            <label for="stock" class="form-label">Stock *</label>
                            <input type="text" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock') }}" id="stock" />
                            @error('stock')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col-end -->
                    </div>
                </div>
            </div>
            <!-- end col-->
            
            <div class="col-lg-12 d-none next_step">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-sm-12"><h3>Product Description</h3></div>
                        <div class="col-sm-12 mb-3">
                          <div class="form-group">
                            <label for="description" class="form-label">Description *</label>
                            <textarea name="description" id="description" rows="6" class="summernote form-control @error('description') is-invalid @enderror" required></textarea>
                            <button type="button" class="btn btn-primary" id="translateBtn" onclick="transletDescription()">Translate</button>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-12 mb-3">
                          <div class="form-group">
                            <label for="description" class="form-label">Description (Bangla)</label>
                            <textarea name="description_bn" id="description_bn" rows="6" class="summernote form-control @error('description_bn') is-invalid @enderror" required></textarea>
                            @error('description_bn')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col end -->
                    </div>
                </div>
            </div>
            
            <!-- end col-->
            <div class="col-lg-12 d-none next_step">
                <div class="card">
                    <div class="card-body row">
                        <!--<div class="col-sm-12"><h3>Status, Deal & Submit</h3></div>-->
                        <!-- col end -->
                        <div class="col-sm-3 mb-3">
                          <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="status" checked />
                              <span class="slider round"></span>
                            </label>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-3 mb-3">
                          <div class="form-group">
                            <label for="topsale" class="d-block">Hot Deals</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="topsale" />
                              <span class="slider round"></span>
                            </label>
                            @error('topsale')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                        </div>
                        <!-- col end -->
            
                        <div class="col-sm-6 mb-3 d-flex justify-content-end">
                          <input type="submit" class="btn btn-success" value="Submit" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col-->
        </div>
    </form>
  
</div>
@endsection 

@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<!-- Plugins js -->
<script src="{{asset('public/backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>
<script>
  $(".summernote").summernote({
    placeholder: "Enter Your Text Here",
  });
</script>
<script type="text/javascript">
  $(document).ready(function () {
    $("#nextStepBtn").click(function () {
      $(".prev_step").addClass("d-none");
      $(".next_step").removeClass("d-none");
      
       $(".select2").select2();
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function () {
    $(".btn-increment").click(function () {
      var html = $(".clone").html();
      $(".increment").after(html);
    });
    $("body").on("click", ".btn-danger", function () {
      $(this).parents(".control-group").remove();
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function () {
    $(".increment_btn").click(function () {
      var html = $(".clone_price").html();
      $(".increment_price").after(html);
    });
    $("body").on("click", ".remove_btn", function () {
      $(this).parents(".increment_control").remove();
    });

    $(".select2").select2();
  });

  // category to sub
  $("#category_id").on("change", function () {
    var ajaxId = $(this).val();
    if (ajaxId) {
      $.ajax({
        type: "GET",
        url: "{{url('ajax-product-subcategory')}}?category_id=" + ajaxId,
        success: function (res) {
          $('#nextStepBtn').prop('disabled', false);
          
          if (res) {
            $("#subcategory_id").empty();
            $("#subcategory_id").append('<option value="0">Choose...</option>');
            $.each(res, function (key, value) {
              $("#subcategory_id").append('<option value="' + key + '">' + value + "</option>");
            });
          } else {
            $("#subcategory_id").empty();
          }
        },
      });
    } else {
      $("#subcategory_id").empty();
    }
  });

  // subcategory to childcategory
  $("#subcategory_id").on("change", function () {
    var ajaxId = $(this).val();
    if (ajaxId) {
      $.ajax({
        type: "GET",
        url: "{{url('ajax-product-childcategory')}}?subcategory_id=" + ajaxId,
        success: function (res) {
          if (res) {
            $("#childcategory_id").empty();
            $("#childcategory_id").append('<option value="0">Choose...</option>');
            $.each(res, function (key, value) {
              $("#childcategory_id").append('<option value="' + key + '">' + value + "</option>");
            });
          } else {
            $("#childcategory_id").empty();
          }
        },
      });
    } else {
      $("#childcategory_id").empty();
    }
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
async function autoTranslate() {
  const text = document.getElementById('name').value.trim();
  if (!text) {
    document.getElementById('name_bn').value = '';
    return;
  }

  try {
    const response = await fetch(`https://lingva.ml/api/v1/en/bn/${encodeURIComponent(text)}`);
    const data = await response.json();
    document.getElementById('name_bn').value = data.translation || '';
  } catch (error) {
    console.error('Translation failed:', error);
    document.getElementById('name_bn').value = '';
  }
}

// for description

async function transletDescription() {
    const htmlContent = $('#description').summernote('code').trim();

    // HTML থেকে plain text বের করা
    const tempDiv = document.createElement("div");
    tempDiv.innerHTML = htmlContent;
    const plainText = tempDiv.textContent || tempDiv.innerText || "";

    if (!plainText) {
        $('#description_bn').summernote('code', '');
        return;
    }

    try {
        const response = await fetch(`https://lingva.ml/api/v1/en/bn/${encodeURIComponent(plainText)}`);
        if (!response.ok) throw new Error('Network response was not ok');
        const data = await response.json();

        $('#description_bn').summernote('code', data.translation || '');
    } catch (error) {
        console.error('Translation failed:', error);
        $('#description_bn').summernote('code', '');
    }
}

// my addition 

const input = document.getElementById('tagsInput');
const choices = new Choices(input, {
  removeItemButton: true,
  delimiter: ',',
  editItems: true,
  maxItemCount: 10,
  addItems: true,
  duplicateItemsAllowed: false
});



</script>

@endsection