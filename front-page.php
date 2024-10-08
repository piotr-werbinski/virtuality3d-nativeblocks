<?php
    get_header();
?>

    <main>
    	<div class="container__model">
			<div class="container__model-header">
				<h1>Virtuality</h1>
				<div id="scroll-down">
					<p>Scroll down</p>
					<svg xmlns="http://www.w3.org/2000/svg" width="360" height="360" viewBox="0 0 360 360" fill="none">
						<circle cx="180" cy="180" r="175" stroke="white" stroke-width="10"/>
						<path d="M180 310L208.868 260H151.132L180 310ZM175 50V265H185V50H175Z" fill="white"/>
					</svg>
				</div>
			</div>
			<canvas id="container-3d"></canvas>
			<script type="importmap">
				{
					"imports": {
						"three": "./wp-content/themes/virtuality/assets/js/three.module.js"
					}
				}
			</script>
		</div>
		<div class="virtuality">
			<?php the_content() ?>
		</div>
    </main>      

<?php
    get_footer();
?>