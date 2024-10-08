import * as THREE from 'three';
import { GLTFLoader } from './GLTFLoader.js';

function main() {

	const canvas = document.querySelector( '#container-3d' );
	const renderer = new THREE.WebGLRenderer( { antialias: true, canvas } );

	const fov = 45;
	const aspect = 2; // the canvas default
	const near = 0.1;
	const far = 100;
	const camera = new THREE.PerspectiveCamera( fov, aspect, near, far );
	camera.position.set( 0, 10, 0 );

	const scene = new THREE.Scene();
	scene.background = new THREE.Color( 'black' );

	// Variables to control rotation
    let scrollY = window.scrollY;

    function handleScroll() {
        const delta = window.scrollY - scrollY;
        scrollY = window.scrollY;

        // Adjust rotation based on scroll direction
        const rotationSpeed = 0.002;
        scene.rotation.y += delta * rotationSpeed;

        // Ensure rotation stays within 0 to 2π range
        scene.rotation.y = (scene.rotation.y + Math.PI * 2) % (Math.PI * 2);
    }

    window.addEventListener('scroll', handleScroll);

	{

		const planeSize = 40;

		const loader = new THREE.TextureLoader();
		const texture = loader.load( './wp-content/themes/virtuality/assets/js/checker.png' );
		texture.wrapS = THREE.RepeatWrapping;
		texture.wrapT = THREE.RepeatWrapping;
		texture.magFilter = THREE.NearestFilter;
		texture.colorSpace = THREE.SRGBColorSpace;
		const repeats = planeSize / 2;
		texture.repeat.set( repeats, repeats );
	}

	{

		const skyColor = 0xB1E1FF; // light blue
		const groundColor = 0xB97A20; // brownish orange
		const intensity = 2;
		const light = new THREE.HemisphereLight( skyColor, groundColor, intensity );
		scene.add( light );

	}

	{

		const color = 0xFFFFFF;
		const intensity = 2.5;
		const light = new THREE.DirectionalLight( color, intensity );
		light.position.set( 5, 10, 2 );
		scene.add( light );
		scene.add( light.target );

	}

	function frameArea( sizeToFitOnScreen, boxSize, boxCenter, camera ) {
		if (window.innerWidth >= 1000) {
			var ratio = 0.5;
		} else {
			var ratio = 0.35;
		}
		const halfSizeToFitOnScreen = sizeToFitOnScreen * 0.5;
		const halfFovY = THREE.MathUtils.degToRad( camera.fov * ratio );
		const distance = halfSizeToFitOnScreen / Math.tan( halfFovY );
		// compute a unit vector that points in the direction the camera is now
		// in the xz plane from the center of the box
		const direction = ( new THREE.Vector3() )
			.subVectors( camera.position, boxCenter )
			.multiply( new THREE.Vector3( 1, 0, 1 ) )
			.normalize();

		// move the camera to a position distance units way from the center
		// in whatever direction the camera was from the center already
		camera.position.copy( direction.multiplyScalar( distance ).add( boxCenter ) );

		// pick some near and far values for the frustum that
		// will contain the box.
		camera.near = boxSize / 100;
		camera.far = boxSize * 100;

		camera.updateProjectionMatrix();

		// point the camera to look at the center of the box
		camera.lookAt( boxCenter.x, boxCenter.y, boxCenter.z );

	}

	{

		const gltfLoader = new GLTFLoader();
		gltfLoader.load( './wp-content/themes/virtuality/assets/3d/a320.glb', ( gltf ) => {

			const root = gltf.scene;
			scene.add( root );

			// compute the box that contains all the stuff
			// from root and below
			const box = new THREE.Box3().setFromObject( root );

			const boxSize = box.getSize( new THREE.Vector3() ).length();
			const boxCenter = box.getCenter( new THREE.Vector3() );

			// set the camera to frame the box
			frameArea( boxSize * 0.5, boxSize, boxCenter, camera );
		} );

	}

	function resizeRendererToDisplaySize( renderer ) {

		const canvas = renderer.domElement;
		const width = canvas.clientWidth;
		const height = canvas.clientHeight;
		const needResize = canvas.width !== width || canvas.height !== height;
		if ( needResize ) {

			renderer.setSize( width, height, false );

		}

		return needResize;

	}

	function render() {

		if ( resizeRendererToDisplaySize( renderer ) ) {

			const canvas = renderer.domElement;
			camera.aspect = canvas.clientWidth / canvas.clientHeight;
			camera.updateProjectionMatrix();

		}

		renderer.render( scene, camera );

		requestAnimationFrame( render );

	}

	requestAnimationFrame( render );

}

main();