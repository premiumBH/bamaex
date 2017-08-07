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
                    $hasSubMenu = hasSubMenu($col, $result1->id);

					if($result1->PageSlug == '#') {
						$href = 'javascript:void(0);';
					}
					else {
						$href = CTRL.$result1->PageSlug;
					}
					if(!empty($hasSubMenu))
                    {
                        $arrow = 'arrow open';

                        $activeopen = 'active open';

                        $toggle = 'nav-toggle';
                    }else{
                        $arrow = '';

                        $activeopen = '';

                        $toggle = 'sideMenuClk';
                    }

                    if($result1->PageSlug == '#') {
                        $menu .='<li class="nav-item start '.$activeopen.'">

                                <a href="'.$href.'" class="nav-link '.$toggle.'">

                                    <i class="'.$result1->icon.'"></i>

                                    <span class="title">'.$result1->label.'</span>

                                    <span class="selected"></span>

                                    <span id="arrows" class="'.$arrow.'"></span>

                                </a>';
                    }else{
                        $menu .='<li class="nav-item start '.$activeopen.'">

                                <a href="'.$href.'" class="nav-link '.$toggle.'">

                                    <i class="'.$result1->icon.'"></i>

                                    <span class="title">'.$result1->label.'</span>

                                    <span class="selected"></span>

                                    

                                </a>
                                <a href="javascript:void(0);" style="margin: 0px;padding: 0px;">
                                <span id="arrows" class="'.$arrow.'" style="top: -31px;"></span>
                                </a>
                                ';

                    }


		   //if($result1->PageSlug == '#')
           if (!empty($hasSubMenu));
		   {

		   $menu .= "<ul class='sub-menu'>".get_menu_tree($col, $result1->id)."</ul>"; //call  recursively

		   }

 		   $menu .= "</li>";

 

    }

    

    return $menu;

}

function hasSubMenu($col, $parent_id){
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

    return $result->result();
}



?>