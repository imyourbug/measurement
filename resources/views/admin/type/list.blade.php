@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    <style>
        .dataTables_paginate {
            float: right;

        }

        .form-inline {
            display: inline;
        }

        .pagination li {
            margin-left: 10px;
        }
    </style>
@endpush
@push('scripts')
    <script src="/js/admin/tasktype/index.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script></script>
@endpush
@section('content')
    <a href="{{ route('admin.types.create') }}" class="btn btn-success mb-3">Thêm mới</a>
    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên loại</th>
                <th>Danh mục cha</th>
                <th>Thao tác</th>
            </tr>
        <tbody>
            @foreach ($types as $key => $type)
                <tr class="row{{ $type->id }}">
                    <th>{{ $type->id }}</th>
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->parent->name ?? '' }}</td>
                    <td><a class="btn btn-primary btn-sm" href='{{ route('admin.types.show', ['id' => $type->id]) }}'>
                            <i class="fas fa-edit"></i>
                        </a>
                        <button data-id="{{ $type->id }}" class="btn btn-danger btn-sm btn-delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </thead>
    </table>
@endsection