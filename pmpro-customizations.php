<?php
/*
Plugin Name: Jaffe Websites Customisations
Plugin URI: https://jaffewebsites.com.au
Description: Customizations for Paid Memberships Pro Setup
Version: .1
Author: Paid Memberships Pro
Author URI: https://www.paidmembershipspro.com
*/




function bpdev_get_age_from_dob($dob_field_name,$user_id=false,$format="%y"){
	if(!$user_id)
	$user_id=bp_displayed_user_id ();
	$dob_time=xprofile_get_field_data($dob_field_name, $user_id);//get the datetime as myswl datetime
	$dob=new DateTime($dob_time);//create a DateTime Object from that
	$current_date_time=new DateTime();//current date time object
	//calculate difference
	$diff= $current_date_time->diff($dob);//returns DateInterval object
	//format and return
	return $diff->format($format);
}



define ( 'BP_AVATAR_THUMB_WIDTH', 300 );
define ( 'BP_AVATAR_THUMB_HEIGHT', 300 );
define ( 'BP_AVATAR_FULL_WIDTH', 300 );
define ( 'BP_AVATAR_FULL_HEIGHT', 300 );




function my_pmprorh_init() {
	// Don't break if Register Helper is not loaded.
	if ( ! function_exists( 'pmprorh_add_registration_field' ) ) {
		return false;
	}

	// Define the fields.
	$fields = array();

	$fields[] = new PMProRH_Field(
		'name',							// input name, will also be used as meta key
		'text',								// type of field
		array(
			'buddypress' => 'Name',
			'label'		=> 'Name',	// custom field label
			'class'		=> 'name',
			'profile'	=> true,
			'required'	=> true,
			'levels'		=> array(1)	
		)
	);	


	$fields[] = new PMProRH_Field(
		'gender',							
		'select',							
		array(
			'buddypress' => 'Gender',
			'options' => array(				
				'reg-male'	=> 'Male',		
				'reg-female'	=> 'Female',
			),
			'label'		=> 'Gender',	
			'class'		=> 'reg-gender',
			'profile'	=> true,
			'required'	=> true,
			'levels'		=> array(1)	
		)
	);

	$fields[] = new PMProRH_Field(
		'weight',							// input name, will also be used as meta key
		'number',								// type of field
		array(
			'buddypress' => 'Weight',
			'label'		=> 'Weight (kg)',	// custom field label
			'class'		=> 'reg-weight',
			'profile'	=> true,
			'required'	=> true,
			'levels'		=> array(1)	
		)
	);


	$fields[] = new PMProRH_Field(
		'height',							// input name, will also be used as meta key
		'number',								// type of field
		array(
			'buddypress' => 'Height',
			'label'		=> 'Height (cm)',	// custom field label
			'class'		=> 'reg-height',
			'profile'	=> true,
			'required'	=> true,
			'levels'		=> array(1)	
		)
	);	

	$fields[] = new PMProRH_Field(
		'gym',							// input name, will also be used as meta key
		'text',								// type of field
		array(
			'buddypress' => 'Gym',
			'label'		=> 'Gym',	// custom field label
			'class'		=> 'gym',
			'profile'	=> true,
			'required'	=> false,
			'levels'		=> array(1)	
		)
	);		

	$fields[] = new PMProRH_Field(
		'trainer',							// input name, will also be used as meta key
		'text',								// type of field
		array(
			'buddypress' => 'Trainer',
			'label'		=> 'Trainer',	// custom field label
			'class'		=> 'trainer',
			'profile'	=> true,
			'required'	=> false,
			'levels'		=> array(1)	
		)
	);			


	$fields[] = new PMProRH_Field(
		'date',							// input name, will also be used as meta key
		'date',								// type of field
		array(
			'buddypress' => 'Date of Birth',
			'label'		=> 'Date of birth',	// custom field label
			'class'		=> 'reg-dob',
			'profile'	=> true,
			'required'	=> true,
			'levels'		=> array(1)	
		)
	);


	$fields[] = new PMProRH_Field(
		'disc-1',						// input name, will also be used as meta key
		'checkbox',						// type of field
		array(
			'label' => 'Boxing',
			'profile' => true,
			'buddypress' => 'Discipline',
		)			
	);

	$fields[] = new PMProRH_Field(
		'disc-2',						// input name, will also be used as meta key
		'checkbox',						// type of field
		array(
			'label' => 'MMA',
			'profile' => true,
			'buddypress' => 'Discipline',
		)			
	);	

	$fields[] = new PMProRH_Field(
		'disc-3',						// input name, will also be used as meta key
		'checkbox',						// type of field
		array(
			'label' => 'Krav Maga',
			'profile' => true,
			'buddypress' => 'Discipline',
		)				
	);		



	$fields[] = new PMProRH_Field(
		'location',
		'select',								// type of field
		array(
			'options' => array(				
				'reg-vic'	=> 'VIC',		
				'reg-nsw'	=> 'NSW',
				'reg-qld'	=> 'QLD',
				'reg-act'	=> 'ACT',
				'reg-tas'	=> 'TAS',
				'reg-nt'	=> 'NT',

			),			
			'buddypress' => 'Location',
			'label'		=> 'Location',	// custom field label
			'class'		=> 'location',
			'profile'	=> true,
			'required'	=> true,
			'levels'		=> array(1)	
		)
	);		


	// Add the fields into a new checkout_boxes are of the checkout page.
	foreach ( $fields as $field ) {
		pmprorh_add_registration_field(
			'checkout_boxes',				// location on checkout page
			$field							// PMProRH_Field object
		);
	}

	// That's it. See the PMPro Register Helper readme for more information and examples.
}
add_action( 'init', 'my_pmprorh_init' );







function pmprobuddy_update_user_meta($meta_id, $object_id, $meta_key, $meta_value)
{		
	//make sure buddypress is loaded
	do_action('bp_init');

	//array of user meta to mirror
	$um = array(
		"gender" => "Gender",
		"weight" => "Weight",
		"height" => "Height",
	);		
		
	//check if this user meta is to be mirrored
	foreach($um as $left => $right)
	{
		if($meta_key == $left)
		{			
			//find the buddypress field
			$field = xprofile_get_field_id_from_name($right);
			
			//update it
			if(!empty($field))
				xprofile_set_field_data($field, $object_id, $meta_value);
		}
	}
}
add_action('update_user_meta', 'pmprobuddy_update_user_meta', 10, 4);

//need to add the meta_id for add filter
function pmprobuddy_add_user_meta($object_id, $meta_key, $meta_value)
{
	pmprobuddy_update_user_meta(NULL, $object_id, $meta_key, $meta_value);
}
add_action('add_user_meta', 'pmprobuddy_add_user_meta', 10, 3);






