@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Détails de la correction</div>

                <div class="card-body">
                    @auth
                    @if (Auth::user()->role == 'teacher' || Auth::user()->role == 'admin')
                        <a href="{{ route('ds.show', $correctionRequest->ds_id) }}" class="btn btn-primary">Voir le DS lié</a>
                        {{-- corriger --}}
                            <a href="{{ route('correctionRequest.correctForm', $correctionRequest->ds_id) }}" class="btn btn-success">Corriger</a>
                    @endif
                    @endauth
                    @if ($correctionRequest->correction_message)
                    <p><strong>Message de correction:</strong> {{ $correctionRequest->correction_message }}</p>
                    @endif
                    @if ($correctionRequest->status == 'corrected')
                    <p><strong>Note attribuée:</strong> {{ $correctionRequest->grade }}/20</p>
                    @endif
                    <p><strong>Statut:</strong> {{ $correctionRequest->status }}</p>

                    <h3>Images de la demande de correction:</h3>
                    <div class="row">
                        @foreach ($pictures as $picture)
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <img src="{{ asset('storage/' . $picture) }}" class="card-img-top" alt="Image de la demande de correction" style="max-height: 200px;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <a href="{{ asset('storage/' . $picture) }}" class="btn btn-primary" target="_blank" style="width: 100%;">Ouvrir</a>
                                            </div>
                                            @auth
                                            @if (Auth::user()->role == 'teacher' || Auth::user()->role == 'admin')
                                                <div>
                                                    <a href="{{ asset('storage/' . $picture) }}" class="btn btn-success" download style="width: 100%;">Télécharger</a>
                                                </div>
                                            @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($correctedPictures)
                    <h3>Correction :</h3>
                    @foreach ($correctedPictures as $correctionPicture)
                        <img src="{{ asset('storage/' . $correctionPicture) }}" alt="Image de la correction">
                    @endforeach
                    @else
                    <p>Correction non disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
