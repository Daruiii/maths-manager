@props(['href'])

  <a href="{{ $href }}" class="quiz-button text-xs md:text-sm">
    {{ $slot }}
  </a>

<style>
  .quiz-button {
  padding: 8.5px 20px;
  border: 0;
  border-radius: 100px;
  /* équivalent de red-100 */
  background-color: rgb(239 68 68);
  color: #ffffff;
  font-weight: Bold;
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
}

.quiz-button:hover {
  background-color: rgb(239 68 68);
  box-shadow: 0 0 20px #ffec8b50;
  transform: scale(1.1);
}

.quiz-button:active {
  background-color: rgb(239 68 68);
  transition: all 0.25s;
  -webkit-transition: all 0.25s;
  box-shadow: none;
  transform: scale(0.98);
}
</style>