<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2,
        h3 {
            margin: 5px 0;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }

        .section-title {
            background: #0d6efd;
            color: white;
            padding: 8px;
            margin-top: 20px;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-border td {
            border: none;
        }
    </style>

</head>

<body>

    <h2>Tech</h2>
    <h3>Employee Complete Report</h3>

    <p style="text-align:right">
        Generated:
        {{ now()->format('d M Y h:i A') }}
    </p>

    <div class="section-title">
        Employee Information
    </div>

    <table>

        <tr>
            <th width="25%">Employee ID</th>
            <td>{{ $employee->employee_code }}</td>

            <th>Department</th>
            <td>{{ $employee->department->name ?? '-' }}</td>
        </tr>

        <tr>
            <th>Name</th>
            <td>{{ $employee->user->name }}</td>

            <th>Designation</th>
            <td>{{ $employee->designation ?? '-' }}</td>
        </tr>

        <tr>
            <th>Email</th>
            <td>{{ $employee->user->email }}</td>

            <th>Phone</th>
            <td>{{ $employee->phone }}</td>
        </tr>

        <tr>
            <th>Joining Date</th>
            <td>{{ date('d-M-Y', strtotime($employee->joining_date)) }}</td>

            <th>Status</th>
            <td>{{ ucfirst($employee->status) }}</td>
        </tr>

    </table>



    <div class="section-title">
        Last Month Attendance Summary
    </div>

    <table>

        <tr>
            <th>Total Days</th>
            <th>Present</th>
            <th>Absent</th>
            <th>Late</th>
            <th>Leave</th>
        </tr>

        <tr class="text-center">
            <td>{{ $attendance->count() }}</td>

            <td>{{ $attendance->where('status', 'present')->count() }}</td>

            <td>{{ $attendance->where('status', 'absent')->count() }}</td>

            <td>{{ $attendance->where('status', 'late')->count() }}</td>

            <td>{{ $attendance->where('status', 'leave')->count() }}</td>

        </tr>

    </table>



    <div class="section-title">
        Attendance Details
    </div>

    <table>

        <tr>

            <th>Date</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Status</th>

        </tr>

        @foreach ($attendance as $item)
            <tr>

                <td>{{ date('d-M-Y', strtotime($item->attendance_date)) }}</td>

                <td>{{ $item->check_in }}</td>

                <td>{{ $item->check_out }}</td>

                <td>{{ ucfirst($item->status) }}</td>

            </tr>
        @endforeach

    </table>




    <div class="section-title">
        Salary Payment History (Last 12 Months)
    </div>

    <table>

        <tr>

            <th>Month</th>
            <th>Amount</th>
            <th>Payment Method</th>

            <th>Paid Date</th>

        </tr>

        @foreach ($salaryPayments as $salary)
            <tr>

                <td>{{ \Carbon\Carbon::parse($salary->payroll->month)->format('M Y') }}</td>

                <td class="text-right">
                    {{ number_format($salary->amount, 2) }}
                </td>

                <td class="text-right">
                    {{ $salary->payment_method }}
                </td>



                <td>
                    {{ $salary->paid_at }}
                </td>

            </tr>
        @endforeach

    </table>



    <div class="section-title">
        Leave Summary
    </div>

    <table>

        <tr>

            <th>Total Leaves</th>
            <th>Approved</th>
            <th>Pending</th>
            <th>Rejected</th>

        </tr>

        <tr class="text-center">

            <td>{{ $leaveRecords->count() }}</td>

            <td>{{ $leaveRecords->where('status', 'approved')->count() }}</td>

            <td>{{ $leaveRecords->where('status', 'pending')->count() }}</td>

            <td>{{ $leaveRecords->where('status', 'rejected')->count() }}</td>

        </tr>

    </table>



    <div class="section-title">
        Leave History
    </div>

    <table>

        <tr>

            <th>From</th>
            <th>To</th>
            <th>Days</th>
            <th>Type</th>
            <th>Status</th>

        </tr>

        @foreach ($leaveRecords as $leave)
            <tr>

                <td>{{ date('d-M-Y', strtotime($leave->from_date)) }}</td>

                <td>{{ date('d-M-Y', strtotime($leave->to_date)) }}</td>

                <td>{{ $leave->days }}</td>

                <td>{{ ucfirst($leave->leave_type) }}</td>

                <td>{{ ucfirst($leave->status) }}</td>

            </tr>
        @endforeach

    </table>




    <div class="section-title">
        Latest Payroll
    </div>

    <table>

        <tr>
            <th>Month</th>
            <th>Basic Salary</th>


            <th>Allowance</th>
            <th>Bonus</th>

            <th>Overtime</th>

            <th>Deductions</th>
            <th>Tax</th>
            <th>Net Salary</th>
        </tr>
        @foreach ($payrolls as $payroll)
            <tr>
                <td>{{ \Carbon\Carbon::parse($payroll->month)->format('M Y') }}</td>
                <td>{{ $payroll->basic_salary }}</td>
                <td>{{ $payroll->allowances }}</td>

                <td>{{ $payroll->bonus }}</td>
                <td>{{ $payroll->overtime }}</td>
                <td>{{ $payroll->deductions }}</td>
                <td>{{ $payroll->tax }}</td>
                <td>{{ $payroll->net_salary }}</td>
            </tr>
        @endforeach


    </table>



    <br><br>

    <p class="text-center">
        -----------------------------<br>
        HR Department
    </p>

</body>

</html>
