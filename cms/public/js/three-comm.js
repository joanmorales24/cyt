import * as THREE from 'https://unpkg.com/three@0.152.2/build/three.module.js';

const canvas = document.getElementById('three-canvas');
if (!canvas) throw new Error('three-canvas not found');

const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.5));
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.domElement.style.width = '100%';
renderer.domElement.style.height = '100%';
renderer.domElement.style.pointerEvents = 'none';

const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.set(0, 0, 12);

const group = new THREE.Group();
scene.add(group);

// simple ambient + point
scene.add(new THREE.AmbientLight(0xffffff, 0.45));
const pl = new THREE.PointLight(0x9fb3ff, 0.9, 60);
pl.position.set(8, 8, 8);
scene.add(pl);

// network parameters
const NODE_COUNT = 36;
const RADIUS = 4.2;
const nodes = [];
const links = [];

// create node mesh template
const sphereGeom = new THREE.SphereGeometry(0.18, 12, 10);
const nodeMat = new THREE.MeshStandardMaterial({ color: 0x6f4bff, emissive: 0x3a0f80, metalness: 0.2, roughness: 0.3 });

for (let i = 0; i < NODE_COUNT; i++) {
  const phi = Math.acos(2 * (i / NODE_COUNT) - 1);
  const theta = Math.PI * (1 + Math.sqrt(5)) * i; // golden spiral
  const x = Math.cos(theta) * Math.sin(phi) * RADIUS;
  const y = Math.sin(theta) * Math.sin(phi) * RADIUS;
  const z = Math.cos(phi) * RADIUS * 0.6;

  const m = new THREE.Mesh(sphereGeom, nodeMat.clone());
  m.position.set(x, y, z);
  m.userData.basePos = new THREE.Vector3(x, y, z);
  m.userData.targetScale = 1;
  m.scale.setScalar(1);
  group.add(m);
  nodes.push(m);
}

// pick random links between nodes and create curved segmented lines
const linkPairs = [];
for (let i = 0; i < NODE_COUNT * 1.6; i++) {
  const a = Math.floor(Math.random() * NODE_COUNT);
  let b = Math.floor(Math.random() * NODE_COUNT);
  if (b === a) b = (a + 1) % NODE_COUNT;
  linkPairs.push([a, b]);
}

// create subdivided curved lines so we can apply a traveling wave
const linksGroup = new THREE.Group();
group.add(linksGroup);
const linkMaterial = new THREE.LineBasicMaterial({ color: 0x9ea3ff, transparent: true, opacity: 0.35 });
const SUBDIV = 36; // points per link (smooth curve)
for (let i = 0; i < linkPairs.length; i++) {
  const [a, b] = linkPairs[i];
  // buffer for SUBDIV points
  const pts = new Float32Array((SUBDIV + 1) * 3);
  const geom = new THREE.BufferGeometry();
  geom.setAttribute('position', new THREE.BufferAttribute(pts, 3));
  const line = new THREE.Line(geom, linkMaterial.clone());
  line.userData = { a, b };
  links.push(line);
  linksGroup.add(line);
}

// audio
let audioCtx = null;
let analyser = null;
let freqData = null;
let audioEnabled = false;

function initAudio() {
  if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return Promise.reject(new Error('getUserMedia not supported'));
  return navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const source = audioCtx.createMediaStreamSource(stream);
    analyser = audioCtx.createAnalyser();
    analyser.fftSize = 1024;
    source.connect(analyser);
    freqData = new Uint8Array(analyser.frequencyBinCount);
    audioEnabled = true;
    return true;
  });
}

window.startThreeCommAudio = function() {
  return initAudio().catch(err => { console.warn('Audio init failed', err); throw err; });
};

// smoothing buffers
const nodeScales = new Float32Array(NODE_COUNT).fill(1);

let paused = false;
function onVisibility() { paused = document.hidden; }
document.addEventListener('visibilitychange', onVisibility);

function animate() {
  if (!paused) {
    // gentle rotation of group
    group.rotation.y += 0.0012;
    group.rotation.x += 0.0006;

    if (audioEnabled && analyser && freqData) {
      analyser.getByteFrequencyData(freqData);
      // map frequency bins to nodes (group bins)
      const binsPerNode = Math.max(1, Math.floor(freqData.length / NODE_COUNT));
      for (let i = 0; i < NODE_COUNT; i++) {
        let sum = 0;
        for (let j = 0; j < binsPerNode; j++) {
          const idx = i * binsPerNode + j;
          sum += freqData[idx] || 0;
        }
        const avg = sum / binsPerNode / 255; // 0..1
        const target = 1 + avg * 1.6; // scale up to 2.6
        nodeScales[i] += (target - nodeScales[i]) * 0.14;
      }

        // update nodes and link brightness + apply wave on each link
        let total = 0;
        for (let i = 0; i < NODE_COUNT; i++) {
          const s = nodeScales[i];
          nodes[i].scale.setScalar(s);
          const em = Math.min(1, (s - 1) * 0.7 + 0.1);
          nodes[i].material.emissiveIntensity = 0.25 + em * 1.2;
          nodes[i].material.emissive = new THREE.Color().setHSL(0.7, 0.8, 0.45);
          total += s;
        }
        // make links pulse with the average energy and animate wave
        const avgAll = total / NODE_COUNT - 1;
        const globalOpacity = 0.25 + Math.min(0.8, avgAll * 0.6);
        const t = performance.now() * 0.0025;
        for (let li = 0; li < links.length; li++) {
          const line = links[li];
          line.material.opacity = globalOpacity;
          const { a, b } = line.userData;
          const pa = nodes[a].position;
          const pb = nodes[b].position;
          const positions = line.geometry.attributes.position.array;
          // direction and perpendicular vector for offset
          const dir = new THREE.Vector3().subVectors(pb, pa);
          const up = new THREE.Vector3(0, 1, 0);
          const normal = new THREE.Vector3().crossVectors(dir, up).normalize();
          if (normal.length() < 0.0001) normal.set(1, 0, 0);
          for (let s = 0; s <= SUBDIV; s++) {
            const u = s / SUBDIV;
            // base point along the straight segment
            const base = new THREE.Vector3().lerpVectors(pa, pb, u);
            // wave amplitude stronger near middle
            const envelope = Math.sin(Math.PI * u);
            // sample frequency-dependent amplitude from nodes near endpoints
            const amp = avgAll * 1.4 + 0.03;
            const wave = Math.sin((u * 6.0) - t * 2.0 + li * 0.6) * amp * envelope;
            const offset = normal.clone().multiplyScalar(wave);
            const final = base.add(offset);
            const idx = s * 3;
            positions[idx] = final.x;
            positions[idx + 1] = final.y;
            positions[idx + 2] = final.z;
          }
          line.geometry.attributes.position.needsUpdate = true;
        }
    } else {
      // idle subtle breathing
      for (let i = 0; i < NODE_COUNT; i++) {
        const idle = 1 + Math.sin(performance.now() * 0.0006 + i) * 0.02;
        nodes[i].scale.setScalar(idle);
      }
      for (let li = 0; li < links.length; li++) {
        links[li].material.opacity = 0.28;
        links[li].material.needsUpdate = true;
      }
    }

    renderer.render(scene, camera);
  }
  requestAnimationFrame(animate);
}

window.addEventListener('resize', () => {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
});

animate();
