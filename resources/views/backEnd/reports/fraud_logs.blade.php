@extends('backEnd.layouts.master')
@section('title','Fraud Protection Logs')
@section('css')
<link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Fraud Protection Logs</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
   <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>IP Address</th>
                                <th>Type</th>
                                <th>Message</th>
                                <th>Context</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            @foreach($show_data as $key=>$value)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$value->created_at->format('d M Y h:i A')}}</td>
                                <td>{{$value->ip_address}}</td>
                                <td>
                                    @if($value->type == 'honeypot')
                                        <span class="badge bg-danger">Honeypot</span>
                                    @elseif($value->type == 'duplicate')
                                        <span class="badge bg-warning">Duplicate</span>
                                    @elseif($value->type == 'ip-block')
                                        <span class="badge bg-dark">IP Blocked</span>
                                    @else
                                        <span class="badge bg-info">{{$value->type}}</span>
                                    @endif
                                </td>
                                <td>{{$value->message}}</td>
                                <td>
                                    <small>{{ Str::limit($value->context, 50) }}</small>
                                </td>
                                <td>
                                    <div class="button-list">
                                        <a class="btn btn-xs btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#logDetails{{$value->id}}"><i class="fe-eye"></i></a>
                                        
                                        @if(!\App\Models\IpBlock::where('ip_no', $value->ip_address)->exists())
                                        <form method="post" action="{{route('customers.ipblock.store')}}" class="d-inline">        
                                            @csrf
                                            <input type="hidden" value="{{$value->ip_address}}" name="ip_no">
                                            <input type="hidden" value="Manual block from fraud logs: {{$value->type}}" name="reason">
                                            <button type="submit" class="btn btn-xs btn-danger waves-effect waves-light" title="Block IP"><i class="fe-slash"></i></button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $show_data->links('pagination::bootstrap-4') }}
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
   </div>
</div>

@foreach($show_data as $key=>$value)
<!-- Modal -->
<div class="modal fade" id="logDetails{{$value->id}}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Log Details #{{$value->id}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                  <p><strong>IP Address:</strong> {{$value->ip_address}}</p>
                  <p><strong>Type:</strong> {{$value->type}}</p>
                  <p><strong>Date:</strong> {{$value->created_at->format('d M Y h:i A')}}</p>
              </div>
              <div class="col-md-6 text-end">
                   <p><strong>Message:</strong> {{$value->message}}</p>
              </div>
          </div>
          <hr>
          <h6>Context Data:</h6>
          <pre style="background: #f4f4f4; padding: 10px; border-radius: 5px; overflow: auto;">{{ json_encode(json_decode($value->context), JSON_PRETTY_PRINT) }}</pre>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection

@section('script')
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
@endsection
