@props(['href'])

  <a href="{{ $href }}" class="recap-button text-xs md:text-sm">
    {{ $slot }}
  </a>

<style>
  .recap-button {
  padding: 8.5px 20px;
  border: 0;
  border-radius: 100px;
  background-color: #4bc295;
  color: #ffffff;
  font-weight: Bold;
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
}

.recap-button:hover {
  background-color: #4bc295;
  box-shadow: 0 0 20px #6fc5ff50;
  transform: scale(1.1);
}

.recap-button:active {
  background-color: #4bc295;
  transition: all 0.25s;
  -webkit-transition: all 0.25s;
  box-shadow: none;
  transform: scale(0.98);
}
</style>