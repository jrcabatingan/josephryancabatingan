<?php 
/*$seomoz = new Nuts_SEOmoz_API();
	var_dump(
		$seomoz->request('','16384'), // MOZrank
		$seomoz->request('www.searchcreatively.com','68719476736'), // Domain Authority
		$seomoz->request('www.searchcreatively.com','34359738368'), // Page Authority
		$seomoz->request('www.searchcreatively.com','2048') // Links
	);
$ahrefs = new Nuts_Ahrefs_API();
	var_dump(
		$ahrefs->request('www.searchcreatively.com'),
		$ahrefs->urlRequest
	);*/
$sc = array(
	array(
		'country' => 'USA',
		'search-engine' => 'yahoo',
		'search-term' => 'Raleigh Internet Marketing',
		'url' => 'http://www.searchcreatively.com'
	),
	array(
		'country' => 'USA',
		'search-engine' => 'yahoo',
		'search-term' => 'Raleigh SEO',
		'url' => 'http://www.searchcreatively.com'
	),
	array(
		'country' => 'USA',
		'search-engine' => 'yahoo',
		'search-term' => 'Raleigh Marketing',
		'url' => 'http://www.searchcreatively.com'
	),
	array(
		'country' => 'USA',
		'search-engine' => 'yahoo',
		'search-term' => 'Raleigh Search Engine Optimization',
		'url' => 'http://www.searchcreatively.com'
	),
);	
$brightlocal = new Nuts_Brightlocal_API(1800);
	/*var_dump(
		$brightlocal->batch_request_rankings($sc)
		//$brightlocal->batch_request_rankings($sc),
		//$brightlocal->batch_id
	);*/
	echo '<pre>';
	print_r($brightlocal->batch_request_rankings($sc));
	echo '</pre>';
?>
<div class="dashboard row-fluid btm-spc">
	<div class="pull-left span4"><h4>Welcome Back, Mark!</h4></div><!-- Welcome text -->
    <div class="pull-right span8">
        <div id="search-list-controls" class="pull-left span7">
            <div id="search-campaigns" class="list-controls input-append pull-left">
                <input class="" id="search-list" name="search-list" type="text" placeholder="seach campaigns..." />
                <span class="add-on"><i class="icon-search"></i></span>
            </div><!-- #search-campaigns -->
            <div id="list-display-option" class="pull-right span3">
                <div class="btn-group">
                <a class="btn def-tooltip" href="#" rel="tooltip" data-placement="bottom" title="Display campaigns as thumbnails"><i class="icon-th"></i></a>
                <a class="btn def-tooltip" href="#" rel="tooltip" data-placement="bottom" title="Display campaigns in a list"><i class="icon-th-list"></i></a>
                </div>
            </div><!-- #list-display-option -->
        </div><!-- #search-list-controls -->
        <div id="campaigns-button" class="pull-right span5">
             <a href="<?php echo $url; ?>" class="btn btn-large btn-block btn-primary" type="button">New Campaigns</a>
        </div><!-- #campaings-button -->
    </div><!-- Dashboard Controls -->
</div>
<div id="campaigns-lists" class="btm-spc">
	<ul class="thumbnails">
    	 <li class="span4">
            <a href="#" class="thumbnail">
           		<img src="<?php echo NUTS_CORE_URI . '/img/screenshot.png'; ?>" alt="">
                <p class="caption">www.example-site.com</p>
            </a>
        </li>
        <li class="span4">
            <a href="#" class="thumbnail">
           		<img src="<?php echo NUTS_CORE_URI . '/img/screenshot.png'; ?>" alt="">
                <p class="caption">www.example-site.com</p>
            </a>
        </li>
        <li class="span4">
            <a href="#" class="thumbnail">
           		<img src="<?php echo NUTS_CORE_URI . '/img/screenshot.png'; ?>" alt="">
                <p class="caption">www.example-site.com</p>
            </a>
        </li>
        <li class="span4">
            <a href="#" class="thumbnail">
           		<img src="<?php echo NUTS_CORE_URI . '/img/screenshot.png'; ?>" alt="">
                <p class="caption">www.example-site.com</p>
            </a>
        </li>
        <li class="span4">
            <a href="#" class="thumbnail">
           		<img src="<?php echo NUTS_CORE_URI . '/img/screenshot.png'; ?>" alt="">
                <p class="caption">www.example-site.com</p>
            </a>
        </li>      
    </ul>
<a href="#" id="show-more" class="btn btn-block">Show More</a>
</div>
