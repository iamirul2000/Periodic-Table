<!DOCTYPE html>
<html lang="en">

@if(Auth::check())

<head>
    <title>Periodic Table Assignment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link type="text/css" rel="stylesheet" href="main.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        overflow: hidden;
    }

    #info {
        position: absolute;
        top: 10px;
        width: 100%;
        text-align: center;
        z-index: 1;
        color: #fff;
        font-size: 16px;
    }

    #container {
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        z-index: 0;
    }

    #menu {
        position: absolute;
        bottom: 20px;
        width: 100%;
        text-align: center;
        z-index: 1;
    }

    #menu button {
        padding: 10px 20px;
        margin: 5px;
        font-size: 14px;
        cursor: pointer;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
    }

    #menu button:hover {
        background-color: #0056b3;
    }

    .element {
        width: 120px;
        height: 130px;
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid #ddd;
        border-radius: 10px;
        text-align: center;
        color: #333;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.5s ease;
    }

    .element img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin: 5px 0;
    }

    .name {
        font-size: 12px;
        font-weight: bold;
        color: #FFFFFF;
    }

    .details {
        font-size: 10px;
        color: #FFFFFF;
        margin: 5px 0;
    }
    </style>
</head>

<body>
    <div id="container"></div>
    <div id="menu">
        <button id="table">TABLE</button>
        <button id="sphere">SPHERE</button>
        <button id="helix">HELIX</button>
        <button id="grid">GRID</button>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <script type="importmap">
        {
				"imports": {
					"three": "/js/three.module.js",
					"three/addons/": "./jsm/"
				}
			}
	</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
    <!-- <script src = "https://cdn.jsdelivr.net/npm/three@0.134.0/examples/jsm/renderers/CSS3DRenderer.js" ></script> -->
    <!-- <script src=".../three/examples/jsm/renderers/CSS3DRenderer.js"></script> -->
    <!-- <script src ="/node_modules/three/examples/jsm/renderers/test.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/three@0.134.0/build/three.module.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script> -->

    <script type="module">
    // Import necessary modules (you already have this)
    import * as THREE from 'three';
    import TWEEN from '/js/jsm/libs/tween.module.js';
    import {
        TrackballControls
    } from '/js/jsm/controls/TrackballControls.js';
    import {
        CSS3DRenderer,
        CSS3DObject
    } from '/js/jsm/renderers/CSS3DRenderer.js';

    // Inside the axios request callback, after initializing the scene and camera:
    axios.get('/api/sheet-data').then(response => {
        console.log('Fetched data:', response.data);

        const [header, ...data] = response.data.values;

        // Initialize Three.js scene and camera
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new CSS3DRenderer();

        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('container').appendChild(renderer.domElement);

        // Initialize TrackballControls (to make the scene interactive)
        const controls = new TrackballControls(camera, renderer.domElement);
        controls.rotateSpeed = 5.0;
        controls.zoomSpeed = 1.2;
        controls.panSpeed = 0.8;

        // Create the tile objects and position them
        function createTile(elementData) {
            const [name, photo, age, country, interest, netWorth] = elementData;
            const element = document.createElement('div');
            element.className = 'element';

            // Set background color based on net worth
            let backgroundColor = '';
            let borderColor = '';
            if (parseFloat(netWorth.replace(/[^0-9.-]+/g, "")) < 100000) {
                backgroundColor = '#EF3022'; // Less than $100K
                borderColor = '#fb0303'
            } else if (parseFloat(netWorth.replace(/[^0-9.-]+/g, "")) < 200000) {
                backgroundColor = '#FDCA35'; // Between $100K and $200K
                borderColor = '#FDCA35'
            } else {
                backgroundColor = '#3A9F48'; // More than $200K
                borderColor = '#3A9F48'
            }

            element.style.backgroundColor = backgroundColor;
            element.style.borderColor = borderColor;

            element.innerHTML = `
            <img src="${photo}" alt="${name}">
            <div class="name">${name}</div>
            <div class="details">${interest}, ${country}</div>
            <div class="details">Net Worth: ${netWorth}</div>
            `;

            const object = new CSS3DObject(element);
            object.position.set(Math.random() * 400 - 200, Math.random() * 400 - 200, Math.random() * 400 -
                200);
            scene.add(object);

            return object;
        }

        // Create tiles for each element
        const objects = data.map(createTile);

        // Add layout buttons event listeners
        document.getElementById('table').addEventListener('click', () => arrangeTable(objects));
        document.getElementById('sphere').addEventListener('click', () => arrangeSphere(objects));
        document.getElementById('helix').addEventListener('click', () => arrangeHelix(objects));
        document.getElementById('grid').addEventListener('click', () => arrangeGrid(objects));

        camera.position.z = 1000;

        function animate() {
            requestAnimationFrame(animate);

            // Update the controls to reflect any user interaction
            controls.update();

            // Render the scene
            renderer.render(scene, camera);
        }

        animate();

        // Function to handle window resizing
        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }

        // Listen for window resize events
        window.addEventListener('resize', onWindowResize);

    }).catch(error => {
        console.error('Error fetching sheet data:', error);
    });

    function animateTransition(objects, newPositions, duration = 1000) {
        const startTime = performance.now();

        function update() {
            const elapsedTime = performance.now() - startTime;
            const progress = Math.min(elapsedTime / duration, 1);

            objects.forEach((object, index) => {
                // Smoothly interpolate between the current position and the new position
                object.position.x += (newPositions[index].x - object.position.x) * progress;
                object.position.y += (newPositions[index].y - object.position.y) * progress;
                object.position.z += (newPositions[index].z - object.position.z) * progress;
            });

            // Continue updating if the transition is not complete
            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        update();
    }

    // Layout functions (you can tweak these for better visual results)
    function arrangeTable(objects) {
        const rows = 10;
        const cols = 20; // 20x10 layout
        const spacing = 200;

        objects.forEach((object, index) => {
            const row = Math.floor(index / cols);
            const col = index % cols;

            const x = (col - cols / 2) * spacing;
            const y = (rows / 2 - row) * spacing;

            object.position.set(x, y, 0);
        });

        animateTransition(objects, newPositions);
    }

    function arrangeSphere(objects) {
        const radius = 500;

        objects.forEach((object, index) => {
            const phi = Math.acos(-1 + (2 * index) / objects.length);
            const theta = Math.sqrt(objects.length * Math.PI) * phi;

            object.position.x = radius * Math.cos(theta) * Math.sin(phi);
            object.position.y = radius * Math.sin(theta) * Math.sin(phi);
            object.position.z = radius * Math.cos(phi);
        });

        animateTransition(objects, newPositions);

    }

    function arrangeHelix(objects) {
        const spacing = 40;
        const radius = 400;
        const twistFactor = 0.1; // Determines the "twist" of the helix

        objects.forEach((object, index) => {
            const angle = twistFactor * index;
            const y = -500 + spacing * index;
            const z = Math.sin(angle) * radius;

            // Create the double helix by adding another offset to the x position
            const x = Math.cos(angle) * radius + (index % 2 === 0 ? 0 : 100);

            object.position.set(x, y, z);
        });

        animateTransition(objects, newPositions);

    }


    function arrangeGrid(objects) {
        const rows = 4;
        const cols = 5;
        const depth = 10; // Depth along the z-axis
        const spacing = 200;

        objects.forEach((object, index) => {
            const row = Math.floor(index / (cols * depth));
            const col = Math.floor((index % (cols * depth)) / depth);
            const z = index % depth;

            const x = (col - cols / 2) * spacing;
            const y = (rows / 2 - row) * spacing;

            object.position.set(x, y, z * spacing);
        });

        animateTransition(objects, newPositions);

    }
    </script>

    @else
    <script>
    window.location.href = '/auth/google';
    </script>
    @endif
</body>

</html>