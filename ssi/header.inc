
<!-- The upper banner displayed at the top of every page. -->
<div id="upper-banner"></div>

<!-- The title bar displayed at the top of every page. -->
<div id="title-banner">

  <!-- The site Logo -->
  <div id="logo-banner">

    <?php if (isset($this->employee)): ?>
	  <!-- The title banner upper navigation links. -->
	  <div id="upper-navigation">
	    <ul>
		  <li><a href="/user/timesheet">Timesheets</a></li>
		  <?php if ($this->employee->has_privileges): ?>
			<li><a href="/user/manage">Manage</a></li>
		  <?php endif; ?>
		  <li><a href="/user/profile">Profile</a></li>
		  <li><a href="/login/logout">Logout</a></li>
	    </ul>
	  </div>
    <?php endif; ?>

  </div>
</div>

<!-- Add the auxiliary middle banner. -->
<div id="middle-banner">
  <!-- Add the middle banner image. -->
  <div id="middle-banner-aux"></div>
</div>

