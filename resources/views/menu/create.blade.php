@extends('app')
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('menu.store') }}" method="post">
            @csrf
            <div class="row mb-3">
                <div class="col-6">
                    <label for="" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Name">
                </div>
                <div class="col-6">
                    <label for="" class="form-label">Parent</label>
                    <select name="parent_id" class="form-select">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="" class="form-label">Icon</label>
                    <input type="text" class="form-control" name="icon" placeholder="Enter Icon">
                </div>
                <div class="col-6">
                    <label for="" class="form-label">Url</label>
                    <input type="text" class="form-control" name="url" placeholder="Enter Url">
                </div>
                <div class="col-6">
                    <label for="" class="form-label">Sort Order</label>
                    <input type="number" class="form-control" name="sort_order">
                </div>
                <div class="col-6">
                    <label for="" class="form-label">Status *</label>
                    <input type="radio" name="is_active" value="1" checked> Active
                    <input type="radio" name="is_acive" value="0"> In-Active
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
