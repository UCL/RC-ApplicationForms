DELIMITER //
CREATE PROCEDURE `list_of_accessible_requests` (in input_username varchar(7))
BEGIN
  SELECT * from Account_Requests
    RIGHT JOIN (
      SELECT DISTINCT request_id FROM Projects
		RIGHT JOIN (
		  SELECT DISTINCT approves_for_consortium 
			FROM Consortium_Permissions 
            LEFT JOIN Privileged_Users
            ON Consortium_Permissions.user_id = Privileged_Users.id
            WHERE username = input_username OR 
                  Privileged_Users.super_special_rainbow_pegasus_powers
		) can_approve_consortia
        ON Projects.consortium = can_approve_consortia.approves_for_consortium
	) requests_in_approvable_consortia
	ON Account_Requests.id = requests_in_approvable_consortia.id;
END //

DELIMITER ;

