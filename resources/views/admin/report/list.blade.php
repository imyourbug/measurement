@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/admin/report/index.js"></script>
    <script>
        var mapChart = null;
        $('#form-export').submit(function(event) {
            event.preventDefault();
            let pattern = /^\d{4}$/;
            let year = $('.select-year').val();
            let month = $('.select-month').val();

            // $(this).unbind('submit').submit();
            if (!month | !year | !pattern.test(year)) {
                alert('Kiểm tra thông tin đã nhập!');
            } else {
                $(this).unbind('submit').submit();
            }
        })

        $('.btn-preview').on('click', function() {
            if (mapChart && mapChart.toBase64Image()) {
                mapChart.destroy();
            }

            let year = $('.select-year').val();
            let month = $('.select-month').val();
            let contract_id = $('.select-contract').val();
            $.ajax({
                type: "GET",
                url: "/api/exports/getDataMapChart?month=" + month + "&year=" + year + "&contract_id=" +
                    contract_id,
                success: function(response) {
                    console.log(response);
                },
            })

            mapChart = new Chart($('#mapChart'), {
                type: 'bar',
                data: {
                    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: '# of Votes',
                        data: [12, 19, 3, 5, 2, 3],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            setTimeout(() => {
                $('#img-chart').attr('src', mapChart.toBase64Image('image/png', 1));
                $('.img-chart').val(mapChart.toBase64Image('image/png', 1));
                $('.month').val($('.select-month').val());
                $('.year').val($('.select-year').val());
                $('.type_report').val($('.select-type').val());
                $('.contract_id').val($('.select-contract').val());
                console.log($('.contract_id').val());
            }, 1000);
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title text-bold">Báo cáo</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;padding: 10px !important;">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chọn loại báo cáo</label>
                                <select class="form-control select-type">
                                    <option value="0">
                                        Kế hoạch dịch vụ
                                    </option>
                                    <option value="1">
                                        Kết quả tháng
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chọn hợp đồng</label>
                                <select class="form-control select-contract">
                                    @foreach ($contracts as $contract)
                                        <option value="{{ $contract->id }}">
                                            {{ $contract->name . '-' . $contract->branch->name ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chọn tháng</label>
                                <select class="form-control select-month">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ $i == now()->format('m') ? 'selected' : '' }}>{{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Chọn năm</label>
                                <input class="form-control select-year" type="text" value="{{ now()->format('Y') }}"
                                    placeholder="Nhập năm..." />
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-danger btn-preview" data-target="#modal-export" data-toggle="modal">Xuất
                        PDF</button>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        {{-- <a href="{{ route('admin.tasks.create') }}" class="btn btn-success">Thêm mới</a> --}}
        {{-- <input class="" style="" type="date" name="from"
            value="{{ Request::get('from') ?? now()->format('Y-m-01') }}" />
        <input class="" style="" type="date" name="to"
            value="{{ Request::get('to') ?? now()->format('Y-m-t') }}" />
        <button class="btn btn-warning btn-filter" type="submit">Lọc</button> --}}
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <label for="">Lựa chọn hợp đồng</label>
                <select multiple="multiple" class="select2 custom-select form-control-border select">
                    @foreach ($contracts as $contract)
                        <option value="{{ $contract->id }}">{{ $contract->name . '-' . $contract->branch->name ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nhiệm vụ</th>
                <th>Hợp đồng</th>
                <th>Ghi chú</th>
                <th>Ngày lập</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div class="modal fade show" id="modal" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title">Cập nhật nhiệm vụ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Loại nhiệm vụ</label>
                                <select class="form-control" id="type_id">
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->id . '-' . $type->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="menu">Hợp đồng</label>
                                <select class="form-control select-contract" id="contract_id">
                                    <option value="">--Hợp đồng--</option>
                                    @foreach ($contracts as $contract)
                                        <option value="{{ $contract->id }}">
                                            {{ $contract->name . '-' . $contract->branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="menu">Ghi chú</label>
                                <textarea placeholder="Nhập ghi chú..." class="form-control" id="note" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" data-url="{{ route('tasks.update') }}"
                        class="btn btn-primary btn-update">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="modal-export" style="display:none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('exports.plan') }}" method="POST" id="form-export">
                    <div class="modal-header">
                        <h4 class="modal-title">Xuất báo cáo?</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="groupChart">
                        <canvas id="mapChart" style="display:block;"></canvas>
                    </div>
                    {{-- <img src="" id="img-chart" alt=""> --}}
                    <input type="hidden" class="img-chart" name="img_chart" />
                    <input type="hidden" class="month" name="month" />
                    <input type="hidden" class="year" name="year" />
                    <input type="hidden" class="type_report" name="type_report" />
                    <input type="hidden" class="contract_id" name="contract_id" />
                    <div class="modal-footer justify-content-between">
                        <button class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary btn-export">Xác nhận</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <input type="hidden" id="task_id">
@endsection
