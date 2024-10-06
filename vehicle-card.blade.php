<div class="card vehicleCard rounded-4 shadow-sm" data-vehicle-id="{{ $vehicle->id }}"
    data-device-id="{{ $vehicle->idDevice }}" data-plate-number="{{ $vehicle->plate_number }}"
    data-company-name="{{ $company->companyName }}">
    <div class="card-body">
        {{-- Dati Azienda --}}
        <div class="d-flex justify-content-center align-content-center align-items-center ">
            <div class="me-2 p-2 badge bg-secondary">
                <i class="fa-solid fa-building"></i> {{ $company->companyName }}
            </div>

            @if ($vehicle->brand != '' || $vehicle->model != '' || $vehicle->color != '')
                <div class="me-2 p-2 badge bg-secondary">
                    <i class="{{ $icon }}"></i>

                    @if ($vehicle->brand != '')
                        {{ $vehicle->brand }}
                    @endif

                    @if ($vehicle->model != '')
                        {{ $vehicle->model }}
                    @endif

                    @if ($vehicle->color != '')
                        {{ $vehicle->color }}
                    @endif

                </div>
            @endif
        </div>

        {{-- Dati Guidatore --}}
        @if ($vehicle->driverName != '' || $vehicle->driverPhone != '' || $vehicle->driverAlternativePhone != '')
            <div class="mt-2 d-flex justify-content-center align-content-center align-items-center ">
                @if ($vehicle->driverName != '')
                    <div class="me-2 p-2 badge bg-secondary">
                        <i class="fa-solid fa-user"></i> {{ $vehicle->driverName }}
                    </div>
                @endif

                @if ($vehicle->driverPhone != '')
                    <div class="me-2 p-2 badge bg-secondary">
                        <i class="fa-solid fa-phone"></i> {{ $vehicle->driverPhone }}
                    </div>
                @endif

                @if ($vehicle->driverAlternativePhone != '')
                    <div class="me-2 p-2 badge bg-secondary">
                        <i class="fa-solid fa-phone"></i> {{ $vehicle->driverAlternativePhone }}
                    </div>
                @endif
            </div>
        @else
            <div class="mt-2 d-flex justify-content-center align-content-center align-items-center ">
                <div class="me-2 p-2 badge bg-danger">
                    <i class="fa-solid fa-user"></i> Nessun Guidatore
                </div>
            </div>
        @endif

        <x-snipped.plate :plateNumber="$vehicle->plate_number" />
        {{-- Dati Onlone --}}
        <div class="mt-1 d-flex justify-content-center align-content-center align-items-center ">
            @php
                $lockId = 0;
                $unlockId = 0;
            @endphp

            @if (Auth::user()->userLevel == 'Admin' || Auth::user()->userLevel == 'Agency')
                @foreach ($vehicle->device->deviceModel->TraccarCommand as $command)
                    @if ($command->actionCommand == 'lockEngine')
                        @php
                            $lockId = $command->idTraccarCommand;
                        @endphp
                    @endif

                    @if ($command->actionCommand == 'unlockEngine')
                        @php
                            $unlockId = $command->idTraccarCommand;
                        @endphp
                    @endif
                @endforeach

                <button data-indentify="engineBlock" data-lock-id="{{ $lockId }}"
                    data-unlock-id="{{ $unlockId }}"
                    data-command-id="{{ $motore == 'Sbloccato' ? $lockId : $unlockId }}"
                    data-value="{{ $motore }}" @class([
                        'me-2 p-2 badge btn',
                        'bg-success' => $motore == 'Sbloccato',
                        'bg-danger' => !($motore == 'Sbloccato'),
                    ])>
                    <i @class([
                        'fa-solid',
                        'fa-unlock' => $motore == 'Sbloccato',
                        'fa-lock' => !($motore == 'Sbloccato'),
                    ])></i> <span class="description">{{ $motore }} </span>
                </button>
            @else
                <span data-indentify="engineBlock" data-value="{{ $motore }}" @class([
                    'me-2 p-2 badge btn',
                    'bg-success' => $motore == 'Sbloccato',
                    'bg-danger' => !($motore == 'Sbloccato'),
                ])>
                    <i @class([
                        'fa-solid',
                        'fa-unlock' => $motore == 'Sbloccato',
                        'fa-lock' => !($motore == 'Sbloccato'),
                    ])></i> <span class="description">{{ $motore }} </span>
                </span>
            @endif


            <div data-indentify="ignition" data-value="{{ $ignition }}" @class([
                'me-2 p-2 badge btn',
                'bg-success' => $ignition,
                'bg-danger' => !$ignition,
            ])>
                <i class="fa-solid fa-key"></i> <span class="description">
                    @if (!$ignition)
                        Quadro Spento
                    @endif
                </span>
            </div>

            <div data-indentify="speed" data-value="{{ $speed }}" @class(['me-2 p-2 badge bg-warning btn', 'd-none' => !$ignition])>
                <i class="fa-solid fa-gauge-high"></i> <span class="description">{{ $speed }}</span> Km/h
            </div>


            <div data-indentify="deviceStatus" data-value="{{ $status }}" @class([
                'me-2',
                'p-2 badge btn',
                'bg-success' => $status == 'Online',
                'bg-secondary' => !($status == 'Online'),
            ])>
                <i @class([
                    'fa-solid',
                    'fa-signal' => $status == 'Online',
                    'fa-clock' => !($status == 'Online'),
                ]) class="fa-solid "></i>
                <span class="description">
                    {{ ucfirst($status) }}
                    {{-- @if ($status == 'Online')
                    {{ $status }}
                    @else
                        {{ $lastupdate }}
                    @endif --}}
                </span>
            </div>

        </div>

        <div data-indentify="lastUpdate" data-value="{{ $lastUpdate }}"
            class="mt-1 d-flex justify-content-center align-content-center align-items-center text-muted">
            <i class="fa-solid fa-clock fs-6 me-2 "></i>
            Ultimo Posizione: <span class="description"> {{ $lastUpdate }} </span>
        </div>
        <hr />

        {{-- Dati Posizione --}}
        <div data-indentify="address" data-value="{{ $address }}"
            class="mt-1 d-flex justify-content-center align-content-center align-items-center ">
            <button class="p-2 w-100 btn btn-info addressVehicleButton">
                <i class="fa-solid fa-location-dot fs-6 me-2 "></i>
                <span class="description">
                    <span class="lh-lg">{!! wordwrap($address, 60, "</span><br /><span class='lh-lg'>") !!}</span>
                </span>
            </button>
        </div>
        <div class="mt-1 d-flex justify-content-between align-content-center align-items-center ">
            <a data-indentify="streetView" class="w-100 streetView me-1 btn btn-outline-primary fs-6" target="_blank"
                href="{{ $URLstreetView }}"> <i class="fa-solid fa-street-view"></i> STREET </a>
            <a data-indentify="appleMap" class="w-100 appleMap me-1 btn btn-outline-dark fs-6" target="_blank"
                href="{{ $URLappleMap }}"><i class="fa-brands fa-apple"></i> APPLE </a>
            <a data-indentify="googleMap" class="w-100 googleMap btn btn-outline-primary fs-6" target="_blank"
                href="{{ $URLgoogleMap }}"><i class="fa-solid fa-map-location-dot"></i> MAPS</a>
        </div>
        <hr />

        {{-- <div class="mt-1 text-center">
            @if (Auth::user()->userLevel == 'Admin' || Auth::user()->userLevel == 'Agency')
                @foreach ($vehicle->device->deviceModel->TraccarCommand as $command)
                    <button data-indentify="command" data-command-id="{{ $command->idTraccarCommand }}"
                        class="btn mb-2 accordion btn-success w-auto {{ $command->actionCommand }}">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                        {{ $traslation[$command->actionCommand] }} </button>
                @endforeach
            @endif
        </div> --}}

        @if (Route::currentRouteName() == 'dashboard')
            <div class="mt-1 align-items-center d-flex justify-content-center gap-1 ">
                <a class="btn w-50 btn-outline-warning" href="{{ route('vehicles.show', $vehicle->id) }}">
                    <i class="fa-solid fa-circle-info"></i> Dettagli
                </a>
                <button class="btn w-50 btn-outline-success infoVehicleButton" role="button" data-bs-toggle="modal"
                    data-bs-target="#vehicleModalDetail">
                    <i class="fa-solid fa-circle-info"></i> Informazioni
                </button>
                @if (Auth::user()->userLevel == 'Admin')
                    <a class="btn w-50 btn-outline-warning" href="{{ route('managments.vehicles.edit', $vehicle->id) }}">
                        <i class="fa-solid fa-edit"></i> Edit
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>
