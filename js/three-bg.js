import * as THREE from 'https://unpkg.com/three@0.152.2/build/three.module.js';
import { EffectComposer } from 'https://unpkg.com/three@0.152.2/examples/jsm/postprocessing/EffectComposer.js';
import { RenderPass } from 'https://unpkg.com/three@0.152.2/examples/jsm/postprocessing/RenderPass.js';
import { ShaderPass } from 'https://unpkg.com/three@0.152.2/examples/jsm/postprocessing/ShaderPass.js';
import { HorizontalBlurShader } from 'https://unpkg.com/three@0.152.2/examples/jsm/shaders/HorizontalBlurShader.js';
import { VerticalBlurShader } from 'https://unpkg.com/three@0.152.2/examples/jsm/shaders/VerticalBlurShader.js';

const canvas = document.getElementById('three-canvas');
if (!canvas) {
  // nothing to do
  throw new Error('three-canvas not found');
}

const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.5));
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.domElement.style.width = '100%';
renderer.domElement.style.height = '100%';
renderer.domElement.style.display = 'block';
renderer.domElement.style.pointerEvents = 'none';
// ensure canvas stays transparent but allow blending; set clear alpha
renderer.setClearColor(0x000000, 0);
renderer.domElement.style.mixBlendMode = 'normal';
// ensure canvas stacking consistent with container
renderer.domElement.style.zIndex = '-30';

// debug indicator to verify rendering and audio state
let debugEl = null;
try {
  debugEl = document.createElement('div');
  debugEl.id = 'three-debug';
  debugEl.textContent = '3D: init';
  Object.assign(debugEl.style, {
    position: 'fixed',
    right: '12px',
    top: '12px',
    zIndex: 100000,
    background: 'rgba(0,0,0,0.6)',
    color: '#fff',
    padding: '6px 8px',
    borderRadius: '6px',
    fontSize: '12px',
    fontFamily: 'system-ui,sans-serif',
    cursor: 'pointer'
  });
  debugEl.title = 'Click to hide';
  debugEl.addEventListener('click', () => debugEl.style.display = 'none');
  document.body.appendChild(debugEl);
  console.log('three-bg: debug element added');
} catch (e) {
  console.warn('three-bg: could not add debug element', e);
}

// postprocessing composer for lens blur
// postprocessing composer for lens blur
// (will be initialized after scene and camera are created)

const scene = new THREE.Scene();

const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.set(0, 0, 6);

const group = new THREE.Group();
scene.add(group);

// postprocessing composer for lens blur (initialized after scene/camera exist)
let composer = null;
let hBlurPass = null;
let vBlurPass = null;
function initComposer() {
  try {
    composer = new EffectComposer(renderer);
    const renderPass = new RenderPass(scene, camera);
    composer.addPass(renderPass);
    hBlurPass = new ShaderPass(HorizontalBlurShader);
    vBlurPass = new ShaderPass(VerticalBlurShader);
    hBlurPass.uniforms.h.value = 0.0;
    vBlurPass.uniforms.v.value = 0.0;
    composer.addPass(hBlurPass);
    composer.addPass(vBlurPass);
  } catch (err) {
    console.warn('Could not initialize postprocessing composer:', err);
    composer = null;
    hBlurPass = null;
    vBlurPass = null;
  }
}
initComposer();

// Torus-knot background (original toroide)
const torusMaterial = new THREE.MeshStandardMaterial({
  color: 0x8a2be2,
  metalness: 0.2,
  roughness: 0.18,
  emissive: 0x4a0060,
  emissiveIntensity: 0.9,
  transparent: true,
  opacity: 0.95,
});
const torusGeom = new THREE.TorusKnotGeometry(1.4, 0.45, 220, 32);
const torusMesh = new THREE.Mesh(torusGeom, torusMaterial);
torusMesh.position.set(0, 0, -0.6);
group.add(torusMesh);

// subtle wireframe highlight
{
  const geo = new THREE.WireframeGeometry(torusGeom);
  const mat = new THREE.LineBasicMaterial({ color: 0x7be3ff, opacity: 0.28, transparent: true });
  const wire = new THREE.LineSegments(geo, mat);
  wire.position.copy(torusMesh.position);
  group.add(wire);
}

// Particles
const particlesCount = 400;
const positions = new Float32Array(particlesCount * 3);
for (let i = 0; i < particlesCount; i++) {
  positions[i * 3] = (Math.random() - 0.5) * 20;
  positions[i * 3 + 1] = (Math.random() - 0.5) * 10;
  positions[i * 3 + 2] = (Math.random() - 0.5) * 10;
}
const particlesGeom = new THREE.BufferGeometry();
particlesGeom.setAttribute('position', new THREE.BufferAttribute(positions, 3));
const pMat = new THREE.PointsMaterial({ color: 0xd8b3ff, size: 0.06, transparent: true, opacity: 0.7 });
const points = new THREE.Points(particlesGeom, pMat);
scene.add(points);

// Lights
const ambient = new THREE.AmbientLight(0xffffff, 0.35);
scene.add(ambient);
const pLight = new THREE.PointLight(0x9b5cff, 1.0, 30);
pLight.position.set(5, 5, 6);
scene.add(pLight);
const pLight2 = new THREE.PointLight(0x6d3bff, 0.6, 30);
pLight2.position.set(-6, -4, 6);
scene.add(pLight2);

let mouseX = 0;
let mouseY = 0;
let targetX = 0;
let targetY = 0;

function onPointerMove(e) {
  // prefer pointer coords, fallback to touch
  const clientX = e.clientX ?? (e.touches && e.touches[0] && e.touches[0].clientX) ?? 0;
  const clientY = e.clientY ?? (e.touches && e.touches[0] && e.touches[0].clientY) ?? 0;
  const w = window.innerWidth;
  const h = window.innerHeight;
  targetX = (clientX / w - 0.5) * 2;
  targetY = (clientY / h - 0.5) * 2;
}

window.addEventListener('pointermove', onPointerMove, { passive: true });
window.addEventListener('touchmove', onPointerMove, { passive: true });

// Audio reactivity
let audioCtx = null;
let analyser = null;
let dataArray = null; // frecuencia (si es necesario)
let waveArray = null; // dominio temporal (waveform)
let audioEnabled = false;
let audioLevel = 0;

function initAudio(){
  if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return Promise.reject(new Error('getUserMedia not supported'));
  return navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const source = audioCtx.createMediaStreamSource(stream);
    analyser = audioCtx.createAnalyser();
    // usar FFT grande para poder extraer una forma de onda con resolución
    analyser.fftSize = 2048;
    source.connect(analyser);
    dataArray = new Uint8Array(analyser.frequencyBinCount);
    waveArray = new Uint8Array(analyser.fftSize);
    audioEnabled = true;
    return true;
  });
}

// expose starter so UI can call it
window.startThreeAudio = function(){
  return initAudio().catch(err => { console.warn('Audio init failed', err); throw err; });
};

let paused = false;
function onVisibility() {
  paused = document.hidden;
}
document.addEventListener('visibilitychange', onVisibility);

function animate() {
  if (!paused) {
    // lerp mouse influence
    mouseX += (targetX - mouseX) * 0.06;
    mouseY += (targetY - mouseY) * 0.06;

    group.rotation.y = mouseX * 0.35 + performance.now() * 0.00008;
    group.rotation.x = mouseY * 0.12;

    // drift particles
    const pos = particlesGeom.attributes.position.array;
    for (let i = 0; i < particlesCount; i++) {
      const idx = i * 3 + 2;
      pos[idx] += Math.sin((i + performance.now() * 0.0002) * 0.01) * 0.0005;
    }
    particlesGeom.attributes.position.needsUpdate = true;

    // audio-driven adjustments for torus-knot (frequency domain)
    if (audioEnabled && analyser && dataArray) {
      analyser.getByteFrequencyData(dataArray);
      // compute average energy
      let sum = 0;
      for (let i = 0; i < dataArray.length; i++) sum += dataArray[i];
      const avg = (sum / dataArray.length) / 255; // 0..1
      audioLevel = audioLevel + (avg - audioLevel) * 0.12;

      // scale and emissive based on energy
      const scale = 1 + audioLevel * 0.9;
      torusMesh.scale.set(scale, scale, scale);
      torusMaterial.emissiveIntensity = 0.4 + audioLevel * 1.4;
      torusMaterial.needsUpdate = true;

      // rotate faster when audio is strong
      torusMesh.rotation.x += 0.002 + audioLevel * 0.01;
      torusMesh.rotation.y += 0.003 + audioLevel * 0.012;

      // particles respond subtly
      pMat.size = 0.04 + audioLevel * 0.12;
      pMat.opacity = 0.45 + audioLevel * 0.4;
      pMat.needsUpdate = true;
    } else {
      // idle motion for torus
      torusMesh.rotation.x += 0.0006;
      torusMesh.rotation.y += 0.0009;
      const idle = (Math.sin(performance.now() * 0.0009) * 0.5 + 0.5) * 0.03;
      torusMesh.scale.set(1 + idle * 0.2, 1 + idle * 0.2, 1 + idle * 0.2);
    }

    // render via composer to apply blur (fallback to renderer)
    if (composer && typeof composer.render === 'function') {
      composer.render();
    } else {
      renderer.render(scene, camera);
    }
    // update debug indicator
    try {
      if (debugEl) debugEl.textContent = '3D: running' + (audioEnabled ? (' — audio ' + Math.round(audioLevel * 100)) : ' — idle');
    } catch (e) { /* ignore */ }
  }
  requestAnimationFrame(animate);
}

function onResize() {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
  composer.setSize(window.innerWidth, window.innerHeight);
  // set blur relative to size (small values give lens-like softness)
  const blurStrength = Math.min(0.0025 * (window.devicePixelRatio || 1), 0.02);
  if (hBlurPass && vBlurPass) {
    hBlurPass.uniforms.h.value = blurStrength;
    vBlurPass.uniforms.v.value = blurStrength;
  }
}
window.addEventListener('resize', onResize);

// start
animate();
