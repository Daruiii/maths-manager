<a  href="#top" id="back-to-top" class="button-back-top fade-in">
    <svg class="svgIcon" viewBox="0 0 384 512">
      <path
        d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"
      ></path>
    </svg>
</a>

  <style>
    .button-back-top {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: rgb(20, 20, 20);
  border: none;
  font-weight: 600;
  display: none;
  align-items: center;
  justify-content: center;
  box-shadow: 0px 0px 0px 4px rgba(180, 160, 255, 0.253);
  cursor: pointer;
  transition-duration: 0.3s;
  overflow: hidden;
  position: fixed; /* Fixe la position */
        bottom: 50px; /* Positionne en bas */
        right: 50px; /* Positionne à droite */
}

.svgIcon {
  width: 12px;
  transition-duration: 0.3s;
}

.svgIcon path {
  fill: white;
}

.button-back-top:hover {
  width: 140px;
  border-radius: 50px;
  transition-duration: 0.3s;
  background-color: rgb(181, 160, 255);
  align-items: center;
}

.button-back-top:hover .svgIcon {
  /* width: 20px; */
  transition-duration: 0.3s;
  transform: translateY(-200%);
}

.button-back-top::before {
  position: absolute;
  bottom: -20px;
  content: "Retour en haut";
  color: white;
  /* transition-duration: .3s; */
  font-size: 0px;
}

.button-back-top:hover::before {
  font-size: 13px;
  opacity: 1;
  bottom: unset;
  /* transform: translateY(-30px); */
  transition-duration: 0.3s;
}

@media (max-width: 768px) {
  .button-back-top {
    bottom: 20px;
    right: 20px;
  }
}
</style>