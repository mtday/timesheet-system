
INSERT INTO employees (`id`, `login`, `hashed_pass`, `email`, `first_name`, `last_name`, `suffix`, `division`, `personnel_type`, `ssn`, `dob`, `pob`, `citizenship`) VALUES
(1, 'mday', 'cf099c00087b3c7d3d34f5858b170f9cab94806d78aa04414cfd429956feb8abf1c3cc42125ccd0c03f9b3a14118397a878ae97bfd50b5478ede5098de71fd92', 'mike.day@milestoneintelligence.com', 'Mike', 'Day', NULL, 'Executive', 'Employee'),
(2, 'dstewart', 'cf099c00087b3c7d3d34f5858b170f9cab94806d78aa04414cfd429956feb8abf1c3cc42125ccd0c03f9b3a14118397a878ae97bfd50b5478ede5098de71fd92', 'doug.stewart@milestoneintelligence.com', 'Doug', 'Stewart', NULL, 'Executive', 'Employee');

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
(8, 'OCEANSURF - MISSIONBELL', 'OCE-001-123', 'TTO-MSB-001', false),
(9, 'TUSCANSUN - ABCD', 'TUS-001-123', 'TTO-ABC-101', false);


INSERT INTO contract_assignments (`contract_id`, `employee_id`, `labor_cat`, `start`, `end`) VALUES
(8, 1, 'SWE3', '2010-04-19', NULL),
(9, 1, 'SWE4', '2010-06-05', '2010-06-10'),
(9, 2, 'SE5', NULL, NULL);


INSERT INTO pay_periods (`start`, `end`, `type`) VALUES
('2010-08-01', '2010-08-15', 'semimonthly');


INSERT INTO bills (`id`, `contract_id`, `employee_id`, `day`, `hours`) VALUES
(1, 8, 1, '2010-06-05', 8.0);


INSERT INTO timesheets (`id`, `employee_id`, `pp_start`) VALUES
(1, 1, '2010-05-30');


INSERT INTO audit_logs (`timesheet_id`, `log`, `timestamp`) VALUES
(1, 'Initial empty timesheet created.', '2010-05-31 16:22:35');


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


INSERT INTO vr_companies (`id`, `company_name`, `street`, `city`, `state`, `zip`, `sec_poc_name`, `sec_poc_phone`, `sec_poc_fax`, `sec_poc_email`) VALUES
(1, 'Company ABC', '1234 Main St', 'Severn', 'MD', 21144, 'Im the poc', '999-999-9999', '999-999-9998', 'Imthepoc@email.com'),
(2, 'Company MNO', '4321 Main St', 'Odenton', 'MD', 21113, 'Hes the poc', '111-111-1111', '111-111-1112', 'Hesthepoc@email.com'),
(3, 'Company XYZ', '9876 Main St', 'Ft. Meade', 'MD', 21755, 'Youre the poc', '555-555-5555', '555-555-5556', 'Yourethepoc@email.com');

INSERT INTO vrs (`id`, `employee_id`, `company_id`, `start_date`, `end_date`) VALUES
(1, 1, 1, '2010-01-01', '2010-12-31'),
(2, 1, 1, '2011-02-01', '2011-12-01'),
(3, 1, 2, '2011-02-02', '2011-12-02'),
(4, 1, 3, '2011-02-03', '2011-12-03'),
(5, 2, 1, '2011-02-04', '2011-12-04');

