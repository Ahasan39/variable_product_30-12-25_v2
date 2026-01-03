@extends('backEnd.layouts.master') 
@section('title','Shipping Charge Manage') 
@section('css')
<link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection 
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{ route('shippingcharges.create') }}" class="btn btn-primary waves-effect waves-light btn-sm rounded-pill">Create</a>
                </div>
                <h4 class="page-title">Shipping Charge Manage</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('shippingcharges.upazilaWise.update') }}">
                        @csrf
                        <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Division Name</th>
                                    <th>District Name</th>
                                    <th>Upazila Name</th>
                                    <th>Charge</th>
                                </tr>
                            </thead>
    
                            <tbody>
                                @foreach($shipping_charges as $key => $item)
                                    <tr style="vertical-align: middle;">
                                        <td>{{$loop->iteration}}<input type="hidden" name="id[]" value="{{ $item->id }}"></td>
                                        <td>{{ $item->division_name }}<input type="hidden" name="division_id[]" value="{{ $item->division_id }}"><input type="hidden" name="division_name[]" value="{{ $item->division_name }}"></td>
                                        <td>{{ $item->district_name }}<input type="hidden" name="district_id[]" value="{{ $item->district_id }}"><input type="hidden" name="district_name[]" value="{{ $item->district_name }}"></td>
                                        <td>{{ $item->upazila_name }}<input type="hidden" name="upazila_id[]" value="{{ $item->upazila_id }}"><input type="hidden" name="upazila_name[]" value="{{ $item->upazila_name }}"></td>
                                        <td><input type="text" class="form-control" name="amount[]" value="{{ $item->amount ?? 100 }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row justify-content-center mt-3">
                            <input type="submit" class="btn btn-success w-auto" value="Update Shipping Charge">
                        </div>
                    </form>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
</div>
@endsection 
@section('script')
<!-- third party js -->
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/js/pages/datatables.init.js"></script>
<!-- third party js ends -->

<script>
$(document).ready(function() {
    var table = $('#datatable-buttons').DataTable();

    // Ensure all rows' data are submitted
    $('form').on('submit', function(e) {
        var form = this;

        // Get all rows (not only visible)
        var rows = table.rows({ search: 'applied' }).nodes();

        // Append hidden inputs for non-visible rows
        $('input, select, textarea', rows).each(function() {
            if (!$.contains(document, this)) {
                $(form).append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', this.name)
                        .val($(this).val())
                );
            }
        });
    });
});
</script>
@endsection
