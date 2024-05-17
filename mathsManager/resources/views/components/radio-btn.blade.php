@props(['id', 'name', 'value', 'required'])

<div class="radio-input">
    <input type="radio" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" required="{{ $required }}" />
    <label for="{{ $id }}" class="clue-content cmu-serif">
        {!! $slot !!}
    </label>
  </div>

  <style>
.radio-input {
  display: flex;
  flex-direction: row;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  font-size: 16px;
  font-weight: 600;
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