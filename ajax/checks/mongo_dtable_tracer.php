<?php
// check for some shit
if(!isset($_GET['sEcho']))
{
	die;
}
chdir("../..");
include("inc/startup.php");
if(!isLoggedIn())
{
	header('HTTP/1.0 401 Unauthorized');
	die;
}
checkToken();

if(isset($_GET['to']))
{
	if($_GET['to'] == "NaN")
	{
		$_GET['to'] = time();
	}
}
/**
 * Script:    DataTables server-side script for PHP 5.2+ and MongoDB
 * Copyright: 2012 - Kari Söderholm, aka Haprog
 * License:   GPL v2 or BSD (3-point)
 *
 * By default Mongo documents are returned as is like they are stored in the
 * database. You can define which fields to return by overriding the empty
 * $fields array a few rows below.
 *
 * Because MongoDB documents can naturally contain nested data, this script
 * assumes (requires) that you use mDataProp in DataTables to define which
 * fields to display.
 */
mb_internal_encoding('UTF-8');
$cid = $_GET['cid']; 
$check = returnServiceCheck($cid);
$database   = MONGO_DB_CHECKS;
$user_id = $check->user_id;
$colname = $user_id."traces".$cid;
$collection =$colname;

global $m;

if(!accessToCheck($cid))
{
	die;
}
 
$m_collection = $m->$database->$collection;
 
/**
 * Define the document fields to return to DataTables (as in http://us.php.net/manual/en/mongocollection.find.php).
 * If empty, the whole document will be returned.
 */
$fields = array();
 
// Input method (use $_GET, $_POST or $_REQUEST)
$input =& $_GET;
 
/**
 * Handle requested DataProps
 */
 
// Number of columns being displayed (useful for getting individual column search info)
$iColumns = $input['iColumns'];
 
// Get mDataProp values assigned for each table column
$dataProps = array();
for ($i = 0; $i < $iColumns; $i++) {
    $var = 'mDataProp_'.$i;
    if (!empty($input[$var]) && $input[$var] != 'null') {
        $dataProps[$i] = $input[$var];
    }
}
 
/**
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large collections.
 */
$searchTermsAny = array();
$searchTermsAll = array();
 
if ( !empty($input['sSearch']) ) {
    $sSearch = $input['sSearch'];
     
    for ( $i=0 ; $i < $iColumns ; $i++ ) {
        if ($input['bSearchable_'.$i] == 'true') {
            if ($input['bRegex'] == 'true') {
                $sRegex = str_replace('/', '\/', $sSearch);
            } else {
                $sRegex = preg_quote($sSearch, '/');
            }
            $searchTermsAny[] = array(
                $dataProps[$i] => new MongoRegex( '/'.$sRegex.'/i' )
            );
        }
    }
}
 
// Individual column filtering
for ( $i=0 ; $i < $iColumns ; $i++ ) {
    if ( $input['bSearchable_'.$i] == 'true' && $input['sSearch_'.$i] != '' ) {
        if ($input['bRegex_'.$i] == 'true') {
            $sRegex = str_replace('/', '\/', $input['sSearch_'.$i]);
        } else {
            $sRegex = preg_quote($input['sSearch_'.$i], '/');
        }
        $searchTermsAll[ $dataProps[$i] ] = new MongoRegex( '/'.$sRegex.'/i' );
    }
}
 
$searchTerms = $searchTermsAll;
if (!empty($searchTermsAny)) {
    $searchTerms['$or'] = $searchTermsAny;
}

//print_r($fields); die;

//$cursor = $m_collection->find($searchTerms, $fields);
if ( trim($input['sSearch']) != "" ) {
	if(!is_numeric($_GET['from']))
	{
		$cursor = $m_collection->find(array('cid' => new MongoInt32($cid), 'output' => new MongoRegex( '/'.$input['sSearch'].'/i' )));	
	}
	else
	{
		$start = $_GET['from'] - 30;
		$end = $_GET['to'] + 30;
		$cursor = $m_collection->find(array('cid' => new MongoInt32($cid), 'output' => new MongoRegex( '/'.$input['sSearch'].'/i' ), 'time' => array('$gt' => new MongoInt32($start), '$lt' => new MongoInt32($end))));	
	}
}
else
{
	if(!is_numeric($_GET['from']))
	{
		$start = time() - 172800;
		$cursor = $m_collection->find(array('cid' => new MongoInt32($cid),'time' => array('$gt' => new MongoInt32($start))));
	}
	else
	{
		$start = $_GET['from'] - 30;
		$end = $_GET['to'] + 30;
		$cursor = $m_collection->find(array('cid' => new MongoInt32($cid),'time' => array('$gt' => new MongoInt32($start), '$lt' => new MongoInt32($end))));		
	}	
}
//$cursor = $m_collection->find(array('cid' => new MongoInt32($cid), 'hread' => 'WARNING - rb-n15-01-94-198-57-129.unbelievable-machine.net: rta 270.689ms, lost 0%'));
 
 
/**
 * Paging
 */
if ( isset( $input['iDisplayStart'] ) && $input['iDisplayLength'] != '-1' ) {
    $cursor->limit( $input['iDisplayLength'] )->skip( $input['iDisplayStart'] );
}
 
/**
 * Ordering
 */

if ( isset($input['iSortCol_0']) ) {
	if($input['iSortCol_0'] == 0)
	{
		if(is_numeric($_GET['from']))
		{
			$cursor->sort(array('time' => 1));	
		}
		else
		{
			$cursor->sort(array('time' => -1));	
		}
	}
	else 
	{
	    $sort_fields = array();
	    for ( $i=0 ; $i<intval( $input['iSortingCols'] ) ; $i++ ) {
	        if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == 'true' ) {
	            $field = $dataProps[ intval( $input['iSortCol_'.$i] ) ];
	            $order = ( $input['sSortDir_'.$i]=='desc' ? -1 : 1 );
	            $sort_fields[$field] = $order;
	        }
	    }
	    $cursor->sort($sort_fields);
	}
}
 
/**
 * Output
 */
$output = array(
    "sEcho" => intval($input['sEcho']),
    "iTotalRecords" => $m_collection->count(),
    "iTotalDisplayRecords" => $cursor->count(),
    "aaData" => array(),
);


foreach ( $cursor as $doc ) {
	$dtStr = date("c", $doc['time']);
	$date = new DateTime($dtStr, new DateTimeZone($_SESSION['timezone']));
	$doc['time'] = $date->format('D M j G:i:s T Y');
	//$doc['output'] = nl2br($doc['output']);
	$doc['output'] = '<pre>'.$doc['output'].'</pre>';
	$doc['location'] = '<img src="/images/flags/'.$doc['location'].'.png"> ' . $doc['location'];

    $output['aaData'][] = $doc;
}
 
echo json_encode( $output );
