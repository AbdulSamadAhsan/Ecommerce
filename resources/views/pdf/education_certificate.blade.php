<div
    style="
    width: 100%;
    
    box-sizing: border-box;
    font-family: 'DejaVu Sans', sans-serif;
    color: #222;
">

    <div style="
        border: 3px solid #1f3b5b;
        padding: 6px;
    ">
        <div
            style="
            border: 1px solid #b79a58;
            padding: 55px;
            text-align: center;
        ">

            {{-- Heading --}}
            <div
                style="
                font-size: 32px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 3px;
                color: #1f3b5b;
                margin-bottom: 8px;
            ">
                Certificate
            </div>

            <div
                style="
                width: 90px;
                border-bottom: 2px solid #b79a58;
                margin: 0 auto 35px auto;
            ">
            </div>

            {{-- Intro --}}
            <div
                style="
                font-size: 16px;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                color: #666;
                margin-bottom: 22px;
            ">
                This is to certify that
            </div>

            {{-- Candidate --}}

            @if (!empty($applicannt_photo))
                @php
                    $photoPath = public_path('storage/candidate/' . $applicannt_photo);
                @endphp

                @if (file_exists($photoPath))
                    <img src="{{ $photoPath }}" alt="Candidate Photo"
                        style="
                width: 110px;
                height: 130px;
                object-fit: cover;
                border: 2px solid #1f3b5b;
                padding: 3px;
            ">
                @endif
            @endif
            <div
                style="
                font-size: 27px;
                font-weight: bold;
                color: #1f3b5b;
                margin-bottom: 8px;
            ">
                {{ $candidate }}
            </div>

            {{-- Father Name --}}
            <div
                style="
                font-size: 16px;
                color: #555;
                margin-bottom: 30px;
            ">
                @if ($gender == 'male')
                    S/O
                @else
                    D/O
                @endif

                <strong>{{ $father_name }}</strong>
            </div>

            {{-- Certificate Text --}}
            <div
                style="
                max-width: 700px;
                margin: 0 auto;
                font-size: 18px;
                line-height: 2;
                color: #333;
            ">
                has successfully passed

                <strong style="color: #1f3b5b;">
                    {{ $education }}
                </strong>

                from

                <strong style="color: #1f3b5b;">
                    {{ $institute }}
                </strong>

                in the session

                <strong>
                    {{ date('Y', strtotime($yearstart)) }} - {{ date('Y', strtotime($endyear)) }}
                </strong>

                with

                <strong style="color: #1f3b5b;">
                    @if ($insurance_type == 'university')
                        {{ $grade }} CGPA
                    @else
                        {{ $grade }} %
                    @endif
                </strong>.
            </div>

        </div>
    </div>

</div>
