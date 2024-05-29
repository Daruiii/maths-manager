<div class="overlay">
<div class="cookies">
     <p class="cookieHeading">Nous utilisons des cookies 🍪</p>
     <p class="cookieDescription">Ce site utilise des cookies pour vous offrir une meilleure expérience utilisateur. En utilisant notre site Web, vous acceptez notre utilisation de cookies conformément à notre politique de cookies.</p>
     <div class="buttonContainer">
       <button class="acceptButton">Accepter</button>
     <button class="declineButton">Refuser</button>
     </div>
   </div>
    </div>
   
   <script>
document.querySelector('.acceptButton').addEventListener('click', function() {
  document.cookie = "cookiesAccepted=true; max-age=31536000; path=/"; // Set a cookie that expires in 1 year
  document.querySelector('.overlay').style.display = 'none'; // Hide the overlay
});

document.querySelector('.declineButton').addEventListener('click', function() {
  document.cookie = "cookiesAccepted=false; max-age=31536000; path=/"; // Set a cookie that expires in 1 year
  document.querySelector('.overlay').style.display = 'none'; // Hide the overlay
});
</script>

   <style>
    .overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.cookies {
  width: 300px;
  height: 240px;
  background-color: rgb(255, 255, 255);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px 30px;
  gap: 13px;
  box-shadow: 2px 2px 20px rgba(0, 0, 0, 0.062);
}


.cookieHeading {
  font-size: 1.2em;
  font-weight: 800;
  color: rgb(26, 26, 26);
}

.cookieDescription {
  text-align: center;
  font-size: 0.7em;
  font-weight: 600;
  color: rgb(99, 99, 99);
}

.buttonContainer {
  display: flex;
  gap: 20px;
  flex-direction: row;
}

.acceptButton {
  width: 80px;
  height: 30px;
  background-color: #7b57ff;
  transition-duration: .2s;
  border: none;
  color: rgb(241, 241, 241);
  cursor: pointer;
  font-weight: 600;
  border-radius: 20px;
}

.declineButton {
  width: 80px;
  height: 30px;
  background-color: rgb(218, 218, 218);
  transition-duration: .2s;
  color: rgb(46, 46, 46);
  border: none;
  cursor: pointer;
  font-weight: 600;
  border-radius: 20px;
}

.declineButton:hover {
  background-color: #ebebeb;
  transition-duration: .2s;
}

.acceptButton:hover {
  background-color: #9173ff;
  transition-duration: .2s;
}

@media (max-width: 768px) {
    .cookies {
        width: 250px;
        height: 200px;
    }

    .cookieHeading {
        font-size: 1em;
    }

    .cookieDescription {
        font-size: 0.6em;
    }

    .buttonContainer {
        gap: 15px;
    }

    .acceptButton {
        width: 70px;
        height: 25px;
    }

    .declineButton {
        width: 70px;
        height: 25px;
    }

    .acceptButton:hover {
        background-color: #9173ff;
        transition-duration: .2s;
    }

    .declineButton:hover {
        background-color: #ebebeb;
        transition-duration: .2s;
    }
}
    </style>