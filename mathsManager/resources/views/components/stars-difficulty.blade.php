@props(['starsActive' => 2, 'id' => 'rating'])

<div class="rating">
    @for ($i = 5; $i >= 1; $i--)
        <input value="{{ $i }}" name="{{ $id }}" id="{{ $id }}-star{{ $i }}" type="radio" @if($i == $starsActive) checked @endif disabled />
        <label for="{{ $id }}-star{{ $i }}"></label>
    @endfor
</div>

<style>
.rating input {
  display: none;
}

.rating label {
  float: right;
  color: #ccc;
  transition: color 0.3s;
}

.rating label:before {
  content: '\2605';
}

/* Change the color of the label when the input is checked */
.rating input:checked ~ label:before {
  color: #947767;
}
</style>