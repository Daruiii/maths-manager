@extends('layouts.app')

@section('content')
<div id="loadingPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
        <svg style="left: 50%; top: 50%; position: absolute; transform: translate(-50%, -50%) matrix(1, 0, 0, 1, 0, 0);" 
             preserveAspectRatio="xMidYMid meet" viewBox="0 0 187.3 93.7" height="300px" width="400px">
            <path d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 
                    c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z" 
                  stroke-miterlimit="10" stroke-linejoin="round" stroke-linecap="round" stroke-width="4" fill="none" 
                  id="outline" stroke="#4E4FEB"></path>
            <path d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 
                    c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z" 
                  stroke-miterlimit="10" stroke-linejoin="round" stroke-linecap="round" stroke-width="4" stroke="#4E4FEB" 
                  fill="none" opacity="0.05" id="outline-bg"></path>
        </svg>
    </div>
</div>

<section class="form-wrapper">
    <div class="form">
        <h1 class="form-title">Modifier le Contenu</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('content.update', $content->section) }}" method="POST" enctype="multipart/form-data" id="contentForm">
            @csrf
            @method('PUT')

            <!-- Titre -->
            <div class="form-group">
                <label for="title">Titre :</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Titre du contenu" value="{{ old('title', $content->title) }}">
            </div>

            <!-- Contenu principal -->
            <div class="form-group">
                <label for="content">Contenu :</label>
                <textarea class="form-control" id="content" name="content" rows="4" placeholder="Contenu de la section...">{{ old('content', $content->content) }}</textarea>
            </div>

            <!-- Image -->
            <div class="form-group">
                <label for="image">Image :</label>
                <input type="file" class="form-control" id="image" name="image">
                @if ($content->image)
                    <p>Image actuelle :</p>
                    <img src="{{ asset($content->image) }}" alt="Image actuelle" class="w-24 h-24 my-2">
                    <label class="flex items-center space-x-2 mt-2">
                        <input type="checkbox" name="remove_image" value="true">
                        <span class="text-sm">Supprimer l'image actuelle</span>
                    </label>
                @endif
            </div>

            <button type="submit" class="submit-btn-form">Mettre à jour le Contenu</button>
        </form>
    </div>
</section>

<script>
    // Montre le popup de chargement lors de la soumission
    document.getElementById('contentForm').addEventListener('submit', function() {
        document.getElementById('loadingPopup').style.display = 'block';
    });
</script>
@endsection
