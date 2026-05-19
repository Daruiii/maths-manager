@extends('layouts.app')

@section('content')
    <div id="loadingPopup"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
            <svg style="left: 50%; top: 50%; position: absolute; transform: translate(-50%, -50%) matrix(1, 0, 0, 1, 0, 0);"
                preserveAspectRatio="xMidYMid meet" viewBox="0 0 187.3 93.7" height="300px" width="400px">
                <!-- SVG content omitted for brevity -->
            </svg>
        </div>
    </div>
    <x-back-btn path="">Retour</x-back-btn>
    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Réaffecter un devoir</h1>
            <p class="form-explain mb-5"> Ici, vous pouvez réaffecter un devoir à un autre utilisateur. </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('ds.reAssign') }}">
                @csrf
                <input type="hidden" name="ds_id" value="{{ $ds->id }}">
                <div class="form-group">
                    <label>User :</label>
                    <select id="user" name="user_id" style="width: 100%;">
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Réaffecter</button>
            </form>
        </div>
    </section>
@endsection