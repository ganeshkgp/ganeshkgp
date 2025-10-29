class AudioManager {
  constructor() {
    this.audioContext = null;
    this.sounds = new Map();
    this.backgroundMusic = null;
    this.isInitialized = false;
    this.volume = 0.5;
    this.isMuted = false;
    this.isEnabled = true; // Start enabled by default

    // Check if Web Audio API is supported
    this.checkAudioSupport();
  }

  checkAudioSupport() {
    if (typeof window !== 'undefined' && 'AudioContext' in window) {
      console.log('Web Audio API is supported');
    } else {
      console.warn('Web Audio API is not supported in this browser');
      this.isEnabled = false;
    }
  }

  async init() {
    if (!this.isEnabled || this.isInitialized) return;

    try {
      // Create audio context on first user interaction
      this.audioContext = new (window.AudioContext || window.webkitAudioContext)();

      // Resume context if it was suspended
      if (this.audioContext.state === 'suspended') {
        await this.audioContext.resume();
      }

      // Create master gain node for volume control
      this.masterGainNode = this.audioContext.createGain();
      this.masterGainNode.connect(this.audioContext.destination);
      this.masterGainNode.gain.value = this.volume;

      // Create sounds
      await this.createSounds();

      this.isInitialized = true;
      console.log('Audio manager initialized');
    } catch (error) {
      console.error('Failed to initialize audio manager:', error);
      this.isEnabled = false;
    }
  }

  async createSounds() {
    // Create sound effects using Web Audio API oscillators
    // This avoids loading external audio files

    // Hover sound
    this.sounds.set('hover', this.createTone(800, 0.1, 'sine'));

    // Click sound
    this.sounds.set('click', this.createTone(600, 0.1, 'square'));

    // Success sound
    this.sounds.set('success', this.createMelody([523, 659, 784], 0.2));

    // Error sound
    this.sounds.set('error', this.createTone(200, 0.2, 'sawtooth'));

    // Notification sound
    this.sounds.set('notification', this.createMelody([523, 659, 784, 1047], 0.3));

    // Background music (simple ambient melody)
    this.backgroundMusic = this.createAmbientMusic();
  }

  createTone(frequency, duration, type = 'sine') {
    return () => {
      if (!this.isInitialized || this.isMuted) return;

      const oscillator = this.audioContext.createOscillator();
      const gainNode = this.audioContext.createGain();

      oscillator.type = type;
      oscillator.frequency.setValueAtTime(frequency, this.audioContext.currentTime);

      gainNode.gain.setValueAtTime(0.1, this.audioContext.currentTime);
      gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + duration);

      oscillator.connect(gainNode);
      gainNode.connect(this.masterGainNode);

      oscillator.start(this.audioContext.currentTime);
      oscillator.stop(this.audioContext.currentTime + duration);
    };
  }

  createMelody(frequencies, duration) {
    return () => {
      if (!this.isInitialized || this.isMuted) return;

      frequencies.forEach((freq, index) => {
        const startTime = this.audioContext.currentTime + (index * 0.1);
        const noteDuration = duration / frequencies.length;

        const oscillator = this.audioContext.createOscillator();
        const gainNode = this.audioContext.createGain();

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(freq, startTime);

        gainNode.gain.setValueAtTime(0.15, startTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, startTime + noteDuration);

        oscillator.connect(gainNode);
        gainNode.connect(this.masterGainNode);

        oscillator.start(startTime);
        oscillator.stop(startTime + noteDuration);
      });
    };
  }

  createAmbientMusic() {
    // Create a simple ambient background music loop
    const createAmbientLoop = () => {
      if (!this.isInitialized || this.isMuted) return null;

      const now = this.audioContext.currentTime;

      // Create multiple oscillators for ambient effect
      const oscillators = [];
      const gainNodes = [];

      // Low frequency drone
      const osc1 = this.audioContext.createOscillator();
      const gain1 = this.audioContext.createGain();
      osc1.type = 'sine';
      osc1.frequency.setValueAtTime(55, now); // A1
      gain1.gain.setValueAtTime(0.05, now);
      osc1.connect(gain1);
      gain1.connect(this.masterGainNode);
      oscillators.push(osc1);
      gainNodes.push(gain1);

      // Mid frequency ambient
      const osc2 = this.audioContext.createOscillator();
      const gain2 = this.audioContext.createGain();
      osc2.type = 'triangle';
      osc2.frequency.setValueAtTime(110, now); // A2
      gain2.gain.setValueAtTime(0.03, now);
      osc2.connect(gain2);
      gain2.connect(this.masterGainNode);
      oscillators.push(osc2);
      gainNodes.push(gain2);

      // High frequency sparkle
      const osc3 = this.audioContext.createOscillator();
      const gain3 = this.audioContext.createGain();
      osc3.type = 'sine';
      osc3.frequency.setValueAtTime(440, now); // A4
      gain3.gain.setValueAtTime(0.01, now);
      osc3.connect(gain3);
      gain3.connect(this.masterGainNode);
      oscillators.push(osc3);
      gainNodes.push(gain3);

      // Add some modulation
      const lfo = this.audioContext.createOscillator();
      const lfoGain = this.audioContext.createGain();
      lfo.frequency.setValueAtTime(0.5, now); // Slow modulation
      lfoGain.gain.setValueAtTime(20, now); // Modulation depth
      lfo.connect(lfoGain);
      lfoGain.connect(osc2.frequency);

      // Start all oscillators
      oscillators.forEach(osc => osc.start(now));
      lfo.start(now);

      return {
        stop: () => {
          const stopTime = this.audioContext.currentTime + 1;
          oscillators.forEach(osc => osc.stop(stopTime));
          lfo.stop(stopTime);

          // Fade out
          gainNodes.forEach(gain => {
            gain.gain.exponentialRampToValueAtTime(0.001, stopTime);
          });
        },
        isPlaying: true
      };
    };

    return createAmbientLoop();
  }

  // Public methods
  playSound(soundName) {
    if (!this.isEnabled) return;

    // Initialize on first user interaction if not already done
    if (!this.isInitialized) {
      this.init().then(() => {
        const sound = this.sounds.get(soundName);
        if (sound) sound();
      });
    } else {
      const sound = this.sounds.get(soundName);
      if (sound) sound();
    }
  }

  playBackgroundMusic() {
    if (!this.isEnabled || this.isMuted) return;

    if (!this.isInitialized) {
      this.init().then(() => {
        this.backgroundMusic = this.createAmbientMusic();
      });
    } else {
      if (this.backgroundMusic) {
        this.backgroundMusic.stop();
      }
      this.backgroundMusic = this.createAmbientMusic();
    }
  }

  stopBackgroundMusic() {
    if (this.backgroundMusic && this.backgroundMusic.isPlaying) {
      this.backgroundMusic.stop();
      this.backgroundMusic = null;
    }
  }

  setVolume(volume) {
    this.volume = Math.max(0, Math.min(1, volume));
    if (this.masterGainNode) {
      this.masterGainNode.gain.value = this.volume;
    }
  }

  getVolume() {
    return this.volume;
  }

  mute() {
    this.isMuted = true;
    if (this.masterGainNode) {
      this.masterGainNode.gain.value = 0;
    }
    this.stopBackgroundMusic();
  }

  unmute() {
    this.isMuted = false;
    if (this.masterGainNode) {
      this.masterGainNode.gain.value = this.volume;
    }
  }

  toggleMute() {
    if (this.isMuted) {
      this.unmute();
    } else {
      this.mute();
    }
    return !this.isMuted;
  }

  // Enable/disable audio entirely
  enable() {
    this.isEnabled = true;
  }

  disable() {
    this.isEnabled = false;
    this.stopBackgroundMusic();
  }

  // Check if audio is supported
  isSupported() {
    return this.isEnabled;
  }
}

// Export singleton instance
export const audioManager = new AudioManager();
export default audioManager;