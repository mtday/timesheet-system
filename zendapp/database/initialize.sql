
INSERT INTO employees (`id`, `login`, `email`, `first_name`, `last_name`, `suffix`, `division`, `personnel_type`, `hashed_pass`) VALUES
(1, 'user1', 'user1@wherever.com', 'User', 'One', NULL, 'Engineering Division', 'Employee',
    'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86'),
(2, 'user2', 'user2@wherever.com', 'User', 'Two', NULL, 'Marketing Division', 'Employee',
    'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86');

INSERT INTO roles (`name`, `employee_id`) VALUES
('admin', 1),
('payroll', 1),
('manager', 1),
('payroll', 2),
('manager', 2);


INSERT INTO supervisors (`employee_id`, `supervisor_id`, `primary`) VALUES
(1, 2, true);


INSERT INTO contracts (`id`, `description`, `contract_num`, `job_code`, `admin`) VALUES
(1, 'Paid Time Off', 'PTO', 'PTO', true),
(2, 'Leave Without Pay', 'LWOP', 'LWOP', true),
(3, 'Holiday', 'HOL', 'HOLIDAY', true),
(4, 'Overhead', 'OH', 'OVERHEAD', true),
(5, 'G&A', 'GA', 'G&A', true),
(6, 'Bid and Proposal', 'BP', 'BIDPROPOSAL', true),
(7, 'Jury Duty', 'JURY', 'Jury Duty', true),
(8, 'Initech TPS', 'INITECH', '01', false),
(9, 'Smith Job', 'SMITH', '01', false);


INSERT INTO contract_assignments (`id`, `contract_id`, `employee_id`, `labor_cat`, `item_name`, `start`, `end`) VALUES
(1, 8, 1, 'Software Engineer 4', 'User1:SWE4', '2015-12-01', NULL),
(2, 9, 1, 'Software Engineer 4', 'User1:SWE4', '2015-12-01', '2016-06-30'),
(3, 9, 2, 'Marketer 3', 'User2:MRK3', NULL, NULL);


INSERT INTO pay_periods (`start`, `end`, `type`) VALUES
('2015-12-01', '2015-12-15', 'semimonthly');


INSERT INTO bills (`id`, `contract_id`, `assignment_id`, `employee_id`, `day`, `hours`) VALUES
(1, 8, 1, 1, '2015-12-05', 8.0);


INSERT INTO timesheets (`id`, `employee_id`, `pp_start`) VALUES
(1, 1, '2015-12-01');


INSERT INTO audit_logs (`timesheet_id`, `log`, `timestamp`) VALUES
(1, 'Initial empty timesheet created.', '2015-12-01 16:22:35');


INSERT INTO holidays (`description`, `config`) VALUES
('New Years', 'January 1st Observance'),
('Martin Luther King Day', '2nd Monday in January'),
('President''s Day', '3rd Monday in February'),
('Memorial Day', 'Last Monday in May'),
('Independence Day', 'July 4th Observance'),
('Labor Day', '1st Monday in September'),
('Columbus Day', '2nd Monday in October'),
('Veterans Day', 'November 11th Observance'),
('Thanksgiving Day', '4th Thursday in November'),
('Christmas Day', 'December 25th Observance');

