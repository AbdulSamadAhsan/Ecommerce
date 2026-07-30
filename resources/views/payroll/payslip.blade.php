<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payslip</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: DejaVu Sans, Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            padding: 25px;
            color: #333;
            font-size: 13px;
        }



        .header {
            width: 100%;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header table {
            width: 100%;
        }

        .logo {
            width: 80px;
        }

        .company-name {
            font-size: 26px;
            color: #0d6efd;
            font-weight: bold;
        }

        .company-info {
            text-align: right;
            line-height: 22px;
        }

        .title {
            text-align: center;
            margin-bottom: 25px;
        }

        .title h2 {
            color: #0d6efd;
            font-size: 24px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .section-title {
            background: #0d6efd;
            color: white;
            padding: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        .salary {
            margin-top: 20px;
        }

        .salary th {
            background: #0d6efd;
            color: white;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .salary td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 40%;
            margin-left: auto;
            margin-top: 25px;
        }

        .summary td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .summary tr:last-child {
            background: #198754;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .footer {
            margin-top: 70px;
        }

        .footer table {
            width: 100%;
        }

        .signature {
            width: 30%;
            text-align: center;
        }

        .signature hr {
            margin-bottom: 8px;
        }

        .status {
            display: inline-block;
            padding: 5px 12px;
            background: #198754;
            color: white;
            border-radius: 3px;
            font-weight: bold;
        }

        .note {
            margin-top: 30px;
            font-size: 11px;
            color: #777;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>

</head>

<body>

    <div class="container">

        <!-- Header -->

        <div class="header">

            <table>

                <tr>

                    <td width="20%">
                        {{-- Company Logo --}}
                        <img src="{{ public_path('images/logo.png') }}" class="logo">
                    </td>

                    <td width="40%">

                        <div class="company-name">
                            Tech Store
                        </div>



                    </td>

                    <td width="40%" class="company-info">

                        <strong>Payslip No:</strong>
                        #{{ $salaryPayment->id }} <br>

                        <strong>Payroll Month:</strong>

                        {{ date('F Y', strtotime($salaryPayment->payroll->month)) }}

                        <br>
                        @if ($salaryPayment->paid_date != null)
                            <strong>Payment Date:</strong>

                            {{ date('d M Y', strtotime($salaryPayment->paid_date)) }}

                            <br>

                            <span class="status">PAID</span>
                        @endif

                    </td>

                </tr>

            </table>

        </div>

        <!-- Title -->

        <div class="title">

            <h2>EMPLOYEE PAYSLIP</h2>

            Salary Statement

        </div>

        <!-- Employee -->

        <div class="section-title">
            Employee Information
        </div>

        <table class="info">

            <tr>

                <td><strong>Employee ID</strong></td>
                <td>{{ $employee->employee_code }}</td>

                <td><strong>Name</strong></td>
                <td>{{ $employee->user->name }}</td>

            </tr>

            <tr>

                <td><strong>Department</strong></td>
                <td>{{ $employee->department->name }}</td>

                <td><strong>Designation</strong></td>
                <td>{{ $employee->designation }}</td>

            </tr>

            <tr>

                <td><strong>Email</strong></td>
                <td>{{ $employee->user->email }}</td>

                <td><strong>Joining Date</strong></td>
                <td>{{ date('d M Y', strtotime($employee->joining_date)) }}</td>

            </tr>

        </table>

        <!-- Salary -->
        @php
            $basic = (float) $salaryPayment->payroll->basic_salary;
            $allowance = (float) ($salaryPayment->payroll->allowance ?? 0);
            $bonus = (float) ($salaryPayment->payroll->bonus ?? 0);
            $overtime = (float) ($salaryPayment->payroll->overtime ?? 0);

            $tax = (float) ($salaryPayment->payroll->tax ?? 0);
            $deductions = (float) ($salaryPayment->payroll->deductions ?? 0);

            $totalEarnings = $basic + $allowance + $bonus + $overtime;
            $totalDeductions = $tax + $deductions;
            $netSalary = $salaryPayment->net_salary;
        @endphp



        <!-- Bank Details -->

        <div class="section-title">
            Bank Details
        </div>

        <table class="info">

            <tr>

                <td width="25%">
                    <strong>Bank Name</strong>
                </td>

                <td width="25%">
                    {{ $employee->bank_name ?: '-' }}
                </td>

                <td width="25%">
                    <strong>Account Title</strong>
                </td>

                <td width="25%">
                    {{ $employee->account_title ?: '-' }}
                </td>

            </tr>

            <tr>

                <td>
                    <strong>Account Number</strong>
                </td>

                <td>
                    {{ $employee->account_number ?: '-' }}
                </td>

                <td>
                    <strong>IBAN</strong>
                </td>

                <td>
                    {{ $employee->iban ?: '-' }}
                </td>

            </tr>

        </table>


        <br>


        <!-- Attendance Summary -->

        <div class="section-title">
            Attendance Summary
        </div>

        <table class="info">

            <tr>

                <td width="25%">
                    <strong>Payroll Period</strong>
                </td>

                <td width="25%">
                    {{ $monthStart->format('d M Y') }}
                    -
                    {{ $monthEnd->format('d M Y') }}
                </td>

                <td width="25%">
                    <strong>Total Days</strong>
                </td>

                <td width="25%">
                    {{ $daysInMonth }}
                </td>

            </tr>

            <tr>

                <td>
                    <strong>Present Days</strong>
                </td>

                <td>
                    {{ $presentDays }}
                </td>

                <td>
                    <strong>Absent Days</strong>
                </td>

                <td>
                    {{ $absentDays }}
                </td>

            </tr>

            <tr>

                <td>
                    <strong>Leave Days</strong>
                </td>

                <td>
                    {{ $leaveDays }}
                </td>

                <td>
                    <strong>Late Days</strong>
                </td>

                <td>
                    {{ $lateDays }}
                </td>

            </tr>

            <tr>

                <td>
                    <strong>Half Days</strong>
                </td>

                <td>
                    {{ $halfDays }}
                </td>

                <td>
                    <strong>Working Days</strong>
                </td>

                <td>
                    {{ $presentDays + $lateDays + $halfDays }}
                </td>

            </tr>

        </table>

        <br>

        @php
            use Carbon\Carbon;
            use Carbon\CarbonPeriod;

            $start = $monthStart->copy()->startOfMonth();
            $end = $monthStart->copy()->endOfMonth();

            $firstDay = $start->dayOfWeek; // 0 = Sunday
            $daysInMonth = $start->daysInMonth;
        @endphp

        <div class="section-title">
            Monthly Attendance Calendar
        </div>

        <table width="100%" border="1" cellspacing="0" cellpadding="6"
            style="border-collapse:collapse;text-align:center;font-size:11px;">
            <thead style="background:#e9ecef;">
                <tr>
                    <th style="color:red;">Sun</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th style="color:blue;">Sat</th>
                </tr>
            </thead>

            <tbody>

                @php
                    $day = 1;

                @endphp

                @while ($day <= $daysInMonth)

                    <tr>

                        @for ($i = 0; $i < 7; $i++)
                            @if ($day == 1 && $i < $firstDay)
                                <td></td>
                            @elseif($day > $daysInMonth)
                                <td></td>
                            @else
                                @php
                                    $currentDate = Carbon::create($start->year, $start->month, $day);

                                    $key = $currentDate->format('Y-m-d');

                                    $attendance = $attendanceMap[$key] ?? null;

                                    $label = '';

                                    if ($currentDate->isSunday()) {
                                        $label = 'SUN';
                                    } elseif ($currentDate->isSaturday()) {
                                        $label = 'SAT';
                                    }
                                @endphp

                                <td
                                    @if ($currentDate->isSunday()) style="background:#ffe6e6;"
                        @elseif($currentDate->isSaturday())
                            style="background:#e8f3ff;" @endif>


                                    @if ($attendance && $attendance->status == 'leave')
                                        <strong> <span style="border-radius:100%; border:1px solid red;">
                                                {{ $day }}</span>
                                        </strong>
                                    @else
                                        <strong>{{ $day }}</span>
                                    @endif

                                </td>

                                @php
                                    $day++;
                                @endphp
                            @endif
                        @endfor

                    </tr>

                @endwhile

            </tbody>
        </table>


        <table class="salary">

            <thead>
                <tr>
                    <th width="35%">Earnings</th>
                    <th width="15%">Amount</th>
                    <th width="35%">Deductions</th>
                    <th width="15%">Amount</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>Basic Salary</td>
                    <td class="text-right">{{ number_format($basic, 2) }}</td>

                    <td>Income Tax</td>
                    <td class="text-right">{{ number_format($tax, 2) }}</td>
                </tr>

                <tr>
                    <td>Allowance</td>
                    <td class="text-right">{{ number_format($allowance, 2) }}</td>

                    <td>Other Deductions</td>
                    <td class="text-right">{{ number_format($deductions, 2) }}</td>
                </tr>

                <tr>
                    <td>Bonus</td>
                    <td class="text-right">{{ number_format($bonus, 2) }}</td>

                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Overtime</td>
                    <td class="text-right">{{ number_format($overtime, 2) }}</td>

                    <td></td>
                    <td></td>
                </tr>

                <tr style="font-weight:bold;background:#f8f9fa;">
                    <td colspan="3">Total Earnings</td>
                    <td class="text-right">
                        {{ number_format($totalEarnings, 2) }}
                    </td>
                </tr>

                <tr style="font-weight:bold;background:#f8f9fa;">
                    <td colspan="3">Total Deductions</td>
                    <td class="text-right">
                        {{ number_format($totalDeductions, 2) }}
                    </td>
                </tr>

            </tbody>

        </table>


        <!-- Summary -->

        <table class="summary">

            <tr>

                <td>Gross Salary</td>

                <td class="text-right">

                    {{ number_format((float) $salaryPayment->payroll->basic_salary + (float) $salaryPayment->payroll->bonus, 2) }}

                </td>

            </tr>

            <tr>

                <td>Total Deductions</td>

                <td class="text-right">

                    {{ number_format((float) $salaryPayment->payroll->tax + (float) $salaryPayment->payroll->deductions, 2) }}

                </td>

            </tr>

            <tr>

                <td>Net Salary</td>

                <td class="text-right">

                    {{ number_format($salaryPayment->payroll->net_salary, 2) }}

                </td>

            </tr>

        </table>

        <!-- Footer -->

        <div class="footer">

            <table>

                <tr>

                    <td class="signature">

                        <hr>

                        Employee Signature

                    </td>

                    <td class="signature">

                        <hr>

                        HR Manager

                    </td>

                    <td class="signature">

                        <hr>

                        Finance Manager

                    </td>

                </tr>

            </table>

        </div>

        <div class="note">

            This is a computer-generated payslip. No physical signature is required.

        </div>

    </div>

</body>

</html>
