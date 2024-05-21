@props(['href'])

<a class="see-btn text-xs flex justify-start items-center w-auto" href="{{ $href }}">
    <span class="hover-underline-animation">{{ $slot }}</span>
    <svg
      id="arrow-horizontal"
      xmlns="http://www.w3.org/2000/svg"
      width="45"
      height="7"
      viewBox="0 0 46 16"
    >
      <path
        id="Path_10"
        data-name="Path 10"
        d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z"
        transform="translate(30)"
      ></path>
    </svg>
</a>
  <style>
    .see-btn {
  border: none;
  background: none;
  cursor: pointer;
}

.see-btn span {
    padding-bottom: 5px;
}

.see-btn svg {
  transform: translateX(-8px);
  transition: all 0.3s ease;
}

.see-btn:hover svg {
  transform: translateX(0);
}

.see-btn:active svg {
  transform: scale(0.9);
}

.hover-underline-animation {
  position: relative;
  color: black;
  padding-bottom: 20px;
}

.hover-underline-animation:after {
  content: "";
  position: absolute;
  width: 100%;
  transform: scaleX(0);
  height: 2px;
  bottom: 0;
  left: 0;
  background-color: #000000;
  transform-origin: bottom right;
  transition: transform 0.25s ease-out;
}

.see-btn:hover .hover-underline-animation:after {
  transform: scaleX(1);
  transform-origin: bottom left;
}

    </style>