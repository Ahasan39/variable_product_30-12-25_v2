@php
    $divisions = [
        1 => 'Dhaka',
        2 => 'Chattogram',
        3 => 'Rajshahi',
        4 => 'Khulna',
        5 => 'Barisal',
        6 => 'Sylhet',
        7 => 'Rangpur',
        8 => 'Mymensingh',
    ];

    $districts = [
        1 => ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Kishoreganj', 'Madaripur', 'Manikganj', 'Munshiganj', 'Narayanganj', 'Narsingdi', 'Rajbari', 'Shariatpur', 'Tangail'],
        2 => ['Chattogram', 'Cox\'s Bazar', 'Cumilla', 'Brahmanbaria', 'Chandpur', 'Feni', 'Khagrachhari', 'Lakshmipur', 'Noakhali', 'Rangamati'],
        3 => ['Bogura', 'Joypurhat', 'Naogaon', 'Natore', 'Chapainawabganj', 'Pabna', 'Rajshahi', 'Sirajganj'],
        4 => ['Bagerhat', 'Chuadanga', 'Jashore', 'Jhenaidah', 'Khulna', 'Kushtia', 'Magura', 'Meherpur', 'Narail', 'Satkhira'],
        5 => ['Barisal', 'Barguna', 'Bhola', 'Jhalokathi', 'Patuakhali', 'Pirojpur'],
        6 => ['Sylhet', 'Habiganj', 'Moulvibazar', 'Sunamganj'],
        7 => ['Rangpur', 'Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh', 'Thakurgaon'],
        8 => ['Mymensingh', 'Netrokona', 'Sherpur', 'Jamalpur'],
    ];
@endphp

<script>
    const allDistricts = @json($districts);
</script>

@extends('frontEnd.layouts.master')
@section('title', 'Customer Checkout')
@push('css')
    <link rel="stylesheet" href="{{ asset('frontEnd/css/select2.min.css') }}" />
@endpush
@section('content')
<section class="chheckout-section">
    @php
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-sm-5 cus-order-2">
                <div class="checkout-shipping">
                    <form action="{{ route('customer.ordersave') }}" method="POST" data-parsley-validate="">
                        @csrf
                        <div class="card">
                           <div class="card-header">
                                <h6>আপনার অর্ডারটি কনফার্ম করতে তথ্যগুলো পূরণ করে <span style="color:#fe5200;">"অর্ডার করুন"</span> বাটন এ ক্লিক করুন।</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="name">আপনার নাম লিখুন *</label>
                                            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required/>
                                            @error('name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="phone">আপনার নাম্বার লিখুন *</label>
                                            <input type="text" minlength="11" maxlength="11" pattern="0[0-9]+" title="please enter number only and 0 must first character" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required/>
                                            @error('phone') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                        </div>
                                    </div>
                                    
                   <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="area">ডেলিভারি এরিয়া নিবার্চন করুন *</label>
                                            <select id="area" name="area" class="form-control @error('area') is-invalid @enderror" required>
                                                @foreach ($shippingcharge as $key => $value)
                                                    <option value="{{ $value->id }}" data-charge="{{ $value->amount }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('area') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                        </div>
                                    </div>

                                    {{-- Division Dropdown --}}
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="division">বিভাগ নির্বাচন করুন *</label>
                                            <select name="division" id="division" class="form-control" required>
                                                <option value="">-- বিভাগ নির্বাচন করুন --</option>
                                                @foreach($divisions as $key => $name)
                                                    <option value="{{ $key }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- District Dropdown --}}
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="city">জেলা নির্বাচন করুন *</label>
                                            <select name="city" id="city" class="form-control" required>
                                                <option value="">-- জেলা নির্বাচন করুন --</option>
                                            </select>
                                        </div>
                                    </div>

                                


                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="address">ঠিকানা লিখুন,পৌরসভা ,গ্রাম</label>
                                            <input type="address" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required/>
                                            @error('address') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                        </div>
                                    </div>

                                    {{-- Payment --}}
                                    <div class="col-sm-12">
                                        <div class="radio_payment">
                                            <label id="payment_method">পেমেন্ট মেথড</label>
                                        </div>
                                        <div class="payment-methods">
                                            <div class="form-check p_cash">
                                                <input class="form-check-input" type="radio" name="payment_method" id="inlineRadio1" value="Cash On Delivery" checked required />
                                                <label class="form-check-label" for="inlineRadio1">Cash On Delivery</label>
                                            </div>
                                            @if($bkash_gateway)
                                            <div class="form-check p_bkash">
                                                <input class="form-check-input" type="radio" name="payment_method" id="inlineRadio2" value="bkash" required/>
                                                <label class="form-check-label" for="inlineRadio2">Bkash</label>
                                            </div>
                                            @endif
                                            @if($shurjopay_gateway)
                                            <div class="form-check p_shurjo">
                                                <input class="form-check-input" type="radio" name="payment_method" id="inlineRadio3" value="shurjopay" required/>
                                                <label class="form-check-label" for="inlineRadio3">Shurjopay</label>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group" style="display:none;">
                                            <input type="text" name="user_verification_code" value="">
                                        </div>
                                        <div class="form-group">
                                            <button class="order_place" type="submit">অর্ডার করুন</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         
                    </form>
                </div>
            </div>

            {{-- Cart Section --}}
            <div class="col-sm-7 cust-order-1">
                <div class="cart_details table-responsive-sm">
                    <div class="card">
                        <div class="card-header">
                            <h5>অর্ডারের তথ্য</h5>
                        </div>
                        <div class="card-body cartlist">
                            <table class="cart_table table table-bordered table-striped text-center mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">ডিলিট</th>
                                        <th style="width: 40%;">প্রোডাক্ট</th>
                                        <th style="width: 20%;">পরিমাণ</th>
                                        <th style="width: 20%;">মূল্য</th>
                                    </tr>
                                </thead>

                                <tbody>

                                        <tr>
                                            <td>
                                                <a class="cart_remove"><i
                                                        class="fas fa-trash text-danger"></i></a>
                                            </td>
                                            <td>
                                                <img src="{{ asset($product->image ? $product->image->image : '') }}"
                                            alt="{{ $product->name }}" /><p>{{ $product -> name }}</p>
                                            </td>
                                            <td>
                                              1
                                            </td>
                                             <input type="hidden" id="subtotal_hidden" value="{{ $product->new_price }}">
                                            <td>

                                                @if ($product->old_price)
                                             <del>৳ {{ $product->old_price }}</del>
                                            @endif

                                            ৳ {{ $product->new_price }}
                                            </td>
                                        </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">মোট</th>
                                        <td class="px-4">
                                            <span id="net_total"><span class="alinur">
                                                </span><strong>
                                                @if ($product->old_price)
                                             <del>৳ {{ $product->old_price }}</del>
                                            @endif

                                            ৳ {{ $product->new_price }}

                                                </strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">ডেলিভারি চার্জ</th>
                                        <td class="px-4">
                                            <span id="cart_shipping_cost"><span class="alinur">৳
                                                </span><strong>{{ $shipping }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">সর্বমোট</th>
                                        <td class="px-4">
                                            <span id="grand_total"><span class="alinur">৳
                                                </span><strong>{{ $subtotal + $shipping }}</strong></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('script')
<script src="{{ asset('frontEnd/js/parsley.min.js') }}"></script>
<script src="{{ asset('frontEnd/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
    const subtotal = parseFloat(document.getElementById("subtotal_hidden").value);
$("#area").on("change", function () {
    const selectedOption = $(this).find('option:selected');
    const shipping = parseFloat(selectedOption.data('charge'));
    const subtotal = parseFloat($("#subtotal_hidden").val());

    $("#cart_shipping_cost strong").text(shipping);
    $("#grand_total strong").text(subtotal + shipping);
});
// ==============
<script src="{{ asset('frontEnd/js/parsley.min.js') }}"></script>
<script src="{{ asset('frontEnd/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#division').on('change', function () {
            const divisionId = $(this).val();
            const districts = allDistricts[divisionId] || [];
            const citySelect = $('#city');
            citySelect.empty().append('<option value="">-- জেলা নির্বাচন করুন --</option>');
            districts.forEach(function(district) {
                citySelect.append(`<option value="${district}">${district}</option>`);
            });
        });
    });
</script>
@endpush
@push('script')
<script src="{{ asset('frontEnd/') }}/js/parsley.min.js"></script>
<script src="{{ asset('frontEnd/') }}/js/form-validation.init.js"></script>
<script src="{{ asset('frontEnd/') }}/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>
<script>
    $("#area").on("change", function () {
        const selectedOption = $(this).find('option:selected');
        const shipping = parseFloat(selectedOption.data('charge'));
        const subtotal = parseFloat("{{ $subtotal }}"); // PHP থেকে পাঠানো subtotal

        // Update shipping cost and grand total
        $("#cart_shipping_cost strong").text(shipping);
        $("#grand_total strong").text(subtotal + shipping);
    });
</script>
<script type = "text/javascript">
    dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
    dataLayer.push({
        event    : "view_cart",
        ecommerce: {
            items: [@foreach (Cart::instance('shopping')->content() as $cartInfo){
                item_name     : "{{$cartInfo->name}}",
                item_id       : "{{$cartInfo->id}}",
                price         : "{{$cartInfo->price}}",
                item_brand    : "{{$cartInfo->options->brand}}",
                item_category : "{{$cartInfo->options->category}}",
                item_size     : "{{$cartInfo->options->size}}",
                item_color     : "{{$cartInfo->options->color}}",
                currency      : "BDT",
                quantity      : {{$cartInfo->qty ?? 0}}
            },@endforeach]
        }
    });
</script>
<script type="text/javascript">
    // Clear the previous ecommerce object.
    dataLayer.push({ ecommerce: null });

    // Push the begin_checkout event to dataLayer.
    dataLayer.push({
        event: "begin_checkout",
        ecommerce: {
            items: [@foreach (Cart::instance('shopping')->content() as $cartInfo)
                {
                    item_name: "{{$cartInfo->name}}",
                    item_id: "{{$cartInfo->id}}",
                    price: "{{$cartInfo->price}}",
                    item_brand: "{{$cartInfo->options->brands}}",
                    item_category: "{{$cartInfo->options->category}}",
                    item_size: "{{$cartInfo->options->size}}",
                    item_color: "{{$cartInfo->options->color}}",
                    currency: "BDT",
                    quantity: {{$cartInfo->qty ?? 0}}
                },
            @endforeach]
        }
    });
</script>
@endpush
