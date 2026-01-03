<style>
    .modal-header {
        border-bottom: 1px solid #ddd;
    }

    .success-ratio .progress {
        height: 35px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 50px;
        margin-bottom: 15px;
    }

    .powered_by h6 {
        margin-top: 0 !important;
        font-size: 16px;
        margin-bottom: 25px;
        padding-top: 15px;
    }

    #fraud_checker_modal .modal-body {
        padding-bottom: 0;
    }

    .text-danger {
        border-bottom: 1px solid #ddd;
        padding-bottom: 25px;
        text-align: center;
    }
</style>
<div class="modal-header">
    <h1 class="modal-title fs-5" id="staticBackdropLabel">Parcel Result For {{ $phone }}</h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @if ($status == 'limitover')
        <h4 class="text-danger">Your Package limit over, Please contact to support</h4>
        <hr>
    @else
        @if ($total_parcel > 0)
            <div class="success-ratio">
                <div class="progress" role="progressbar" aria-label="Progress example" aria-valuemin="0"
                    aria-valuemax="100">
                    <div class="progress-bar bg-success"
                        style="width: {{ round(($total_success / $total_parcel) * 100, 2) }}%"
                        aria-valuenow="{{ round(($total_success / $total_parcel) * 100, 2) }}">
                        Success {{ round(($total_success / $total_parcel) * 100, 2) }}%
                    </div>
                    <div class="progress-bar bg-danger"
                        style="width: {{ 100 - round(($total_success / $total_parcel) * 100, 2) }}%"
                        aria-valuenow="{{ 100 - round(($total_success / $total_parcel) * 100, 2) }}">
                        Return {{ 100 - round(($total_success / $total_parcel) * 100, 2) }} %
                    </div>
                </div>
            </div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Courier</th>
                    <th>Total Parcel</th>
                    <th>Delivered</th>
                    <th>Return</th>
                    <th>Success</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>Steadfast</td>
                    <td>{{ $steadfast_total }}</td>
                    <td>{{ $steadfast_success }}</td>
                    <td>{{ $steadfast_cancel }}</td>
                    <td>{{ $steadfast_total > 0 ? round(($steadfast_success / $steadfast_total) * 100, 2) . '%' : '0%' }}
                    </td>
                </tr>
                <tr>
                    <td>Pathao</td>
                    <td>{{ $pathao_total }}</td>
                    <td>{{ $pathao_success }}</td>
                    <td>{{ $pathao_cancel }}</td>
                    <td>{{ $pathao_total > 0 ? round(($pathao_success / $pathao_total) * 100, 2) . '%' : '0%' }}</td>
                </tr>
                <tr>
                    <td>Redx</td>
                    <td>{{ $redx_total }}</td>
                    <td>{{ $redx_success }}</td>
                    <td>{{ $redx_cancel }}</td>
                    <td>{{ $redx_total > 0 ? round(($redx_success / $redx_total) * 100, 2) . '%' : '0%' }}</td>
                </tr>
                <tr>
                    <td>Paperfly</td>
                    <td>{{ $paperfly_total }}</td>
                    <td>{{ $paperfly_success }}</td>
                    <td>{{ $paperfly_cancel }}</td>
                    <td>0%</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th>{{ $total_parcel }}</th>
                    <th>{{ $total_success }}</th>
                    <th>{{ $total_parcel - $total_success }}</th>
                    <th>
                        @if ($total_parcel > 0)
                            {{ round(($total_success / $total_parcel) * 100, 2) }}%
                        @else
                            0
                        @endif
                    </th>
                </tr>
            </tfoot>
        </table>
    @endif
</div>
<div class="powered_by text-center">
    <h6><strong>Powered By <a href="https://webleez.com" target="_blank">webleez</a></strong></h6>
</div>
