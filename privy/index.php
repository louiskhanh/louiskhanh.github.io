<?php
include('include/Setup.php');

switch(PAGE) {
	case p(Index):
		include('include/control/frontend/index.php');
	    break;
    case p(UserLogin):
        include('include/control/user/login.php');
        break;
    case p(UserLogout):
        include('include/control/user/logout.php');
        break;


    case p(Directory):
        include('include/control/frontend/directory.php');
        break;
    case p(DirectoryChild):
        include('include/control/frontend/directory.php');
        break;

}
include('include/Output.php');

?>