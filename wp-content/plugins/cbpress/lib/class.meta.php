<?php
if (!defined('ABSPATH')) die();
class CBP_Meta {

	public static $data = array();
	public static $sortables = array();
	public static $filters = array();
	public static $clickbank = array();
	public static $headers = array();

	public function create( $args = array() ) {

		$defaults = array(
			'id'      	=> 'default_field',
			'label'   	=> __( 'Default Field', CBPRESS_TRANS ),
			'short'   	=> __( 'Default', CBPRESS_TRANS ),
			'title'   	=> '',
			'desc'    	=> __( '', CBPRESS_TRANS ),
			'std'     	=> '',
			'type'    	=> 'text',
			'filter'  	=> 0,
			'sort' 	  	=> 1,
			'head' 	  	=> 0,
			'sortlabel' => '',
			'sortorder' => 'asc',
			'clickbank' => 0,
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);

		extract( wp_parse_args( $args, $defaults ) );

		if ( $title == '' ) $title = $label;
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class,
			'title'  	=> $title,
			'label'  	=> $label,
			'short'  	=> $short,
			'filter'  	=> $filter,
			'sort' 	  	=> $sort,
			'sortlabel' => $sortlabel,
			'sortorder' => $sortorder,
			'clickbank' => $clickbank
		);

		if ( $clickbank > 0 ) self::$clickbank[$id] = $id;
		if ( $head > 0  || 1 == 2) self::$headers[$id] = $head;
		if ( $sort == 1 ) self::$sortables[] = $id;
		if ( $filter == 1 ) self::$filters[] = $id;

		self::$data[$id] = $field_args;
	}

	function getSortByList() {
		$out = array();
		foreach (self::$sortables AS $id){
			$out[$id] = self::$data[$id]['sortlabel'];
		}
		return $out;
	}
	function getMinMaxList() {
		$out = array();
		foreach (self::$filters AS $id){
			$out[$id] = self::$data[$id];
		}
		return $out;
	}
	function getClickbankCols() {
		$out = array();
		foreach (self::$clickbank AS $id){
			$out[$id] = self::$data[$id];
		}
		return $out;
	}
	function getOrderByList() {
		$out = array();
		$out['asc'] = 'Asc';
		$out['desc'] = 'Desc';
		return $out;
	}
	function getResultHeaders() {
		$out = array();
		$arr = self::$headers;
		asort( $arr );
		foreach ($arr as $id => $pos) {
			$out[$id] = self::$data[$id];
		}
		return $out;
	}

	function getArray() {
		return self::$data;
	}
	function get($id) {
		return self::$data[$id];
	}

	function __construct() {





			self::create(
				array(
					  'id' =>  'lid'
					, 'label' =>  __('Product ID', CBPRESS_TRANS)
					, 'short' =>  __('ID', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Product ID', CBPRESS_TRANS)
					, 'desc' =>  __('This is the database product ID', CBPRESS_TRANS)
					, 'head' => 1
					, 'sort' => 1
					, 'filter' => 0
			));
			self::create(
				array(
					  'id' =>  'title'
					, 'label' =>  __('Title', CBPRESS_TRANS)
					, 'short' =>  __('Title', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Product Title', CBPRESS_TRANS)
					, 'desc' =>  __('The title of the product', CBPRESS_TRANS)
					, 'head' => 2
					, 'sort' => 1
					, 'filter' => 0
			));
			self::create(
				array(
					  'id' =>  'vin'
					, 'label' =>  __('Vendor', CBPRESS_TRANS)
					, 'short' =>  __('Vendor', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Vendor', CBPRESS_TRANS)
					, 'desc' =>  __('This is the ClickBank Vendor\'s ID', CBPRESS_TRANS)
					, 'head' => 1
					, 'sort' => 1
					, 'filter' => 0
					, 'clickbank' => 1
			));
			self::create(
				array(
					  'id' =>  'status'
					, 'label' =>  __('Status', CBPRESS_TRANS)
					, 'short' =>  __('Status', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Status', CBPRESS_TRANS)
					, 'desc' =>  __('This denotes whether a product is active in the ClickBank XML feed', CBPRESS_TRANS)
					, 'head' => 3
					, 'sort' => 1
					, 'filter' => 0
			));



			self::create(
				array(
					  'id' =>  'created'
					, 'label' =>  __('Date Added', CBPRESS_TRANS)
					, 'short' =>  __('Added', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Date Added', CBPRESS_TRANS)
					, 'desc' =>  __('Date product was added to your database', CBPRESS_TRANS)
					, 'head' => 4
					, 'sort' => 1
					, 'filter' => 0
			));


			self::create(
				array(
					  'id' =>  'ActivateDate'
					, 'label' =>  __('Activate Date', CBPRESS_TRANS)
					, 'short' =>  __('Activated on CB', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Activate Date', CBPRESS_TRANS)
					, 'desc' =>  __('The date the product was activated on Clickbank', CBPRESS_TRANS)
					, 'head' => 5
					, 'sort' => 1
					, 'filter' => 0
					, 'clickbank' => 1
			));

			self::create(array(
					  'id' =>  'Gravity'
					, 'label' =>  __('Gravity', CBPRESS_TRANS)
					, 'short' =>  __('Grav', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Gravity', CBPRESS_TRANS)
					, 'desc' =>  __('This number of unique affiliates who have successfully promoted (and sold) the product over a 12-week period.', CBPRESS_TRANS)
					, 'head' => 7
					, 'sort' => 1
					, 'filter' => 1
					, 'clickbank' => 1
			));
			self::create(
				array(
					  'id' =>  'Commission'
					, 'label' =>  __('Commission', CBPRESS_TRANS)
					, 'short' =>  __('Earn%', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Commission', CBPRESS_TRANS)
					, 'sortorder' =>  __('desc', CBPRESS_TRANS)
					, 'desc' =>  __('This is the commission percentage per sale earned by a single affiliate sale', CBPRESS_TRANS)
					, 'type' =>  'select'
					, 'head' => 8
					, 'sort' => 1
					, 'filter' => 1
					, 'choices' => self::getPercentageArray()
					, 'clickbank' => 1
				)
			);

			self::create(array(
					  'id' =>  'rank'
					, 'label' =>  __('Rank', CBPRESS_TRANS)
					, 'short' =>  __('Rank', CBPRESS_TRANS)
					, 'desc' => __("This is where the product ranks under a specific marketplace category.
							Clickbank gives a Popularity Rank to products based on \'productivity score\'. 
							This rank depends on these factors: Initial $/sale, %/sale , % referred and gravity
							$ Earned/Sale,Future $, Total $, %Earned/Sale, %Referred and Gravity")
					, 'sortlabel' =>  __('Category Rank', CBPRESS_TRANS)
					, 'sort' => 1
					, 'head' => 6
					, 'filter' => 1
			));

			self::create(array(
					  'id' =>  'PopularityRank'
					, 'label' =>  __('Avg Rank', CBPRESS_TRANS)
					, 'short' =>  __('Avg Rank', CBPRESS_TRANS)
					, 'desc' =>  __('This is a calculated average Popularity Rank across the entire marketplace for which a product is listed under', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Popularity Rank (avg)', CBPRESS_TRANS)
					, 'sort' => 1
					, 'filter' => 0
					, 'clickbank' => 1
			));




			self::create(array(
					  'id' => 'InitialEarningsPerSale'
					, 'label' =>  __('Initial $ Per Sale', CBPRESS_TRANS)
					, 'short' =>  __('EPS $', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Initial $/sale', CBPRESS_TRANS)
					, 'desc' =>  __('For one-time purchases, this number is the same as Initial $/sale.', CBPRESS_TRANS)
					, 'head' => 9
					, 'sort' => 1
					, 'filter' => 0
					, 'clickbank' => 1
			));

			self::create(array(
					  'id' =>  'AverageEarningsPerSale'
					, 'label' =>  __('Average $ Per Sale', CBPRESS_TRANS)
					, 'short' =>  __('Avg $/sale', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Avg $/sale', CBPRESS_TRANS)
					, 'desc' =>  __('For one-time purchases, this number is the same as Initial $/sale (EPS).', CBPRESS_TRANS)
					, 'sort' => 1
					, 'filter' => 0
					, 'clickbank' => 1
			));

			self::create(array(
					  'id' =>  'TotalRebillAmt'
					, 'label' =>  __('Avg Rebill Total', CBPRESS_TRANS)
					, 'short' =>  __('TotalRebillAmt', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Avg Rebill Total', CBPRESS_TRANS)
					, 'desc' =>  __('For one-time purchases, this number is the same as Initial $/sale (EPS).', CBPRESS_TRANS)
					, 'sort' => 1
					, 'filter' => 0
					, 'clickbank' => 1
			));

			self::create(array(
					  'id' => 'PercentPerRebill'
					, 'label' =>  __('Avg %/rebill', CBPRESS_TRANS)
					, 'short' =>  __('Avg %/rebill', CBPRESS_TRANS)
					, 'sortlabel' =>  __('PercentPerRebill', CBPRESS_TRANS)
					, 'desc' =>  __('This number is only shown if the vendor offers recurring billing products, and shows the average commission rate earned only on rebills. ', CBPRESS_TRANS)
					, 'sort' => 1
					, 'filter' => 0
					, 'clickbank' => 1
			));

			
			self::create(array(
					  'id' =>  'PercentPerSale'
					, 'label' =>  __('PercentPerSale', CBPRESS_TRANS)
					, 'short' =>  __('EPS %', CBPRESS_TRANS)
					, 'sortlabel' =>  __('PercentPerSale', CBPRESS_TRANS)
					, 'desc' =>  __('%/sale percentage eraned per sale', CBPRESS_TRANS)
					, 'sort' => 1
					, 'filter' => 0
					, 'clickbank' => 1
			));




			self::create(array(
					  'id' =>  'HasRecurringProducts'
					, 'label' =>  __('Recurring', CBPRESS_TRANS)
					, 'short' =>  __('Recur', CBPRESS_TRANS)
					, 'sortlabel' =>  __('Has Recurring Products', CBPRESS_TRANS)
					, 'desc' =>  __('Yes, if the product charges customer on a subscription basis; No, if the product charges customer a one-time payment only.', CBPRESS_TRANS)
					, 'head' => 10
					, 'sort' => 1
					, 'filter' => 0
					, 'clickbank' => 1
			));


			self::create(array(
					  'id' =>  'Referred'
					, 'label' =>  __('%Referred', CBPRESS_TRANS)
					, 'short' =>  __('Referred', CBPRESS_TRANS)
					, 'desc' =>  __('This is the percentage of a vendor\'s product sales that are referred by affiliates', CBPRESS_TRANS)
					, 'sortlabel' =>  __('%Referred', CBPRESS_TRANS)
					, 'filter' => 1
					, 'clickbank' => 1
			));


			self::create(array(
					  'id' =>  'description'
					, 'label' =>  __('Description', CBPRESS_TRANS)
					, 'short' =>  __('Description', CBPRESS_TRANS)
					, 'desc' =>  __('This description is supplied by the item\'s vendor', CBPRESS_TRANS)
					, 'sort' => 0
					, 'filter' => 0
			));






		}

		function getPercentageArray() {

			$choices = range(0,75,5);
			array_unshift($choices,'');
			$percfn = create_function('$value','$value=$value."%";   return ($value=="%")?"":$value;');
			$choices = array_combine($choices,array_map($percfn,$choices));
			return $choices;

		}





}
new CBP_Meta;
