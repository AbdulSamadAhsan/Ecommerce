<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

{{-- No surplus words or unnecessary actions. - Marcus Aurelius --}}
<div>
    {{-- You must be the change you wish to see in the world. - Mahatma Gandhi --}}


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



    </div>

    <!-- Product Table -->

    <div class="dashboard-card">

        <!-- Search -->

        <div class="row mb-4">

            <div class="col-md-6">

                <input type="text" class="form-control rounded-4" placeholder="Search product...">

            </div>

        </div>

        <!-- Table -->

        <div class="table-responsive">

            <table class="table align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>
                        <th>Brand Image</th>
                        <th>Brand Name</th>

                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <!-- Product 1 -->

                    <tr>

                        <td>#1001</td>

                        <td>

                            <img src="{{ asset('asset/laptop.jpg') }}" width="50" class="rounded-3">

                        </td>

                        <td>

                            <div class="fw-semibold">
                                HP
                            </div>

                        </td>



                        <td>

                            <span class="badge bg-success rounded-pill">
                                Active
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

                            <img src="{{ asset('asset/keyboard.jpg') }}" width="50" class="rounded-3">

                        </td>

                        <td>

                            <div class="fw-semibold">
                                DELL
                            </div>

                        </td>


                        <td>

                            <span class="badge bg-primary rounded-pill">
                                Active
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



                    <!-- Product 4 -->

                    <tr>

                        <td>#1004</td>

                        <td>

                            <img src="{{ asset('asset/mouse.jpg') }}" width="50" class="rounded-3">

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



                </tbody>

            </table>

        </div>

    </div>

</div>
