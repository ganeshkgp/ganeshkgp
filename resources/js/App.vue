<template>
  <div id="app">
    <router-view />
    <AudioControls />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import AudioControls from './components/AudioControls.vue'
import audioManager from './utils/AudioManager.js'

onMounted(() => {
  // Initialize any global app logic here
  console.log('3D Portfolio App Initialized')

  // Add global audio effects to router navigation
  const originalPushState = history.pushState
  history.pushState = function(state, title, url) {
    originalPushState.call(this, state, title, url)
    audioManager.playSound('hover')
  }

  // Add keyboard shortcuts
  const handleKeyPress = (event) => {
    // Ctrl/Cmd + M to toggle music
    if ((event.ctrlKey || event.metaKey) && event.key === 'm') {
      event.preventDefault()
      // Toggle music (we'll need to access the audio controls)
    }

    // Ctrl/Cmd + P to play/pause
    if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
      event.preventDefault()
      audioManager.playSound('click')
    }
  }

  document.addEventListener('keydown', handleKeyPress)
})
</script>

<style>
#app {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  width: 100%;
  height: 100vh;
  overflow: hidden;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: #0a0a0a;
  color: #ffffff;
  overflow-x: hidden;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, #00dddd, #ddddff);
}
</style>