<?php
/*
	group/group-radius-process.php

	Access:		ldapadmins only

	Define radius attributes for the selected group.
*/

// includes
require("../include/config.php");
require("../include/amberphplib/main.php");
require("../include/application/main.php");


if (user_permissions_get('ldapadmins'))
{

	/*
		Load radius processing form and run logic
	*/

	$obj_radius		= New ui_radius_attributes;
	$obj_radius->obj_owner	= New ldap_auth_manage_group;

	$obj_radius->ui_process();



	/*
		Check for errors and apply if all good
	*/

	if (error_check())
	{
		$_SESSION["error"]["form"]["group_radius"] = "failed";
		header("Location: ../index.php?page=group_management/group-radius.php&id=". $obj_radius->obj_owner->id);
		exit(0);
	}
	else
	{
		/*
			Apply Changes
		*/

		if (!$obj_radius->obj_owner->update())
		{
			log_write("error", "process", "An error occured whilst attempting to update radius attributes.");
		
			$_SESSION["error"]["form"]["group_radius"] = "failed";
			header("Location: ../index.php?page=group_management/group-radius.php&id=". $obj_radius->obj_owner->id);
			exit(0);
		}
		else
		{
			error_clear();

			log_write("notification", "process", "Group radius attributes have been updated.");
		}

		// goto view page
		header("Location: ../index.php?page=group_management/group-radius.php&id=". $obj_radius->obj_owner->id);
		exit(0);


	} // if valid data input
	
	
} // end of "is group logged in?"
else
{
	// group does not have permissions to access this page.
	error_render_noperms();
	header("Location: ../index.php?page=message.php");
	exit(0);
}


?>
