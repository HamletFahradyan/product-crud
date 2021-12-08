@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <button class="btn btn-primary" id="btn-add">+ Add</button>
                    <div class="container">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>@sortablelink('name')</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    @if (!empty($products) && $products->count())
                                        @foreach ($products as $key => $value)
                                            <tr id="row_{{$value->id}}">
                                                <td>
                                                    <p id="row_name_{{$value->id}}">{{ $value->name }}</p>
                                                </td>
                                                <td>
                                                    <button class="btn btn-success btn-edit" data-id="{{$value->id}}">
                                                        Edit
                                                    </button>
                                                    <a href="javascript:void(0)" class="btn btn-danger"
                                                       onclick="openModal({{$value->id}})">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10">There are no data.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                {!! $products->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="product-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete product</h4>
            </div>
            <div class="modal-body">
                <span>Are you sure to delete this product?</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="deleteProduct()">Yes</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous">
    </script>
    <script>
        let idToDelete = null;
        $(document).on('click', '#btn-add', function() {
            let tableRow = '<tr id="new-product-tr">' +
                '<td><input type="text" id="product-name" name="product-name"></td>' +
                '<td>' +
                '<button class="btn btn-primary" style="margin-right: 3px;" id="save-new-product">Save</button>' +
                '<button class="btn btn-danger" onclick="deleteEmptyRow()">Delete</button>' +
                '</td>' +
                '</tr>';

                $('#tbody').prepend(tableRow);
                $('#product-name').focus();
        });
        $(document).on('click', '#save-new-product', function() {
            createProduct();
        });
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data("id");
            let name = $(`#row_name_${id}`).text();
            $(`#row_name_${id}`).hide();
            $(`#row_${id} td:nth-child(2) button:nth-child(1)`).remove();
            $(`#row_${id} td:nth-child(2)`).prepend('<button class="btn btn-primary update-product" style="margin-right: 3px;" data-id="' + id + '">Save</button>');
            $(`#row_${id} td:nth-child(1)`).append('<input data-id="' + id + '" type="text" id="product-name-edit-' + id + '" name="product-name" value="' + name + '">');
            $(`#product-name-edit-${id}`).focus();
        });
        $(document).on('click', '.update-product', function() {
            let id = $(this).data("id");
            let name = $(`#product-name-edit-${id}`).val();
            updateProduct(id, name);
        });
        $(document).on('keyup', function(e) {
            let id = null;
            let isCreateNew = $(e.currentTarget.activeElement).attr('id') === 'product-name';

            if (e.currentTarget.activeElement !== undefined) {
                id = $(e.currentTarget.activeElement).data('id');
            }
            if (e.key === "Enter") {
                if (isCreateNew) {
                    createProduct();
                } else {
                    let name = $(`#product-name-edit-${id}`).val();
                    updateProduct(id, name);
                }
            } else if (e.key === "Escape") {
                if (isCreateNew) {
                    deleteEmptyRow();
                } else {
                    $(`#row_name_${id}`).show();
                    $(e.currentTarget.activeElement).remove();
                    $(`#row_${id} td:nth-child(2) button:nth-child(1)`).remove();
                    $(`#row_${id} td:nth-child(2)`).prepend('<button class="btn btn-success btn-edit" style="margin-right: 3px;" data-id="' + id + '">Edit</button>');
                }
            }
        });
        function createProduct() {
            let name = $('#product-name').val();
            let _url   = '/products';
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: "POST",
                data: {
                    name,
                    _token
                },
                success: (response) => {
                    if(response.status === 'success') {
                        let id = response.data.id;
                        $('#product-name').remove();
                        $("#new-product-tr td:nth-child(1)").append('<p id="row_name_' + id + '">' +  response.data.name + '</p>');
                        $("#new-product-tr td:nth-child(2) button:nth-child(1)").remove();
                        $("#new-product-tr td:nth-child(2) button:nth-child(1)").remove();
                        $("#new-product-tr td:nth-child(2)").append('<button class="btn btn-success btn-edit" style="margin-right: 3px;" data-id="' + id + '">Edit</button>');
                        $("#new-product-tr td:nth-child(2)").append('<a href="javascript:void(0)" class="btn btn-danger" onclick="openModal(' + id + ')">Delete</a>');
                        $("#new-product-tr").attr("id", 'row_' + id)
                    }
                },
                error: (response) => {
                    console.error(response);
                }
            });
        }
        function updateProduct(id, name) {
            let _url   = '/products/' + id;
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: "PUT",
                data: {
                    name,
                    _token
                },
                success: (response) => {
                    if(response.status === 'success') {
                        $(`#product-name-edit-${id}`).remove();
                        $(`#row_name_${id}`).show();
                        $(`#row_name_${id}`).text(response.data.name);
                        $(`#row_${id} td:nth-child(2) button:nth-child(1)`).remove();
                        $(`#row_${id} td:nth-child(2)`).prepend('<button class="btn btn-success btn-edit" style="margin-right: 3px;" data-id="' + id + '">Edit</button>');
                    }
                },
                error: (response) => {
                    console.error(response);
                }
            });
        }
        function closeModal() {
            $('#product-modal').modal('hide');
            idToDelete = null;
        }
        function openModal(id) {
            idToDelete = id;
            $('#product-modal').modal('show');
        }
        function deleteProduct() {
            let _url = `/products/${idToDelete}`;
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: 'DELETE',
                data: {
                    _token: _token
                },
                success: (response) => {
                    if (response.status === 'success') {
                        $("#row_" + idToDelete).remove();
                        closeModal();
                    }
                }
            });
        }
        function deleteEmptyRow() {
            $('#new-product-tr').remove();
        }
    </script>
@endsection

