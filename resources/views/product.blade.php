@extends('layouts.dashboard_layout')

@section('content')

<div class="content-wrapper">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Product List
            </h3>

            <p class="text-muted mb-0">
                Manage all products
            </p>

        </div>

        <a href="#"
           class="btn btn-primary rounded-pill px-4">

            <i class="bi bi-plus-circle me-2"></i>
            Add Product

        </a>

    </div>

    <!-- Product Table -->

    <div class="dashboard-card">

        <!-- Search -->

        <div class="row mb-4">

            <div class="col-md-6">

                <input type="text"
                       class="form-control rounded-4"
                       placeholder="Search product...">

            </div>

        </div>

        <!-- Table -->

        <div class="table-responsive">

            <table class="table align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <!-- Product 1 -->

                    <tr>

                        <td>#1001</td>

                        <td>

                            <img src="https://via.placeholder.com/50"
                                 width="50"
                                 class="rounded-3">

                        </td>

                        <td>

                            <div class="fw-semibold">
                                Laptop
                            </div>

                        </td>

                        <td>Electronics</td>

                        <td>$850</td>

                        <td>120</td>

                        <td>

                            <span class="badge bg-success rounded-pill">
                                In Stock
                            </span>

                        </td>

                        <td>

                            <button class="btn btn-sm btn-primary rounded-pill">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger rounded-pill">
                                Delete
                            </button>

                        </td>

                    </tr>

                    <!-- Product 2 -->

                    <tr>

                        <td>#1002</td>

                        <td>

                            <img src="https://via.placeholder.com/50"
                                 width="50"
                                 class="rounded-3">

                        </td>

                        <td>

                            <div class="fw-semibold">
                                Keyboard
                            </div>

                        </td>

                        <td>Accessories</td>

                        <td>$120</td>

                        <td>80</td>

                        <td>

                            <span class="badge bg-primary rounded-pill">
                                Available
                            </span>

                        </td>

                        <td>

                            <button class="btn btn-sm btn-primary rounded-pill">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger rounded-pill">
                                Delete
                            </button>

                        </td>

                    </tr>

                    <!-- Product 3 -->

                    <tr>

                        <td>#1003</td>

                        <td>

                            <img src="https://via.placeholder.com/50"
                                 width="50"
                                 class="rounded-3">

                        </td>

                        <td>

                            <div class="fw-semibold">
                                Monitor
                            </div>

                        </td>

                        <td>Electronics</td>

                        <td>$450</td>

                        <td>15</td>

                        <td>

                            <span class="badge bg-warning text-dark rounded-pill">
                                Low Stock
                            </span>

                        </td>

                        <td>

                            <button class="btn btn-sm btn-primary rounded-pill">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger rounded-pill">
                                Delete
                            </button>

                        </td>

                    </tr>

                    <!-- Product 4 -->

                    <tr>

                        <td>#1004</td>

                        <td>

                            <img src="https://via.placeholder.com/50"
                                 width="50"
                                 class="rounded-3">

                        </td>

                        <td>

                            <div class="fw-semibold">
                                Mouse
                            </div>

                        </td>

                        <td>Accessories</td>

                        <td>$50</td>

                        <td>200</td>

                        <td>

                            <span class="badge bg-success rounded-pill">
                                In Stock
                            </span>

                        </td>

                        <td>

                            <button class="btn btn-sm btn-primary rounded-pill">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger rounded-pill">
                                Delete
                            </button>

                        </td>

                    </tr>

                    <!-- Product 5 -->

                    <tr>

                        <td>#1005</td>

                        <td>

                            <img src="https://via.placeholder.com/50"
                                 width="50"
                                 class="rounded-3">

                        </td>

                        <td>

                            <div class="fw-semibold">
                                Headphones
                            </div>

                        </td>

                        <td>Audio</td>

                        <td>$180</td>

                        <td>65</td>

                        <td>

                            <span class="badge bg-success rounded-pill">
                                In Stock
                            </span>

                        </td>

                        <td>

                            <button class="btn btn-sm btn-primary rounded-pill">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger rounded-pill">
                                Delete
                            </button>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection