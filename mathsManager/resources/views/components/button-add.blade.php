@props(['href'])

  <a href="{{ $href }}" class="add-button">
    <div class="sign text-xl ">+</div>
    <div class="text text-xs"> {{ $slot }}</div>
  </a>

<style>
 .add-button {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  margin: 5px;
  width: 30px;
  height: 30px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition-duration: .3s;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
  background-color: #318CE7;
}

/* plus sign */
.sign {
  width: 100%;
  color: white;
  transition-duration: .3s;
  display: flex;
  align-items: center;
  justify-content: center;
}
/* text */
.text {
  position: absolute;
  right: 0%;
  width: 0%;
  opacity: 0;
  color: white;
  font-weight: 500;
  transition-duration: .3s;
}
/* hover effect on button width */
.add-button:hover {
  width: 130px;
  border-radius: 5px;
  transition-duration: .3s;
}

.add-button:hover .sign {
  width: 30%;
  transition-duration: .3s;
  padding-left: 0.75rem;
}
/* hover effect button's text */
.add-button:hover .text {
  opacity: 1;
  width: 70%;
  transition-duration: .3s;
  padding-right: 1rem;
}
/* button click effect*/
.add-button:active {
  transform: translate(2px ,2px);
}
</style>