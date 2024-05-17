@props(['href'])

<a class="cta" href="{{ $href }}">
    <span> {{ $slot }} </span>
    <svg width="10px" height="7px" viewBox="0 0 13 10">
      <path d="M1,5 L11,5"></path>
      <polyline points="8 1 12 5 8 9"></polyline>
    </svg>
</a>

  <style>
    .cta {
  position: relative;
  margin: auto;
  padding: 4px 8px; /* Réduit davantage le padding */
  transition: all 0.2s ease;
  border: none;
  background: none;
  cursor: pointer;
  display: flex; /* Ajouté */
  justify-content: center; /* Ajouté */
  align-items: center; /* Ajouté */
}

.cta:before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  display: block;
  border-radius: 50px;
  background: #b1dae7;
  width: 17%; /* Réduit davantage la largeur */
  height: 100%; /* Réduit davantage la hauteur */
  transition: all 0.3s ease;
  z-index: -1; /* Ajouté */
}

.cta span {
  position: relative;
  font-family: "Ubuntu", sans-serif;
  font-weight: 700;
  letter-spacing: 0.05em;
  color: #234567;
}

.cta svg {
  position: relative;
  top: 0;
  margin-left: 10px;
  fill: none;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke: #234567;
  stroke-width: 2;
  transform: translateX(-5px);
  transition: all 0.3s ease;
}

.cta:hover:before {
  width: 100%;
  background: #b1dae7;
}

.cta:hover svg {
  transform: translateX(0);
}

.cta:active {
  transform: scale(0.95);
}

</style>