@props(['id', 'name', 'value', 'required'=> false, 'disabled' => false, 'correct_answer' => false, 'my_answer' => false])


<div class="radio-input">
  <input type="radio" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" required="{{ $required }}" {{ $disabled ? 'disabled' : '' }}>
  <label for="{{ $id }}" class="clue-content cmu-serif text-sm {{ $my_answer ? 'bg-red-400' : ($correct_answer ? 'bg-green-400' : 'bg-white') }}">
      <div class="w-full text-center clue-content cmu-serif text-sm"> {!! $slot !!} </div>
  </label>
</div>


  <style>
.radio-input {
  display: flex;
  flex-direction: row;
  font-size: 14px;
  color: #212121;
  margin-bottom: 10px;
}

.radio-input input[type="radio"] {
  display: none;
}

.radio-input label {
  display: flex;
  align-items: center;
  padding: 10px;
  min-width: 200px;
  /* border: 1px solid #ccc; */
  border-radius: 5px;
  margin-right: 12px;
  cursor: pointer;
  position: relative;
  transition: all 0.3s ease-in-out;
}

/* .radio-input label:before {
  content: "";
  display: block;
  position: absolute;
  top: 50%;
  left: 0;
  transform: translate(-50%, -50%);
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #8d3a7c; 
  border: 2px solid #ccc;
  transition: all 0.3s ease-in-out;
}

.radio-input input[type="radio"]:checked + label:before {
  background-color: #f4e736; 
  top: 0;
} */

.radio-input input[type="radio"]:checked + label {
  background-color: #d7d7d7; /* Changed from light green to grey */
  color: #212121;
  animation: radio-translate 0.5s ease-in-out;
}

@keyframes radio-translate {
  0% {
    transform: translateX(0);
  }

  50% {
    transform: translateY(-10px);
  }

  100% {
    transform: translateX(0);
  }
}
</style>