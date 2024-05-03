<button class="pause-btn" id="pauseButton">
  <svg class="svg-icon" fill="none" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><g stroke="#00FF00" stroke-linecap="round" stroke-width="2"><rect height="14" rx="1.5" width="3" x="15" y="5"></rect><rect height="14" rx="1.5" width="3" x="6" y="5"></rect></g></svg>
  <span class="pause-label text-sm">Pause</span>
</button>

<style>
  .pause-btn {
  width: 100px;
  height: 40px;
  padding: 18px 22px 18px 20px;
  gap: 1px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: rgba(0, 255, 0, 0.282);
  border-radius: 16px;
  border: none;
  cursor: pointer;
}

.pause-label {
  font-family: sans-serif;
  height: 23px;
  line-height: 24px;
  color: #00FF00;
}

.pause-btn:hover {
  background: rgba(0, 255, 0, 0.224);
}

.pause-btn:hover .svg-icon {
  animation: pulse 0.7s linear infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.13);
  }

  100% {
    transform: scale(1);
  }
}
</style>