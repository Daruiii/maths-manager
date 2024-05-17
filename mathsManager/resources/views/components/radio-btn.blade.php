@props(['id', 'name', 'value', 'required'=> false, 'disabled' => false])

<div class="radio-input">
    <input type="radio" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" required="{{ $required }}" {{ $disabled ? 'disabled' : '' }}>
    <label for="{{ $id }}" class="clue-content cmu-serif text-sm  @if($disabled && $name == 'correct_answer') correct-answer @endif">
        {!! $slot !!}
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
  border: 1px solid #ccc;
  background-color: #f5f5f5; /* Changed from white to light grey */
  border-radius: 5px;
  margin-right: 12px;
  cursor: pointer;
  position: relative;
  transition: all 0.3s ease-in-out;
}

.radio-input label:before {
  content: "";
  display: block;
  position: absolute;
  top: 50%;
  left: 0;
  transform: translate(-50%, -50%);
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #f44336; /* Changed from dark to red */
  border: 2px solid #ccc;
  transition: all 0.3s ease-in-out;
}

.radio-input input[type="radio"]:checked + label:before {
  background-color: #f44336; /* Changed from royalblue to red */
  top: 0;
}

.radio-input input[type="radio"]:checked + label {
  background-color: #9e9e9e; /* Changed from light green to grey */
  color: #212121;
  border-color: #f44336; /* Changed from royalblue to red */
  animation: radio-translate 0.5s ease-in-out;
}

.radio-input input[type="radio"]:disabled + label {
  background-color: rgb(220 38 38); /* Change the background color when disabled */
  color: #fff;
}

.radio-input input[type="radio"]:disabled + label.correct-answer {
  background-color:  rgb(101 163 13); /* Change the background color to green when disabled and the name is correct_answer */
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