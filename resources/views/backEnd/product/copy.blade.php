 @extends('backEnd.layouts.master') @section('title','Product Create') @section('css')
<style>
  .increment_btn,
  .remove_btn {
    margin-top: -17px;
    margin-bottom: 10px;
  }
</style>
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
@endsection @section('content')
<div class="container-fluid">
  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <a href="{{route('products.index')}}" class="btn btn-primary rounded-pill">Manage</a>
        </div>
        <h4 class="page-title">Product Copy</h4>
      </div>
    </div>
  </div>
  <!-- end page title -->
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <form action="{{route('products.store')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
            @csrf
            <!--<div class="col-sm-6">-->
            <!--  <div class="form-group mb-3">-->
            <!--    <label for="name" class="form-label">Product Name *</label>-->
            <!--    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" required="" />-->
            <!--    @error('name')-->
            <!--    <span class="invalid-feedback" role="alert">-->
            <!--      <strong>{{ $message }}</strong>-->
            <!--    </span>-->
            <!--    @enderror-->
            <!--  </div>-->
            <!--</div>-->
            
            
            
            <div class="col-sm-6">
              <div class="form-group mb-3">
                <label for="name" class="form-label">Product Name (English) *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $product->name }}" id="name" required />
                @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            
            <div class="col-sm-6">
              <div class="form-group mb-3">
                <label for="name_bn" class="form-label">Product Name (Bangla)</label>
                <input type="text" class="form-control"  value="{{ $product->name_bn }}" name="name_bn" id="name_bn" />
              </div>
            </div>

            


                   <!-- col-end -->
            <div class="col-sm-6">
              <div class="form-group mb-3">
                <label for="category_id" class="form-label">Categories *</label>
                <select class="form-control select2 @error('category_id') is-invalid @enderror" name="category_id" value="{{ old('category_id') }}" id="category_id" required>
                  <option value="{{ $product->category->id}}">{{ $product->category->name}}</option>
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
            <div class="col-sm-6">
              <div class="form-group mb-3">
                <label for="subcategory_id" class="form-label">SubCategories (Optional)</label>
                <select class="form-control select2 @error('subcategory_id') is-invalid @enderror" id="subcategory_id" name="subcategory_id" data-placeholder="{{$product->subcategory->subcategoryName}}">
                  <optgroup>
                    <option value="{{$product->subcategory->id}}">{{$product->subcategory->subcategoryName}}</option>
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
            <div class="col-sm-6">
              <div class="form-group mb-3">
                <label for="childcategory_id" class="form-label">Child Categories (Optional)</label>
                <select class="form-control select2 @error('childcategory_id') is-invalid @enderror" id="childcategory_id" name="childcategory_id" data-placeholder="{{ $product->childcategory->childcategoryName }}">
                  <optgroup>
                    <option value="{{ $product->childcategory->id }}">{{ $product->childcategory->childcategoryName }}</option>
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

            <div class="col-sm-4">
              <div class="form-group mb-3">
                <label for="category_id" class="form-label">Brands</label>
                <select class="form-control select2 @error('brand_id') is-invalid @enderror" value="{{ old('brand_id') }}" name="brand_id">
                  <option value="{{$product->brand_id}}">{{$product->brand->name}}</option>
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
            <div class="col-sm-4">
              <div class="form-group mb-3">
                <label for="purchase_price" class="form-label">Purchase Price *</label>
                <input type="text" class="form-control @error('purchase_price') is-invalid @enderror" name="purchase_price" value="{{ $product->purchase_price }}" id="purchase_price" required />
                @error('purchase_price')
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
                <input type="text" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ $product->stock }}" id="stock" />
                @error('stock')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            
            
            
            
      <div id="repeater">
    @foreach($product_images as $index => $variation)
        <div class="row repeater-item mb-4">
            {{-- Color --}}
            <div class="col-md-3">
                <div class="form-group mb-2">
                    <select class="form-control" name="variations[{{ $index }}][color]">
                        <option value="">Select Color</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ $variation->color == $color->id ? 'selected' : '' }}>
                                {{ $color->colorName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Size --}}
            <div class="col-md-3">
                <div class="form-group mb-2">
                    <select class="form-control" name="variations[{{ $index }}][size]">
                        <option value="">Select Size</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ $variation->size == $size->id ? 'selected' : '' }}>
                                {{ $size->sizeName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Price and Image --}}
            <div class="col-md-3">
                <input type="number" name="variations[{{ $index }}][price]" class="form-control mb-2"
                       value="{{ $variation->price }}" placeholder="Price" required>

                <input type="file" name="variations[{{ $index }}][image]" value="{{ asset($variation->image) }}" class="form-control image-input"
                       accept="image/*" onchange="previewImage(this)">
                <img src="{{ asset($variation->image) }}" alt="Image Preview" class="img-preview mt-2"
                     style="max-height: 100px;">
            </div>

            {{-- Remove button --}}
            <div class="col-md-3 d-flex align-items-start">
                <button type="button" class="btn btn-danger mt-2" onclick="removeRow(this)">Remove</button>
            </div>
        </div>
    @endforeach
</div>

<button type="button" class="btn btn-secondary mb-3" onclick="addRow()">Add Variation</button>

             <!--col end -->
            <!--<div class="col-sm-12 mb-3">-->
            <!--  <div class="form-group">-->
            <!--    <label for="description" class="form-label">Description *</label>-->
            <!--    <textarea name="description" rows="6" class="summernote form-control @error('description') is-invalid @enderror" required></textarea>-->
            <!--    @error('description')-->
            <!--    <span class="invalid-feedback" role="alert">-->
            <!--      <strong>{{ $message }}</strong>-->
            <!--    </span>-->
            <!--    @enderror-->
            <!--  </div>-->
            <!--</div>-->
            <!-- col end -->
            
            
            
            <!-- English Description -->
<div class="col-sm-12 mb-3">
  <div class="form-group">
    <label for="description" class="form-label">Description *</label>
    <textarea id="description" name="description" rows="6" class="summernote form-control @error('description') is-invalid @enderror" required>{{$product->description}}</textarea>
    @error('description')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
</div>

<!-- Bangla Translated Description -->
<div class="col-sm-12 mb-3">
  <div class="form-group">
    <label for="description_bn" class="form-label">Description (Bangla)</label>
    <textarea id="description_bn" name="description_bn" rows="6" class="form-control">{{$product->description_bn}}</textarea>
  </div>
</div>

           
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

            <div>
              <input type="submit" class="btn btn-success" value="Submit" />
            </div>
          </form>
        </div>
        <!-- end card-body-->
      </div>
      <!-- end card-->
    </div>
    <!-- end col-->
  </div>
</div>
@endsection @section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<!-- Plugins js -->
<script src="{{asset('public/backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>
<!--Translate-->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById("name");
    const nameBnInput = document.getElementById("name_bn");
    const descriptionInput = document.getElementById("description");
    const descriptionBnInput = document.getElementById("description_bn");

    let timeout = null;

    // Translation helper
    function translateText(text, targetField) {
        if (text.trim().length === 0) {
            targetField.value = "";
            return;
        }

        fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=bn&dt=t&q=${encodeURIComponent(text)}`)
            .then(res => res.json())
            .then(data => {
                const translated = data[0].map(t => t[0]).join("");
                targetField.value = translated;
            })
            .catch(err => {
                console.error("Translation error:", err);
            });
    }

    // Product Name auto-translate
    nameInput.addEventListener("input", function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            translateText(nameInput.value, nameBnInput);
        }, 500);
    });

    // Description auto-translate
    // If using Summernote or WYSIWYG, monitor content change dynamically
    const isSummernote = descriptionInput.classList.contains("summernote");
    
    if (isSummernote && typeof $ !== "undefined" && typeof $.fn.summernote !== "undefined") {
        // Summernote is initialized
        $(descriptionInput).on('summernote.change', function(we, contents) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                translateText(contents.replace(/<\/?[^>]+(>|$)/g, ""), descriptionBnInput); // Strip HTML
            }, 700);
        });
    } else {
        // Plain textarea
        descriptionInput.addEventListener("input", function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                translateText(descriptionInput.value, descriptionBnInput);
            }, 700);
        });
    }
});
</script>


<!--Translate-->
<script>
  $(".summernote").summernote({
    placeholder: "Enter Your Text Here",
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













<script>
    let index = 1;

    function addRow() {
        const repeater = document.getElementById('repeater');
        const template = `
            <div class="row repeater-item mb-4">
                <div class="col-md-3">
                    <div class="form-group mb-2">
                        <select class="form-control" name="variations[${index}][color]">
                            <option value="">Select Color</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->colorName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-2">
                        <select class="form-control" name="variations[${index}][size]">
                            <option value="">Select Size</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->sizeName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <input type="number" name="variations[${index}][price]" class="form-control mb-2" placeholder="Price" required>

                    <input type="file" name="variations[${index}][image]" class="form-control image-input" accept="image/*" onchange="previewImage(this)">
                    <img src="" alt="Image Preview" class="img-preview mt-2" style="display:none; max-height:100px;">
                </div>

                <div class="col-md-3 d-flex align-items-start">
                    <button type="button" class="btn btn-danger mt-2" onclick="removeRow(this)">Remove</button>
                </div>
            </div>
        `;
        repeater.insertAdjacentHTML('beforeend', template);
        index++;
    }

    function removeRow(button) {
        button.closest('.repeater-item').remove();
    }

    function previewImage(input) {
        const img = input.closest('.col-md-3').querySelector('.img-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            img.src = '';
            img.style.display = 'none';
        }
    }
</script>
@endsection




