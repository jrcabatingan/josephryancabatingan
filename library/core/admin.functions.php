<?php 
/**
 * Wordpress Administration for Nutshell Metrics
 *
 * Current functionilities added:
 	- Campaign post type
 
 * @package WordPress
 * @subpackage nutshell
 */

/**
 * Creates the Campaign Post Type 
 */
function nuts_campaign_post_type() {
	$labels = array(
		'name' => 'Nutshell Campaigns',
		'singular_name' => 'Nutshell Campaigns',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Campaign',
		'edit_item' => 'Edit Campaign',
		'new_item' => 'New Campaign',
		'view_item' => 'View Campaign',
		'search_items' => 'Search Campaign',
		'not_found' =>  'No Campaign found',
		'not_found_in_trash' => 'No Campaign found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Nutshell Campaigns'
	);
	
  	$args = array(
		'labels' => $labels,
		'public' => false,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_nav_menus' => false,
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'page',
		'has_archive' => false, 
		'hierarchical' => false,
		'menu_icon' => get_bloginfo('wpurl').'/wp-admin/images/generic.png',
		'menu_position' => 100,
		'supports' => array('title','custom-fields','revisions')
	); 
	register_post_type('nutshell-campaigns',$args);
}
add_action('init', 'nuts_campaign_post_type');

function nutshell_add_custom_box(){
	add_meta_box(
	        'nutshell_post_details',
        	__( 'Campaign Details', 'nutshell_text_content' ), 
	        'nutshell_list_fields',
        	'nutshell-campaigns'
	);
	remove_meta_box('postcustom', 'nutshell-campaigns', 'normal');
}

function nutshell_list_fields( $post ) {

  wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );

  $countrylist=array(
					array("AF","Afghanistan"),
					array("AL","Albania"),
					array("DZ","Algeria"),
					array("AS","American Samoa"),
					array("AD","Andorra"),
					array("AG","Angola"),
					array("AI","Anguilla"),
					array("AG","Antigua &amp; Barbuda"),
					array("AR","Argentina"),
					array("AA","Armenia"),
					array("AW","Aruba"),
					array("AU","Australia"),
					array("AT","Austria"),
					array("AZ","Azerbaijan"),
					array("BS","Bahamas"),
					array("BH","Bahrain"),
					array("BD","Bangladesh"),
					array("BB","Barbados"),
					array("BY","Belarus"),
					array("BE","Belgium"),
					array("BZ","Belize"),
					array("BJ","Benin"),
					array("BM","Bermuda"),
					array("BT","Bhutan"),
					array("BO","Bolivia"),
					array("BL","Bonaire"),
					array("BA","Bosnia &amp; Herzegovina"),
					array("BW","Botswana"),
					array("BR","Brazil"),
					array("BC","British Indian Ocean Ter"),
					array("BN","Brunei"),
					array("BG","Bulgaria"),
					array("BF","Burkina Faso"),
					array("BI","Burundi"),
					array("KH","Cambodia"),
					array("CM","Cameroon"),
					array("CA","Canada"),
					array("IC","Canary Islands"),
					array("CV","Cape Verde"),
					array("KY","Cayman Islands"),
					array("CF","Central African Republic"),
					array("TD","Chad"),
					array("CD","Channel Islands"),
					array("CL","Chile"),
					array("CN","China"),
					array("CI","Christmas Island"),
					array("CS","Cocos Island"),
					array("CO","Colombia"),
					array("CC","Comoros"),
					array("CG","Congo"),
					array("CK","Cook Islands"),
					array("CR","Costa Rica"),
					array("CT","Cote D'Ivoire"),
					array("HR","Croatia"),
					array("CU","Cuba"),
					array("CB","Curacao"),
					array("CY","Cyprus"),
					array("CZ","Czech Republic"),
					array("DK","Denmark"),
					array("DJ","Djibouti"),
					array("DM","Dominica"),
					array("DO","Dominican Republic"),
					array("TM","East Timor"),
					array("EC","Ecuador"),
					array("EG","Egypt"),
					array("SV","El Salvador"),
					array("GQ","Equatorial Guinea"),
					array("ER","Eritrea"),
					array("EE","Estonia"),
					array("ET","Ethiopia"),
					array("FA","Falkland Islands"),
					array("FO","Faroe Islands"),
					array("FJ","Fiji"),
					array("FI","Finland"),
					array("FR","France"),
					array("GF","French Guiana"),
					array("PF","French Polynesia"),
					array("FS","French Southern Ter"),
					array("GA","Gabon"),
					array("GM","Gambia"),
					array("GE","Georgia"),
					array("DE","Germany"),
					array("GH","Ghana"),
					array("GI","Gibraltar"),
					array("UK","Great Britain"),
					array("GR","Greece"),
					array("GL","Greenland"),
					array("GD","Grenada"),
					array("GP","Guadeloupe"),
					array("GU","Guam"),
					array("GT","Guatemala"),
					array("GN","Guinea"),
					array("GY","Guyana"),
					array("HT","Haiti"),
					array("HW","Hawaii"),
					array("HN","Honduras"),
					array("HK","Hong Kong"),
					array("HU","Hungary"),
					array("IS","Iceland"),
					array("IN","India"),
					array("ID","Indonesia"),
					array("IR","Iran"),
					array("IQ","Iraq"),
					array("IE","Ireland"),
					array("IM","Isle of Man"),
					array("IL","Israel"),
					array("IT","Italy"),
					array("JM","Jamaica"),
					array("JP","Japan"),
					array("JO","Jordan"),
					array("KZ","Kazakhstan"),
					array("KE","Kenya"),
					array("KI","Kiribati"),
					array("NK","Korea North"),
					array("KS","Korea South"),
					array("KW","Kuwait"),
					array("KG","Kyrgyzstan"),
					array("LA","Laos"),
					array("LV","Latvia"),
					array("LB","Lebanon"),
					array("LS","Lesotho"),
					array("LR","Liberia"),
					array("LY","Libya"),
					array("LI","Liechtenstein"),
					array("LT","Lithuania"),
					array("LU","Luxembourg"),
					array("MO","Macau"),
					array("MK","Macedonia"),
					array("MG","Madagascar"),
					array("MY","Malaysia"),
					array("MW","Malawi"),
					array("MV","Maldives"),
					array("ML","Mali"),
					array("MT","Malta"),
					array("MH","Marshall Islands"),
					array("MQ","Martinique"),
					array("MR","Mauritania"),
					array("MU","Mauritius"),
					array("ME","Mayotte"),
					array("MX","Mexico"),
					array("MI","Midway Islands"),
					array("MD","Moldova"),
					array("MC","Monaco"),
					array("MN","Mongolia"),
					array("MS","Montserrat"),
					array("MA","Morocco"),
					array("MZ","Mozambique"),
					array("MM","Myanmar"),
					array("NA","Nambia"),
					array("NU","Nauru"),
					array("NP","Nepal"),
					array("AN","Netherland Antilles"),
					array("NL","Netherlands (Holland, Europe)"),
					array("NV","Nevis"),
					array("NC","New Caledonia"),
					array("NZ","New Zealand"),
					array("NI","Nicaragua"),
					array("NE","Niger"),
					array("NG","Nigeria"),
					array("NW","Niue"),
					array("NF","Norfolk Island"),
					array("NO","Norway"),
					array("OM","Oman"),
					array("PK","Pakistan"),
					array("PW","Palau Island"),
					array("PS","Palestine"),
					array("PA","Panama"),
					array("PG","Papua New Guinea"),
					array("PY","Paraguay"),
					array("PE","Peru"),
					array("PH","Philippines"),
					array("PO","Pitcairn Island"),
					array("PL","Poland"),
					array("PT","Portugal"),
					array("PR","Puerto Rico"),
					array("QA","Qatar"),
					array("ME","Republic of Montenegro"),
					array("RS","Republic of Serbia"),
					array("RE","Reunion"),
					array("RO","Romania"),
					array("RU","Russia"),
					array("RW","Rwanda"),
					array("NT","St Barthelemy"),
					array("EU","St Eustatius"),
					array("HE","St Helena"),
					array("KN","St Kitts-Nevis"),
					array("LC","St Lucia"),
					array("MB","St Maarten"),
					array("PM","St Pierre &amp; Miquelon"),
					array("VC","St Vincent &amp; Grenadines"),
					array("SP","Saipan"),
					array("SO","Samoa"),
					array("AS","Samoa American"),
					array("SM","San Marino"),
					array("ST","Sao Tome &amp; Principe"),
					array("SA","Saudi Arabia"),
					array("SN","Senegal"),
					array("SC","Seychelles"),
					array("SL","Sierra Leone"),
					array("SG","Singapore"),
					array("SK","Slovakia"),
					array("SI","Slovenia"),
					array("SB","Solomon Islands"),
					array("OI","Somalia"),
					array("ZA","South Africa"),
					array("ES","Spain"),
					array("LK","Sri Lanka"),
					array("SD","Sudan"),
					array("SR","Suriname"),
					array("SZ","Swaziland"),
					array("SE","Sweden"),
					array("CH","Switzerland"),
					array("SY","Syria"),
					array("TA","Tahiti"),
					array("TW","Taiwan"),
					array("TJ","Tajikistan"),
					array("TZ","Tanzania"),
					array("TH","Thailand"),
					array("TG","Togo"),
					array("TK","Tokelau"),
					array("TO","Tonga"),
					array("TT","Trinidad &amp; Tobago"),
					array("TN","Tunisia"),
					array("TR","Turkey"),
					array("TU","Turkmenistan"),
					array("TC","Turks &amp; Caicos Is"),
					array("TV","Tuvalu"),
					array("UG","Uganda"),
					array("UA","Ukraine"),
					array("AE","United Arab Emirates"),
					array("UK","United Kingdom"),
					array("US","United States of America"),
					array("UY","Uruguay"),
					array("UZ","Uzbekistan"),
					array("VU","Vanuatu"),
					array("VS","Vatican City State"),
					array("VE","Venezuela"),
					array("VN","Vietnam"),
					array("VB","Virgin Islands (Brit)"),
					array("VA","Virgin Islands (USA)"),
					array("WK","Wake Island"),
					array("WF","Wallis &amp; Futana Is"),
					array("YE","Yemen"),
					array("ZR","Zaire"),
					array("ZM","Zambia"),
					array("ZW","Zimbabwe"),				
  );

  $nutshell_website = get_post_meta( $post->ID,"nuts_campaign_website",true);
  $nutshell_country = get_post_meta( $post->ID,"nuts_campaign_country",true);
  $nutshell_keywords = get_post_meta( $post->ID,"nuts_campaign_keywords",true);
  echo '<table><tr><td width="30%"><label for="nutshell_field_label">';
       _e("Website : ", 'nutshell_text_content' );
  echo '</label></td>';
  echo '<td><input type="text" id="nuts_campaign_website" name="nuts_campaign_website" placeholder="http://" value="'.$nutshell_website.'" size="25" /></div>';
  echo '</td></tr><tr><td><label for="nutshell_field_label">';
       _e("Country : ", 'nutshell_text_content' );
  echo '</label></td>';
  echo '<td><select id="nuts_campaign_country" name="nuts_campaign_country">
	  <option value="">Select Country</option>';

  foreach($countrylist as $country):
	echo '<option value="'.$country[0].'" '.(($country[0]==$nutshell_country)?'selected="selected"':'').'" >'.$country[1].'</option>';
  endforeach;

  echo '  </select></td></tr>';
  echo '<tr><td valign="top"><label for="nutshell_field_label">';
       _e("Keywords : ", 'nutshell_text_content' );
  echo '</label></td> ';
  echo '<td><textarea id="nuts_campaign_keywords" rows="7" cols="49" name="nuts_campaign_keywords" placeholder="Add one keyword per line">'.$nutshell_keywords.'</textarea></td></tr></table>';
}

add_action( 'add_meta_boxes', 'nutshell_add_custom_box' );
add_action( 'save_post', 'nutshell_save_meta' );

function nutshell_save_meta($post_id){
	update_post_meta($post_id,"nuts_campaign_website",$_REQUEST["nuts_campaign_website"]);
	update_post_meta($post_id,"nuts_campaign_country",$_REQUEST["nuts_campaign_country"]);
	update_post_meta($post_id,"nuts_campaign_keywords",$_REQUEST["nuts_campaign_keywords"]);
}
?>
