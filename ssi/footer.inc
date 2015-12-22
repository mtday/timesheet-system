
<!-- The footer content displayed on every page. -->

<!-- Determine whether we should display the other info. -->
<?php if ($this->layout()->getLayout() == "home"): ?>
  <div id="footer-banner-home">
<?php else: ?>
  <div id="footer-banner">
<?php endif; ?>

  <div id="footer">
	<div id="copyright">
	  &copy; Copyright <?php print date("Y"); ?> Milestone Intelligence Group, Inc.
	  All rights reserved
	</div>

	<div id="legal">
	  <a href="/index/legal">
		 Privacy Policy and Terms of Use</a>
	</div>

	<!-- Determine whether we should display the other info. -->
	<?php if ($this->layout()->getLayout() == "home"): ?>

	  <div id="contact">
		<div id="address">
		  Milestone Intelligence Group, Inc.<br>
		  7050 Hi Tech Drive, Suite 105<br>
		  Hanover, Maryland 21076
		</div>
		<div id="phone">
		  Phone: 410-220-2079<br>
		  Fax: 410-242-6552
		</div>
	  </div>

	  <div id="email">
		For additional information, contact:<br/>
		info [at] milestoneintelligence.com
	  </div>

	<?php endif; ?>

  </div>
</div>

<!-- The lower banner displayed at the bottom of every page. -->
<?php if ($this->layout()->getLayout() == "home"): ?>
  <div id="lower-banner-home"></div>
<?php else: ?>
  <div id="lower-banner"></div>
<?php endif; ?>

