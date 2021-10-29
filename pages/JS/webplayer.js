import lottieWeb from 'https://cdn.skypack.dev/lottie-web';

const playIconContainer = document.getElementById('playpause');
      const audioPlayerContainer = document.getElementById('webplayer-container');
      const muteIcon = document.getElementById('mute-icon');
      const seekSlider = document.getElementById('music-slider');
      const volumeSlider = document.getElementById('volume-slider');
      const currentTimeContainer = document.getElementById('current-time');
      let state = 'play';
      let muted = 'unmute';

const animation = lottieWeb.loadAnimation({
    container: playIconContainer,
    path: 'https://maxst.icons8.com/vue-static/landings/animated-icons/icons/pause/pause.json',
    renderer: 'svg',
    loop: false,
    autoplay: false,
    name: "Play Animation",
});

const muteAnimation = lottieWeb.loadAnimation({
    container: muteIcon,
    path: 'https://maxst.icons8.com/vue-static/landings/animated-icons/icons/mute/mute.json',
    renderer: 'svg',
    loop: false,
    autoplay: false,
    name: "Mute Animation",
});

animation.goToAndStop(14, true);


playIconContainer.addEventListener('click', () => {
    if(state === 'play') {
        audio.play();
        animation.playSegments([14, 27], true);
        requestAnimationFrame(whilePlaying);
        state = 'pause';
    } else {
        audio.pause();
        animation.playSegments([0, 14], true);
        cancelAnimationFrame(rAF);
        state = 'play';
    }
});

muteIcon.addEventListener('click', () => {
    if(muted === 'unmute') {
        muteAnimation.playSegments([0, 15], true);
        muted = 'mute';
        audio.muted = true;
    } else {
        muteAnimation.playSegments([15, 25], true);
        muted = 'unmute';
        audio.muted = false;
    }
});



const showRangeProgress = (rangeInput) => {
    if(rangeInput === seekSlider) audioPlayerContainer.style.setProperty('--seek-before-width', rangeInput.value / rangeInput.max * 100 + '%');
    else audioPlayerContainer.style.setProperty('--volume-before-width', rangeInput.value / rangeInput.max * 100 + '%');
}

seekSlider.addEventListener('input', (e) => {
    showRangeProgress(e.target);
});
volumeSlider.addEventListener('input', (e) => {
    showRangeProgress(e.target);
});



//----------------------------------------------------------------------
//Audio fájl metaadatainak lekérdezése, aztán megjelenítése a lejátszón

const audio = document.querySelector('audio');
const durationContainer = document.getElementById('duration');

const calculateTime = (secs) => {
  const minutes = Math.floor(secs / 60);
  const seconds = Math.floor(secs % 60);
  const returnedSeconds = seconds < 10 ? `0${seconds}` : `${seconds}`;
  return `${minutes}:${returnedSeconds}`;
}

const displayDuration = () => {
  durationContainer.textContent = calculateTime(audio.duration);
}

//-----------------------------------------------------------------------
//SLIDER BEÁLLÍTÁS

const setSliderMax = () => {
  seekSlider.max = Math.floor(audio.duration);
}


var framepage = document.getElementById('framepage');
$('html').on('DOMSubtreeModified', '#framepage', function(){
    const audiolist = document.getElementById('list');
    const sourceControl = document.getElementById('sourceControl');

    if(audiolist && sourceControl){
        audiolist.onclick = function(e){
            e.preventDefault();
    
            var elm = e.target;
            sourceControl.src = elm.getAttribute('data-value');
            
            audio.load();
            audio.addEventListener("loadeddata", function () {
                const bufferedAmount = audio.buffered.end(audio.buffered.length - 1);
                const seekableAmount = audio.seekable.end(audio.seekable.length - 1);
    
                const displayBufferedAmount = () => {
                    const bufferedAmount = Math.floor(audio.buffered.end(audio.buffered.length - 1));
                    audioPlayerContainer.style.setProperty('--buffered-width', `${(bufferedAmount / seekSlider.max) * 100}%`);
                }
            
                if (audio.readyState > 0) {
                    displayDuration();
                    setSliderMax();
                    displayBufferedAmount();
                } else {
                    
                    audio.addEventListener('loadedmetadata', () => {
                        displayDuration();
                        setSliderMax();
                        displayBufferedAmount();
                    });
                }
            
                audio.play();
                animation.playSegments([14, 27], true);
                requestAnimationFrame(whilePlaying);
                state = 'pause';
                audio.addEventListener('progress', displayBufferedAmount);
            });
        }
    }
    
});
  

//SOURCE CONTROL
$('#framepage').on('change', function() {
    
});


// RAF

let rAF = null;

const whilePlaying = () => {
  seekSlider.value = Math.floor(audio.currentTime);
  currentTimeContainer.textContent = calculateTime(seekSlider.value);
  audioPlayerContainer.style.setProperty('--seek-before-width', `${seekSlider.value / seekSlider.max * 100}%`);
  rAF = requestAnimationFrame(whilePlaying);
}


//----------------------------------------------------------------------
//PLAY/PAUSE

seekSlider.addEventListener('change', () => {
    audio.currentTime = seekSlider.value;
    if(!audio.paused) {
        requestAnimationFrame(whilePlaying);
    }
});

seekSlider.addEventListener('input', () => {
    currentTimeContainer.textContent = calculateTime(seekSlider.value);
    if(!audio.paused) {
      cancelAnimationFrame(rAF);
    }
});


audio.addEventListener('timeupdate', () => {
    seekSlider.value = Math.floor(audio.currentTime);
});

//VOLUME

volumeSlider.addEventListener('input', (e) => {
  const value = e.target.value;
  audio.volume = value / 100;
});