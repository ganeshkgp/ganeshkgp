<template>
  <div class="audio-controls" v-if="audioManager.isSupported()">
    <button
      @click="toggleBackgroundMusic"
      class="audio-btn"
      :class="{ active: isMusicPlaying }"
      :title="isMusicPlaying ? 'Stop Music' : 'Play Music'"
    >
      <span v-if="isMusicPlaying">🔇</span>
      <span v-else>🎵</span>
    </button>

    <button
      @click="toggleMute"
      class="audio-btn"
      :class="{ active: isMuted }"
      :title="isMuted ? 'Unmute' : 'Mute'"
    >
      <span v-if="isMuted">🔈</span>
      <span v-else>🔊</span>
    </button>

    <div class="volume-control" v-if="showVolumeSlider">
      <input
        type="range"
        min="0"
        max="100"
        :value="volume * 100"
        @input="updateVolume"
        class="volume-slider"
      />
      <span class="volume-indicator">{{ Math.round(volume * 100) }}%</span>
    </div>

    <button
      @click="showVolumeSlider = !showVolumeSlider"
      class="audio-btn volume-toggle"
      title="Volume"
    >
      ⚡
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import audioManager from '../utils/AudioManager.js'

const isMusicPlaying = ref(false)
const isMuted = ref(false)
const volume = ref(audioManager.getVolume())
const showVolumeSlider = ref(false)

let musicCheckInterval = null

const toggleBackgroundMusic = () => {
  audioManager.playSound('click');

  if (isMusicPlaying.value) {
    audioManager.stopBackgroundMusic();
    isMusicPlaying.value = false;
  } else {
    audioManager.playBackgroundMusic();
    isMusicPlaying.value = true;
  }
};

const toggleMute = () => {
  audioManager.playSound('click');
  const newMuteState = audioManager.toggleMute();
  isMuted.value = newMuteState;
};

const updateVolume = (event) => {
  const newVolume = event.target.value / 100;
  audioManager.setVolume(newVolume);
  volume.value = newVolume;

  // If unmuting by increasing volume from 0
  if (newVolume > 0 && isMuted.value) {
    audioManager.unmute();
    isMuted.value = false;
  }
};

const checkMusicStatus = () => {
  if (audioManager.backgroundMusic) {
    isMusicPlaying.value = audioManager.backgroundMusic.isPlaying;
  }
};

onMounted(() => {
  // Initialize audio on first user interaction
  const initAudio = () => {
    audioManager.init();
    document.removeEventListener('click', initAudio);
    document.removeEventListener('touchstart', initAudio);
    document.removeEventListener('keydown', initAudio);
  };

  document.addEventListener('click', initAudio);
  document.addEventListener('touchstart', initAudio);
  document.addEventListener('keydown', initAudio);

  // Check music status periodically
  musicCheckInterval = setInterval(checkMusicStatus, 1000);

  // Handle visibility change to pause music when tab is hidden
  const handleVisibilityChange = () => {
    if (document.hidden) {
      // Tab is hidden, we might want to pause music
      audioManager.playSound('hover');
    } else {
      // Tab is visible
      audioManager.playSound('hover');
    }
  };

  document.addEventListener('visibilitychange', handleVisibilityChange);

  onBeforeUnmount(() => {
    clearInterval(musicCheckInterval);
    document.removeEventListener('visibilitychange', handleVisibilityChange);
  });
});
</script>

<style scoped>
.audio-controls {
  position: fixed;
  bottom: 20px;
  right: 20px;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(10, 10, 10, 0.9);
  backdrop-filter: blur(10px);
  border: 1px solid var(--color-border-primary);
  border-radius: 25px;
  padding: 0.5rem;
  z-index: 1000;
  transition: var(--transition-normal);
}

.audio-controls:hover {
  border-color: var(--color-border-accent);
  box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
}

.audio-btn {
  background: transparent;
  border: none;
  color: var(--color-text-secondary);
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  transition: var(--transition-normal);
  position: relative;
}

.audio-btn:hover {
  color: var(--color-primary);
  background: var(--color-bg-hover);
  transform: scale(1.1);
}

.audio-btn.active {
  color: var(--color-primary);
  background: var(--color-bg-hover);
  box-shadow: 0 0 15px rgba(0, 255, 255, 0.4);
}

.volume-control {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0 0.5rem;
  border-left: 1px solid var(--color-border-primary);
  margin-left: 0.5rem;
}

.volume-slider {
  width: 80px;
  height: 4px;
  border-radius: 2px;
  background: var(--color-bg-card);
  outline: none;
  cursor: pointer;
  -webkit-appearance: none;
}

.volume-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: var(--gradient-primary);
  cursor: pointer;
  transition: var(--transition-normal);
}

.volume-slider::-webkit-slider-thumb:hover {
  transform: scale(1.2);
  box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
}

.volume-slider::-moz-range-thumb {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: var(--gradient-primary);
  cursor: pointer;
  border: none;
  transition: var(--transition-normal);
}

.volume-slider::-moz-range-thumb:hover {
  transform: scale(1.2);
  box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
}

.volume-indicator {
  color: var(--color-text-muted);
  font-size: 0.8rem;
  min-width: 35px;
  text-align: center;
}

.volume-toggle {
  background: var(--gradient-primary);
  color: var(--color-bg-primary);
}

.volume-toggle:hover {
  background: var(--gradient-accent);
  transform: scale(1.1) rotate(15deg);
}

/* Mobile Optimization */
@media (max-width: 768px) {
  .audio-controls {
    bottom: 10px;
    right: 10px;
    padding: 0.3rem;
  }

  .audio-btn {
    width: 35px;
    height: 35px;
    font-size: 1rem;
  }

  .volume-slider {
    width: 60px;
  }

  .volume-indicator {
    font-size: 0.7rem;
    min-width: 30px;
  }
}

/* Touch Device Optimization */
@media (hover: none) and (pointer: coarse) {
  .audio-btn {
    min-width: 44px;
    min-height: 44px;
  }

  .audio-btn:hover {
    transform: none;
  }
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
  .audio-btn:hover,
  .volume-toggle:hover {
    transform: none;
  }
}
</style>