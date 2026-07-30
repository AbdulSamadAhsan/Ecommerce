<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f5f5f5;
            padding: 40px;
        }

        .card {

            width: 856px;
            height: 540px;

            background: #fff;

            border-radius: 20px;

            overflow: hidden;

            border: 2px solid #0d6efd;

            position: relative;

            box-shadow: 0 5px 20px rgba(0, 0, 0, .2);

        }

        .header {

            height: 100px;

            background: #0d6efd;

            color: white;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 20px 30px;

        }

        .header img {

            width: 70px;

        }

        .company {

            text-align: right;

        }

        .company h2 {

            font-size: 30px;

        }

        .company p {

            font-size: 16px;

        }

        .content {

            display: flex;

            padding: 35px;

        }

        .left {

            width: 230px;

            text-align: center;

        }

        .photo {

            width: 180px;

            height: 210px;

            border-radius: 10px;

            border: 2px solid #ddd;

            object-fit: cover;

        }

        .employee-id {

            margin-top: 15px;

            background: #0d6efd;

            color: white;

            padding: 10px;

            border-radius: 5px;

            font-size: 22px;

            font-weight: bold;

        }

        .right {

            flex: 1;

            padding-left: 40px;

        }

        table {

            width: 100%;

            border-collapse: collapse;

        }

        td {

            padding: 10px 0;

            font-size: 22px;

        }

        td:first-child {

            width: 180px;

            font-weight: bold;

        }

        .footer {

            position: absolute;

            bottom: 20px;

            left: 30px;

            right: 30px;

            display: flex;

            justify-content: space-between;

            align-items: center;

        }

        .signature {

            text-align: center;

        }

        .signature hr {

            width: 180px;

            border: none;

            border-top: 2px solid #000;

            margin-bottom: 8px;

        }

        .qr {

            width: 120px;

            height: 120px;

        }
    </style>

</head>

<body>

    <div class="card">

        <div class="header">

            <img src="{{ public_path('images/logo.png') }}">

            <div class="company">

                <h2>Tech Store</h2>

                <p>Employee Identity Card</p>

            </div>

        </div>

        <div class="content">

            <div class="left">

                <img class="photo" src="{{ public_path('storage/' . $employee->photo) }}">

                <div class="employee-id">

                    {{ $employee->employee_code }}

                </div>

            </div>

            <div class="right">

                <table>

                    <tr>

                        <td>Name</td>

                        <td>{{ $employee->user->name }}</td>

                    </tr>
                    <tr>

                        <td>CNIC</td>

                        <td>{{ $employee->cnic }}</td>

                    </tr>
                    <tr>

                        <td>Shift</td>

                        <td>{{ $employee->shift }}</td>

                    </tr>
                    <tr>

                        <td>Department</td>

                        <td>{{ $employee->department->name }}</td>

                    </tr>

                    <tr>

                        <td>Designation</td>

                        <td>{{ $employee->designation }}</td>

                    </tr>

                    <tr>

                        <td>Phone</td>

                        <td>{{ $employee->phone }}</td>

                    </tr>

                    <tr>

                        <td>Email</td>

                        <td>{{ $employee->user->email }}</td>

                    </tr>



                    <tr>

                        <td>Joining Date</td>

                        <td>{{ $employee->joining_date }}</td>

                    </tr>

                </table>

            </div>

        </div>

        <div class="footer">



            <div class="signature">

                <hr>

                Authorized Signature

            </div>

        </div>

    </div>

</body>

</html>
