
<!-- The upper banner displayed at the top of every page. -->
<div id="upper-banner"></div>

<!-- The title bar displayed at the top of every page. -->
<div id="title-banner">

  <!-- The site Logo -->
  <div id="logo-banner">

	<!-- The title banner upper navigation links. -->
	<div id="upper-navigation">
	  <ul>
		<li class="first"><a href="/">Home</a></li>
		<?php if (isset($this->employee)): ?>
		  <li><a href="/user/timesheet">Timesheets</a></li>
		  <?php if ($this->employee->has_privileges): ?>
			<li><a href="/user/manage">Manage</a></li>
		  <?php endif; ?>
		  <li><a href="/user/profile">Profile</a></li>
		  <?php if ($this->employee->wiki): ?>
			<li><a href="/wiki">Wiki</a></li>
		  <?php endif; ?>
		  <li><a href="/user/forms">Forms</a></li>
		  <li><a href="https://mail.milestoneintelligence.com">Mail</a></li>
		  <li><a href="/login/logout">Logout</a></li>
		<?php else: ?>
		  <li><a href="/index/contact">Contact Us</a></li>
		  <li><a href="https://mail.milestoneintelligence.com">Mail</a></li>
		  <li><a href="/login/login">Login</a></li>
		<?php endif; ?>
	  </ul>
	</div>

	<!-- The title banner lower navigation links. -->
	<div id="lower-navigation">
	  <ul>
		<li class="first"><a href="/index/about">About Us</a></li>
		<li><a href="/index/services">Services</a></li>
		<li><a href="/index/partners">Partners</a></li>
		<li><a href="/index/careers">Careers</a></li>
		<!--
		<li><a href="/index/community">Community</a></li>
		-->
	  </ul>
	</div>

  </div>
</div>

<!-- Determine which middle banner to display based on the layout. -->
<?php if ($this->layout()->getLayout() == "home"): ?>

  <!-- Add the home page middle banner. -->
  <div id="middle-banner">
	<!-- Add the middle banner image. -->
	<div id="middle-banner-home"></div>
  </div>

<?php else: ?>

  <!-- Add the auxiliary middle banner. -->
  <div id="middle-banner">
	<!-- Add the middle banner image. -->
	<div id="middle-banner-aux"></div>
  </div>

<?php endif; ?>

