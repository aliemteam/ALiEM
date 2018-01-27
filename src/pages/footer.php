<?php

defined( 'ABSPATH' ) || exit;

global $social_icons;
do_action( 'avada_after_main_content' ); ?>

</main>  <!-- #main -->

<?php do_action( 'avada_after_main_container' ); ?>
<?php if ( ! is_page_template( 'blank.php' ) ) : ?>

	<div class="footer">
		<footer role="contentinfo" class="footer__container">
			<a href="/" class="footer__logo" aria-label="Navigate to homepage">
				<svg xmlns="http://www.w3.org/2000/svg" width="50" height="49.014" viewBox="0 0 49.999771 49.014278"><path d="M9.248 30.286a50.149 50.149 0 0 1 1.827-2.325 3.383 3.383 0 0 1-.389-1.566c-3.965-.863-7.65-.711-10.686-.191v6.636h7.48a53.982 53.982 0 0 1 1.767-2.554m8.232-3.966a30.444 30.444 0 0 1 1.836.948 3.39 3.39 0 0 1 4.984.354 31.818 31.818 0 0 1 5.201-1.143 28.604 28.604 0 0 1 2.54-.209 3.398 3.398 0 0 1 .8-1.282 39.566 39.566 0 0 0-3.273-6.026 3.383 3.383 0 0 1-3.4-.576 43.92 43.92 0 0 0-9.044 6.484c.219.438.346.929.356 1.45m.766 3.42c0-.147.012-.291.03-.434a29.059 29.059 0 0 0-1.537-.804 3.398 3.398 0 0 1-3.982 1.01 51.22 51.22 0 0 0-1.669 2.13c-.293.398-.576.798-.853 1.198h3.238a47.988 47.988 0 0 1 4.789-2.777 3.46 3.46 0 0 1-.016-.323m15.088 11.364V32.84h2.058a47.068 47.068 0 0 0-.524-2.08 3.396 3.396 0 0 1-2.804-2.202 25.69 25.69 0 0 0-2.269.188 29.05 29.05 0 0 0-4.754 1.043 3.387 3.387 0 0 1-.28 1.304c3.102 2.657 5.963 5.998 8.573 10.01M23.196 32.76a3.38 3.38 0 0 1-1.553.378 3.386 3.386 0 0 1-2.395-.988 43.858 43.858 0 0 0-2.582 1.422v15.442h16.667v-3.516c-3.012-5.302-6.397-9.554-10.137-12.738m2.178-22.3a40.287 40.287 0 0 1 2.031 2.07 3.396 3.396 0 0 1 .951-.139 3.39 3.39 0 0 1 2.722 1.37 52.102 52.102 0 0 1 2.255-.78V0H16.667v4.123a42.1 42.1 0 0 1 8.707 6.337M38.2 29.064a3.41 3.41 0 0 1-1.138 1.19 51 51 0 0 1 .645 2.586h9.559c-2.93-1.86-5.958-3.122-9.066-3.776m-26.742-4.84a3.39 3.39 0 0 1 4.046-.926 47.018 47.018 0 0 1 9.53-6.79 3.39 3.39 0 0 1 .51-2.627 40.065 40.065 0 0 0-1.742-1.76 39.768 39.768 0 0 0-7.137-5.36v9.413H0v7.69c3.273-.52 7.208-.618 11.459.36m21.874-8.05v-.885c-.532.178-1.06.366-1.58.561a3.374 3.374 0 0 1-.461 1.648 42.806 42.806 0 0 1 3.554 6.513 3.397 3.397 0 0 1 3.749 2.794c3.939.815 7.75 2.525 11.405 5.118v-15.75H33.333" fill="#00b092"/></svg>
			</a>
			<div class="footer__grid">
				<div class="footer__grid__item">
					<h3>About</h3>
					<ul>
						<li><a href="/about-us">About Us</a></li>
						<li><a href="/category/annual-report">Annual Report</a></li>
						<li><a href="/culture-book">Our Culture</a></li>
						<li><a href="/meet-the-team">Our Team</a></li>
						<li><a href="/contact">Contact</a></li>
						<li><a href="/disclaimer-privacy-copyright">Disclosures</a></li>
					</ul>
				</div>
				<div class="footer__grid__item">
					<h3>Affiliates</h3>
					<ul>
						<li><a href="https://aliemu.com">ALiEMU</a></li>
						<li><a href="https://aliemcards.com">ALiEM Cards</a></li>
						<li><a href="/aliem-chief-resident-incubator">Chief Resident Incubator</a></li>
						<li><a href="/faculty-incubator">Faculty Incubator</a></li>
						<li><a href="/wellness-think-tank">Wellness Think Tank</a></li>
					</ul>
				</div>
				<div class="footer__grid__item">
					<h3>Partners</h3>
					<ul>
						<li><a href="https://www.acep.org/" target="_blank" rel="noopener noreferrer">American College of Emergency Physicians</a></li>
						<li><a href="http://www.annemergmed.com/" target="_blank" rel="noopener noreferrer">Annals of Emergency Medicine</a></li>
						<li><a href="https://www.dynamed.com/" target="_blank" rel="noopener noreferrer">EBSCO DynaMed Plus</a></li>
						<li><a href="https://www.essentialsofem.com/" target="_blank" rel="noopener noreferrer">Essentials of Emergency Medicine</a></li>
						<li><a href="https://www.saem.org/" target="_blank" rel="noopener noreferrer">Society for Academic Emergency Medicine</a></li>
						<li><a href="https://www.usacs.com/" target="_blank" rel="noopener noreferrer">US Acute Care Solutions</a></li>
						<li><a href="http://westjem.com/" target="_blank" rel="noopener noreferrer">Western Journal of Emergency Medicine</a></li>
					</ul>
				</div>
			</div>
		</footer>

		<?php get_template_part( 'partials/footer', 'copyright' ); ?>

	</div>

<?php endif; // End is not blank page check. ?>

</div> <!-- wrapper -->
<?php wp_footer(); ?>
</body>
</html>
