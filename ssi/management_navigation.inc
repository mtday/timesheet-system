
<!-- Add all the management actions. -->
<ul id="management-navigation">
  <?php if ($this->employee->manager): ?>
	<!-- Add the manager activities. -->
	<li><a href="/manager/employee">Employees</a></li>
	<li><a href="/manager/contract">Contracts</a></li>
  <?php endif; ?>

  <?php if ($this->employee->supervisor): ?>
	<!-- Add the activities available for supervisors. -->
	<li><a href="/supervisor/index">Supervisor</a></li>
  <?php endif; ?>

  <?php if ($this->employee->payroll): ?>
	<!-- Add the activities available for payroll. -->
	<li><a href="/payroll/index">Payroll</a></li>
  <?php endif; ?>

  <?php if ($this->employee->admin): ?>
	<!-- Add the activities available for administrators. -->
	<li><a href="/admin/holiday">Holidays</a></li>
  <?php endif; ?>

  <?php if ($this->employee->manager): ?>
	<!-- Add the manager activities. -->
	<li><a href="/manager/contact">Contacts</a></li>
  <?php endif; ?>
</ul>

