<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        h2 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
        h3 {
            text-align: center;
            font-size: 14px;
            margin-bottom: 8px;
        }
        h3.left {
            text-align: left;
        }
        p {
            font-size: 12px;
            margin-bottom: 5px;
        }
        p.center {
            text-align: center;
        }
        p.tight {
            margin: 2px 0;
        }
        strong {
            font-size: 12px;
        }
        img {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .profile-picture {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .profile-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .profile-row {
            display: table-row;
        }
        .profile-col-photo {
            display: table-cell;
            width: 20%; /* Memberikan ruang lebih kecil untuk foto */
            vertical-align: middle;
            text-align: left;
        }
        .profile-col-text {
            display: table-cell;
            width: 80%; /* Memberikan ruang lebih besar untuk teks */
            padding-left: 10px;
            vertical-align: top;
        }

        .other-container {
            display: table;
            width: 100%;
        }
        .other-row {
            display: table-row;
        }
        .other-col-text1 {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: left;
            padding-right: 5px;
        }
        .other-col-text2 {
            display: table-cell;
            width: 50%;
            padding-left: 5px;
            text-align: left;
            vertical-align: top;
        }

        .lain-picture {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .lain-container {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .lain-row {
            display: table-row;
        }
        .lain-col-photo {
            display: table-cell;
            width: 15%;
            vertical-align: top;
            text-align: left;
        }
        .lain-col-text {
            display: table-cell;
            width: 85%;
            padding-left: 5px;
            vertical-align: top;
        }
        .mb-1 {
            margin-bottom: 0.5rem !important;
        }
        .lain-col-text-full {
            width: 100%; /* Lebar penuh jika tidak ada media */
            padding-left: 0;
            text-align: left;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-row">
            <div class="profile-col-photo">
                @if ($user->profile_pict)
                    <img src="{{ public_path($user->profile_pict) }}" alt="Profile Picture" class="profile-picture">
                @else
                    <span>No Picture</span>
                @endif
            </div>
            <div class="profile-col-text">
                <h2>{{ $user->fullname }}</h2>
                <p class="center">{{ $user->city->city_name }} | {{ $user->nickname }} | {{ $user->email }} | {{ $user->phone }}</p>
                <p class="center">{{ $user->address }} | {{ $user->birth_date }} | {{ $user->gender }}</p>
            </div>
        </div>
    </div>

    <p class="tight">
        @foreach ($user->userhrjobs as $uhj)
            <p class="tight"><strong>{{ $uhj->hrjob->job_name }}</strong> | {{ $uhj->created_at }}</p>
        @endforeach
    </p>

    <p class="tight">
        <strong>Social:</strong>
        @foreach ($user->link as $index => $lin)
            <a href="{{ $lin->media_url }}" target="_blank" rel="noopener noreferrer">
                {{ $lin->media }}
            </a>
            @if (!$loop->last) | @endif
        @endforeach
    </p>

    <p class="tight">
        <strong>Source:</strong>
        {{ $user->source->map(function($item) {
            return collect($item)
                ->only(['platform', 'referal', 'other'])
                ->filter()
                ->map(function($value) {
                    return ucwords(str_replace('_', ' ', $value));
                })
                ->implode(', ');
        })->implode(', ') }}
    </p>

    @foreach ($user->about as $abo)
        <h3 class="left">About</h3>
        <p>{{ $abo->about }}</p>
    @endforeach

    <p class="tight">
        <strong>Preferred Work Locations:</strong>
        {{ $user->worklocation->pluck('city.city_name')->implode(', ') }}
    </p>

    <p class="tight">
        <strong>Preferred Work Fields:</strong>
        {{ $user->workfield->pluck('field.field_name')->implode(', ') }}
    </p>

    <p class="tight">
        <strong>Skills:</strong>
        {{ $user->workskill->pluck('skill.skill_name')->implode(', ') }}
    </p>

    <div class="other-container">
        <div class="other-row">
            <div class="other-col-text1">
                <h3 class="left">Education</h3>
                @foreach ($user->education as $edu)
                    <p class="tight"><strong>{{ $edu->university }}</strong></p>
                    <p class="tight">{{ ucwords(str_replace('_', ' ', $edu->degree))}} - {{ $edu->major }}</p>
                    <p class="tight">{{ $edu->start_date }} - {{ $edu->end_date }}</p>
                @endforeach
            </div>
            <div class="other-col-text2">
                <h3 class="left">Language</h3>
                @foreach ($user->language as $lang)
                    <p class="tight"><strong>{{ $lang->language }}</strong></p>
                    <p class="tight">{{ ucwords(str_replace('_', ' ', $lang->skill))}}</p>
                @endforeach
            </div>
        </div>
    </div>
    <h3 class="left">Project</h3>
    <div class="lain-container">
        <div class="lain-row">
            @if ($user->project->whereNotNull('media')->count())
                <div class="lain-col-photo">
                    @foreach ($user->project as $pro)
                        @if ($pro->media)
                            <img src="{{ public_path($pro->media) }}" alt="Project Picture" class="lain-picture">
                        @endif
                    @endforeach
                </div>
                <div class="lain-col-text">
                    @foreach ($user->project as $pro)
                        <p class="tight"><strong>{{ $pro->project_name }}</strong></p>
                        <p class="tight">{{ $pro->start_date }} - {{ $pro->end_date }}</p>
                        <p class="tight">{{ $pro->description }}</p>
                    @endforeach
                </div>
            @else
                <div class="lain-col-text-full">
                    @foreach ($user->project as $pro)
                        <p class="tight"><strong>{{ $pro->project_name }}</strong></p>
                        <p class="tight">{{ $pro->start_date }} - {{ $pro->end_date }}</p>
                        <p class="tight">{{ $pro->description }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <h3 class="left">Experience</h3>
    @foreach ($user->experience as $exp)
        <p class="tight"><strong>{{ $exp->position }}</strong></p>
        <p class="tight">{{ $exp->company_name }} - {{ ucwords(str_replace('_', ' ', $exp->job_type))}}</p>
        <p class="tight">{{ $exp->start_date }} - {{ $exp->end_date }}</p>
        <p class="tight">{{ $exp->location }} - {{ ucwords(str_replace('_', ' ', $exp->location_type))}}</p>
        <p class="tight">Responsibility: {{ $exp->responsibility }}</p>
        <p class="tight mb-1">Job Report: {{ $exp->job_report }}</p>
    @endforeach
    <h3 class="left">Certification</h3>
    <div class="lain-container">
        <div class="lain-row">
            @if ($user->certification->whereNotNull('media')->count())
                <div class="lain-col-photo">
                    @foreach ($user->certification as $crt)
                        @if ($crt->media)
                            <img src="{{ public_path($crt->media) }}" alt="Certification Picture" class="lain-picture">
                        @endif
                    @endforeach
                </div>
                <div class="lain-col-text">
                    @foreach ($user->certification as $crt)
                        <p class="tight"><strong>{{ $crt->lisence_name }}</strong></p>
                        <p class="tight">{{ $crt->organization }}</p>
                        <p class="tight">{{ $crt->start_date }} - {{ $crt->end_date }}</p>
                        <p class="tight">{{ $crt->description }}</p>
                        <p class="tight">ID Credential: {{ $crt->id_credentials }}</p>
                        <p class="tight">URL Credential: {{ $crt->url_credentials }}</p>
                    @endforeach
                </div>
            @else
                <div class="lain-col-text-full">
                    @foreach ($user->certification as $crt)
                    <p class="tight"><strong>{{ $crt->licence_name }}</strong></p>
                    <p class="tight">{{ $crt->organization }}</p>
                    <p class="tight">{{ $crt->start_date }} - {{ $crt->end_date }}</p>
                    <p class="tight">{{ $crt->description }}</p>
                    <p class="tight">ID Credential: {{ $crt->id_credentials }}</p>
                    <p class="tight">URL Credential: {{ $crt->url_credentials }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <h3 class="left">Organization</h3>
    @foreach ($user->organization as $org)
        <p class="tight"><strong>{{ $org->organization }}</strong></p>
        <p class="tight">{{ $org->position }} - {{ $org->associated }}</p>
        <p class="tight">{{ $org->start_date }} - {{ $org->end_date }}</p>
        <p class="tight"> {{ $org->responsibility }}</p>
        <p class="tight mb-1">{{ $org->description }}</p>
    @endforeach
    <h3 class="left">Volunteer</h3>
    <div class="lain-container">
        <div class="lain-row">
            @if ($user->volunteer->whereNotNull('media')->count())
                <div class="lain-col-photo">
                    @foreach ($user->volunteer as $vol)
                        @if ($vol->media)
                            <img src="{{ public_path($vol->media) }}" alt="Volunteer Picture" class="lain-picture">
                        @endif
                    @endforeach
                </div>
                <div class="lain-col-text">
                    @foreach ($user->volunteer as $vol)
                        <p class="tight"><strong>{{ $vol->organization }}</strong></p>
                        <p class="tight">{{ $vol->role }} - {{ $vol->issue }}</p>
                        <p class="tight">{{ $vol->start_date }} - {{ $vol->end_date }}</p>
                        <p class="tight">{{ $vol->description }}</p>
                    @endforeach
                </div>
            @else
                <div class="lain-col-text-full">
                    @foreach ($user->volunteer as $vol)
                        <p class="tight"><strong>{{ $vol->organization }}</strong></p>
                        <p class="tight">{{ $vol->role }} - {{ $vol->issue }}</p>
                        <p class="tight">{{ $vol->start_date }} - {{ $vol->end_date }}</p>
                        <p class="tight">{{ $vol->description }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <h3 class="left">Reference Contact</h3>
    @foreach ($user->reference as $ref)
        <p class="tight"><strong>{{ $ref->reference_name }}</strong></p>
        <p class="tight">{{ $ref->relation }} - {{ $ref->company_name }}</p>
        <p class="tight mb-1">{{ $ref->phone }} - {{ ucwords(str_replace('_', ' ', $ref->is_call))}}</p>
    @endforeach
    <h3 class="left">Emergency Contact</h3>
    @foreach ($user->emergency as $eme)
        <p class="tight"><strong>{{ $eme->emergency_name }}</strong></p>
        <p class="tight">{{ $eme->emergency_relation }}</p>
        <p class="tight mb-1">{{ $eme->emergency_phone }}</p>
    @endforeach

</body>
</html>
