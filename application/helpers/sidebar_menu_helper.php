<?php



function get_menu_tree($col, $parent_id) 

{

						$href = '';

						$arrow = '';

						$activeopen = '';

						$toggle = '';

	$ci =& get_instance();

$ci->load->database();

	$menu = "";

			$sqlQuery = " SELECT "

			                ." intID AS id,"

			                ." Label  AS label,"

			                ." inParentId AS parent_id,"

			                ." varIcon AS icon,"

			                ." varPageSlug AS PageSlug"

			        ." FROM access_control where $col = 1 and inParentId='" .$parent_id . "'";

			$result = $ci->db->query($sqlQuery);

    foreach($result->result() AS $result1)

				{

					if($result1->PageSlug == '#')

					{

						$href = 'javascript:;';

						$arrow = 'arrow open';

						$activeopen = 'active open';

						$toggle = 'nav-toggle';

					}

					else

					{

						$href = $result1->PageSlug;

						$arrow = '';

						$activeopen = '';

						$toggle = ' sideMenuClk';

					}

           $menu .='<li class="nav-item start '.$activeopen.'">

                                <a href="'.CTRL.$href.'" class="nav-link '.$toggle.'">

                                    <i class="'.$result1->icon.'"></i>

                                    <span class="title">'.$result1->label.'</span>

                                    <span class="selected"></span>

                                    <span id="arrows" class="'.$arrow.'"></span>

                                </a>';

		   if($result1->PageSlug == '#')

		   {

		   $menu .= "<ul class='sub-menu'>".get_menu_tree($col, $result1->id)."</ul>"; //call  recursively

		   }

 		   $menu .= "</li>";

 

    }

    

    return $menu;

}



?>