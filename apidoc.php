<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php 
		$tpl->title = APP_NAME . " - API Documentation"; 
		include("templates/core/head.tpl.php"); 
	?>
	
	</head>
	<body <?php if(isset($_SESSION['minify']) && $_SESSION['minify'] == true) { echo 'class="desktop-detected pace-done minified"'; } else { echo 'class=""';} ?>>

		<!-- HEADER -->
		<header id="header">
		<?php include("templates/core/header.tpl.php"); ?>
		</header>
		<!-- END HEADER -->

		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<?php include("templates/nav/left.tpl.php"); ?>
		<!-- END NAVIGATION -->

		
		<?php
			$user = getUserObject($_SESSION['user_id']);
		?>
		
		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">
			   <!-- breadcrumb -->
				<ol class="breadcrumb">
					<li>Home</li><li>Dashboard</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-gear"></i> API <span>> Documentation</span></h1>
					</div>
				</div>

				<!-- row -->
				<div class="row">
						<!-- NEW WIDGET START -->
						<article class="col-sm-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-toc" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					
								<header>
									<span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
									<h2>MuninMX RESTful API - Table of contents</h2>
								</header>
								<!-- widget div-->
								<style>
									#toc-h2 {
										height:200px;
										/*-moz-column-count: 3;*/
										/*-moz-column-gap: 20px;*/
										/*-webkit-column-count: 3;*/
										/*-webkit-column-gap: 20px;*/
										/*column-count: 3;*/
										/*column-gap: 20px;*/
										-webkit-column-width: 200px; /* Chrome, Safari, Opera */
										-moz-column-width: 200px; /* Firefox */
    									column-width: 200px;
    									-moz-column-fill: auto; /* Firefox */
    									column-fill: auto;
									}
								</style>
								<div>
				
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
				
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body">
										<ul>
											<li><a href="#h1-basics">Basics</a></li>
											
											<li><a id="h1-methods" href="#h1-methods">API Methods</a></li>
											<ul id="toc-h2">
												
													<li>Roles</li>
													<ul>
														<li style=""><a href="#h2-getRole">getRole</a></li>
														<li style=""><a href="#h2-listGroups">listGroups</a></li>
													</ul>
													
													<li>Nodes</li>
													<ul>
													<li style=""><a href="#h2-addNode">addNode</a></li>
													<li style=""><a href="#h2-getNode">getNode</a></li>
													<li style=""><a href="#h2-listNodes">listNodes</a></li>
													<li style=""><a href="#h2-listNodesByGroup">listNodesByGroup</a></li>
													<li style=""><a href="#h2-editNode">editNode</a></li>
													<li style=""><a href="#h2-deleteNode">deleteNode</a></li>
													<li style=""><a href="#h2-reloadPlugins">reloadPlugins</a></li>
													<li style=""><a href="#h2-packageList">packageList</a></li>
													</ul>
													
													<li>Contacts</li>
													<ul>
														<li style=""><a href="#h2-addContact">addContact</a></li>
														<li style=""><a href="#h2-listContacts">listContacts</a></li>
														<li style=""><a href="#h2-deleteContact">deleteContact</a></li>
													</ul>
													
													<li>Data</li>
													<ul>
														<li style=""><a href="#h2-getChartData">getChartData</a></li>
													</ul>
													
													<li>Buckets</li>
													<ul>
														<li style=""><a href="#h2-addBucket">addBucket</a></li>
														<li style=""><a href="#h2-getBucket">getBucket</a></li>
														<li style=""><a href="#h2-getBucketData">getBucketData</a></li>
														<li style=""><a href="#h2-listBuckets">listBuckets</a></li>
														<li style=""><a href="#h2-editBucket">editBucket</a></li>
														<li style=""><a href="#h2-deleteBucket">deleteBucket</a></li>
													</ul>
													
													<li>Events</li>
													<ul>
														<li style=""><a href="#h2-addEvent">addEvent</a></li>
													</ul>
													
													<li>Alerts</li>
													<ul>
														<li style=""><a href="#h2-addAlert">addAlert</a></li>
														<li style=""><a href="#h2-getAlert">getAlert</a></li>
														<li style=""><a href="#h2-listAlertsByNode">listAlertsByNode</a></li>
														<li style=""><a href="#h2-deleteAlert">deleteAlert</a></li>
													</ul>
													
													<li>Notifications</li>
													<ul>
														<li style=""><a href="#h2-addAlertContact">addAlertContact</a></li>
														<li style=""><a href="#h2-deleteAlertContact">deleteAlertContact</a></li>
													</ul>
													
													<li>Checks</li>
													<ul>
														<li style=""><a href="#h2-addCheck">addCheck</a></li>
														<li style=""><a href="#h2-listChecks">listChecks</a></li>
														<li style=""><a href="#h2-listChecksByName">listChecksByName</a></li>
														<li style=""><a href="#h2-deleteCheck">deleteCheck</a></li>
													</ul>										
											
											</ul>
										
										</ul>
									</div>
								</div>
							</div>
						</article>
				</div>
				
				
				
				<!-- row -->
				<div class="row">
						<!-- NEW WIDGET START -->
						<article class="col-sm-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					
								<header>
									<span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
									<h2>MuninMX RESTful API - Documentation </h2>
								</header>
								<!-- widget div-->
								<div>
				
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
				
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body">

  <section id="wiki-content" class="wiki-content">
      <h1 id="markdown-header-basics"><a name="h1-basics"></a>Basics</h1>
      
<h2 id="markdown-header-request-basics">Request Basics</h2>
<p>You can use POST or GET to communicate with the MuninMX API. The parameters <strong>apikey</strong> and <strong>method</strong> must be given.</p>
<p>Example:</p>
<div class="codehilite"><pre><span class="x">api.php?key=$apikey&amp;method=$apimethod</span>
</pre></div>


<p>Responses are always json encoded. If successfull the http response code is 200 and the body contains the json result.</p>

<hr>
<h2 id="markdown-header-return-codes">Return Codes</h2>
<p>On success HTTP Status is 200, other possible status codes are:</p>
<ul>
<li>404 (not found, as example listnodes method will issue 404 if you have no nodes)</li>
<li>403 forbidden (access denied)</li>
<li>400 bad request (parameter missing or other error)</li>
</ul>
<p>On failure a json encoded output will state the issue in a "msg" parameter, example:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;status&quot;</span><span class="p">:</span><span class="mi">400</span><span class="p">,</span><span class="nt">&quot;msg&quot;</span><span class="p">:</span><span class="s2">&quot;unknown method specified&quot;</span><span class="p">}</span>
</pre></div>


<hr>
<h2 id="markdown-header-api-caching">API Caching</h2>
<p>Please note that if you use GET that the API caches all results for 60 seconds. We recommend using GET for storage backend querys such as getChartData. </p>
<h1 id="markdown-header-api-methods"><a name="h1-methods"></a>API Methods</h1>
<p>Some methods are only available to certain user roles (User Extended or Admin). You can see your user role status by using the getRole method.</p>
<h2 id="markdown-header-getrole"><a name="h2-getRole"></a>getRole</h2>
<p>Will return your user role.</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=getRole</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;role&quot;</span><span class="p">:</span><span class="s2">&quot;userext&quot;</span><span class="p">}</span>
</pre></div>


<p>Valid Roles are: user, userext and admin</p>

<hr>
<h2 id="markdown-header-listnodes"><a name="h2-listNodes"></a>listNodes</h2>
<p>Will return a list of all your nodes.</p>
<p>Optional Parameter: &amp;search=</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=listNodes&amp;search=destiny</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">[</span>
    <span class="p">{</span>
        <span class="nt">&quot;id&quot;</span><span class="p">:</span> <span class="s2">&quot;1509&quot;</span><span class="p">,</span>
        <span class="nt">&quot;user_id&quot;</span><span class="p">:</span> <span class="s2">&quot;38&quot;</span><span class="p">,</span>
        <span class="nt">&quot;hostname&quot;</span><span class="p">:</span> <span class="s2">&quot;destiny.clavain.com&quot;</span><span class="p">,</span>
        <span class="nt">&quot;port&quot;</span><span class="p">:</span> <span class="s2">&quot;4949&quot;</span><span class="p">,</span>
        <span class="nt">&quot;query_interval&quot;</span><span class="p">:</span> <span class="s2">&quot;5&quot;</span><span class="p">,</span>
        <span class="nt">&quot;groupname&quot;</span><span class="p">:</span> <span class="s2">&quot;clavain&quot;</span><span class="p">,</span>
        <span class="nt">&quot;last_contact&quot;</span><span class="p">:</span> <span class="s2">&quot;2014-07-03 11:51:28&quot;</span><span class="p">,</span>
        <span class="nt">&quot;via_host&quot;</span><span class="p">:</span> <span class="s2">&quot;unset&quot;</span>
    <span class="p">}</span>
<span class="p">]</span>
</pre></div>

<hr>
<h2 id="markdown-header-getnode"><a name="h2-getNode"></a>getNode</h2>
<p>Will return all plugin and graph definitions from a node. <strong>nodeid parameter is required</strong></p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=getNode&amp;nodeid=1509</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span>
    <span class="nt">&quot;node&quot;</span><span class="p">:</span> <span class="p">{</span>
        <span class="nt">&quot;id&quot;</span><span class="p">:</span> <span class="s2">&quot;1509&quot;</span><span class="p">,</span>
        <span class="nt">&quot;user_id&quot;</span><span class="p">:</span> <span class="s2">&quot;38&quot;</span><span class="p">,</span>
        <span class="nt">&quot;hostname&quot;</span><span class="p">:</span> <span class="s2">&quot;destiny.clavain.com&quot;</span><span class="p">,</span>
        <span class="nt">&quot;port&quot;</span><span class="p">:</span> <span class="s2">&quot;4949&quot;</span><span class="p">,</span>
        <span class="nt">&quot;query_interval&quot;</span><span class="p">:</span> <span class="s2">&quot;5&quot;</span><span class="p">,</span>
        <span class="nt">&quot;groupname&quot;</span><span class="p">:</span> <span class="s2">&quot;clavain&quot;</span><span class="p">,</span>
        <span class="nt">&quot;last_contact&quot;</span><span class="p">:</span> <span class="s2">&quot;2014-07-03 13:21:28&quot;</span><span class="p">,</span>
        <span class="nt">&quot;via_host&quot;</span><span class="p">:</span> <span class="s2">&quot;unset&quot;</span>
    <span class="p">},</span>
    <span class="nt">&quot;plugins&quot;</span><span class="p">:</span> <span class="p">[</span>
        <span class="p">{</span>
            <span class="nt">&quot;str_PluginName&quot;</span><span class="p">:</span> <span class="s2">&quot;cpu&quot;</span><span class="p">,</span>
            <span class="nt">&quot;str_PluginTitle&quot;</span><span class="p">:</span> <span class="s2">&quot;CPU usage&quot;</span><span class="p">,</span>
            <span class="nt">&quot;str_PluginInfo&quot;</span><span class="p">:</span> <span class="s2">&quot;This graph shows how CPU time is spent.&quot;</span><span class="p">,</span>
            <span class="nt">&quot;str_PluginCategory&quot;</span><span class="p">:</span> <span class="s2">&quot;system&quot;</span><span class="p">,</span>
            <span class="nt">&quot;str_PluginLabel&quot;</span><span class="p">:</span> <span class="s2">&quot;%&quot;</span><span class="p">,</span>
            <span class="nt">&quot;v_graphs&quot;</span><span class="p">:</span> <span class="p">[</span>
                <span class="p">{</span>
                    <span class="nt">&quot;str_GraphName&quot;</span><span class="p">:</span> <span class="s2">&quot;system&quot;</span><span class="p">,</span>
                    <span class="nt">&quot;str_GraphLabel&quot;</span><span class="p">:</span> <span class="s2">&quot;system&quot;</span><span class="p">,</span>
                    <span class="nt">&quot;str_GraphInfo&quot;</span><span class="p">:</span> <span class="s2">&quot;CPU time spent by the kernel in system activities&quot;</span><span class="p">,</span>
                    <span class="nt">&quot;str_GraphType&quot;</span><span class="p">:</span> <span class="s2">&quot;DERIVE&quot;</span><span class="p">,</span>
                    <span class="nt">&quot;str_GraphDraw&quot;</span><span class="p">:</span> <span class="s2">&quot;AREA&quot;</span><span class="p">,</span>
                    <span class="nt">&quot;b_isNegative&quot;</span><span class="p">:</span> <span class="kc">false</span><span class="p">,</span>
                    <span class="nt">&quot;bd_GraphValue&quot;</span><span class="p">:</span> <span class="mf">2.64</span><span class="p">,</span>
                    <span class="nt">&quot;bd_LastGraphValue&quot;</span><span class="p">:</span> <span class="mf">4.06</span><span class="p">,</span>
                    <span class="nt">&quot;bd_LastGraphValueCounter&quot;</span><span class="p">:</span> <span class="mi">48249528</span><span class="p">,</span>
                    <span class="nt">&quot;is_init&quot;</span><span class="p">:</span> <span class="kc">true</span><span class="p">,</span>
                    <span class="nt">&quot;i_lastGraphFetch&quot;</span><span class="p">:</span> <span class="mi">1404386479</span><span class="p">,</span>
                    <span class="nt">&quot;i_lastQueued&quot;</span><span class="p">:</span> <span class="mi">1404386479</span><span class="p">,</span>
                    <span class="nt">&quot;queryInterval&quot;</span><span class="p">:</span> <span class="mi">5</span>
                <span class="p">},</span>
                <span class="p">{</span>
                   <span class="err">..</span>
                 <span class="p">}</span>
              <span class="p">]</span>
        <span class="p">},</span>
        <span class="p">{</span>
            <span class="nt">&quot;str_PluginName&quot;</span><span class="p">:</span> <span class="s2">&quot;vmstat&quot;</span><span class="p">,</span>
            <span class="nt">&quot;str_PluginTitle&quot;</span><span class="p">:</span> <span class="s2">&quot;VMstat&quot;</span><span class="p">,</span>
            <span class="nt">&quot;str_PluginCategory&quot;</span><span class="p">:</span> <span class="s2">&quot;processes&quot;</span><span class="p">,</span>
            <span class="nt">&quot;str_PluginLabel&quot;</span><span class="p">:</span> <span class="s2">&quot;process states&quot;</span><span class="p">,</span>
            <span class="nt">&quot;v_graphs&quot;</span><span class="p">:</span> <span class="p">[</span>
                <span class="p">{</span>
                    <span class="nt">&quot;str_GraphName&quot;</span><span class="p">:</span> <span class="s2">&quot;wait&quot;</span><span class="p">,</span>
                    <span class="nt">&quot;str_GraphLabel&quot;</span><span class="p">:</span> <span class="s2">&quot;running&quot;</span><span class="p">,</span>
                    <span class="nt">&quot;str_GraphType&quot;</span><span class="p">:</span> <span class="s2">&quot;GAUGE&quot;</span><span class="p">,</span>
                    <span class="nt">&quot;b_isNegative&quot;</span><span class="p">:</span> <span class="kc">false</span><span class="p">,</span>
                    <span class="nt">&quot;bd_GraphValue&quot;</span><span class="p">:</span> <span class="mi">0</span><span class="p">,</span>
                    <span class="nt">&quot;bd_LastGraphValue&quot;</span><span class="p">:</span> <span class="mi">0</span><span class="p">,</span>
                    <span class="nt">&quot;bd_LastGraphValueCounter&quot;</span><span class="p">:</span> <span class="mi">0</span><span class="p">,</span>
                    <span class="nt">&quot;is_init&quot;</span><span class="p">:</span> <span class="kc">true</span><span class="p">,</span>
                    <span class="nt">&quot;i_lastGraphFetch&quot;</span><span class="p">:</span> <span class="mi">1404386488</span><span class="p">,</span>
                    <span class="nt">&quot;i_lastQueued&quot;</span><span class="p">:</span> <span class="mi">1404386488</span><span class="p">,</span>
                    <span class="nt">&quot;queryInterval&quot;</span><span class="p">:</span> <span class="mi">5</span>
                <span class="p">},</span>
                <span class="p">{</span>
                   <span class="err">..</span>
                <span class="p">}</span>      
            <span class="p">]</span>
        <span class="p">},</span>
        <span class="p">{</span>
           <span class="err">..</span>
        <span class="p">}</span>
    <span class="p">]</span>
<span class="p">}</span>
</pre></div>


<p>most fields in the response are self-explanatory. Some others:</p>
<ul>
<li>b_isNegative = is true when the graph delivers negative values, to be drawn negative on chart</li>
<li>bd_graphValue = the current value of this graph</li>
<li>bd_LastGraphValue = the value from the last graph fetch, required to calculate proper COUNT/DERIVE fields</li>
<li>bd_LastGraphValueCounter = used to calculate graphValue from 2 different fetches</li>
<li>is_init = true when this plugin is initialized. As example count/derive graphs only initialize after two runs</li>
<li>i_lastGraphFetch - unixtimestamp, last contact to munin node and fetch of graph values</li>
<li>i_lastQueued = unixtimestamp, time used for queuing it to the storage backend</li>
<li>queryInterval = interval in minutes in which we talk with the node</li>
</ul>
<p><strong>Values in this call will only refresh every QueryInterval based contact to the node. To fetch plugin values from the storage backend you need to issue a getChartData call</strong></p>

<hr>
<h2 id="markdown-header-getchartdata"><a name="h2-getChartData"></a>getChartData</h2>
<p>Will return graph values from the storage backend for a given plugin. <strong>nodeid and plugin parameter is required</strong></p>
<p>Parameters:</p>
<ul>
<li>nodeid (numeric, required)</li>
<li>plugin (string, required)</li>
<li>start (integer, unixtimestamp, optional)</li>
<li>end  (integer, unixtimestamp, optional)</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=getChartData&amp;nodeid=1509&amp;plugin=cpu&amp;start=1404395178</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span>
    <span class="nt">&quot;system&quot;</span><span class="p">:</span> <span class="p">[</span>
        <span class="p">{</span>
            <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404395179</span><span class="p">,</span>
            <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;3.07&quot;</span>
        <span class="p">}</span>
    <span class="p">],</span>
    <span class="nt">&quot;user&quot;</span><span class="p">:</span> <span class="p">[</span>
        <span class="p">{</span>
            <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404395179</span><span class="p">,</span>
            <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;25.44&quot;</span>
        <span class="p">}</span>
    <span class="p">],</span>
    <span class="nt">&quot;nice&quot;</span><span class="p">:</span> <span class="p">[</span>
        <span class="p">{</span>
            <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404395179</span><span class="p">,</span>
            <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;0.00&quot;</span>
        <span class="p">}</span>
    <span class="p">],</span>
    <span class="nt">&quot;idle&quot;</span><span class="p">:</span> <span class="p">[</span>
        <span class="p">{</span>
            <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404395179</span><span class="p">,</span>
            <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;759.17&quot;</span>
        <span class="p">}</span>
    <span class="p">],</span>
    <span class="nt">&quot;iowait&quot;</span><span class="p">:</span> <span class="p">[</span>
        <span class="p">{</span>
            <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404395179</span><span class="p">,</span>
            <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;7.96&quot;</span>
        <span class="p">}</span>
    <span class="p">],</span>
    <span class="nt">&quot;irq&quot;</span><span class="p">:</span> <span class="p">[</span>
        <span class="p">{</span>
            <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404395179</span><span class="p">,</span>
            <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;0.00&quot;</span>
        <span class="p">}</span>
    <span class="p">],</span>
    <span class="nt">&quot;softirq&quot;</span><span class="p">:</span> <span class="p">[</span>
        <span class="p">{</span>
            <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404395179</span><span class="p">,</span>
            <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;1.22&quot;</span>
        <span class="p">}</span>
    <span class="p">],</span>
    <span class="nt">&quot;steal&quot;</span><span class="p">:</span> <span class="p">[],</span>
    <span class="nt">&quot;guest&quot;</span><span class="p">:</span> <span class="p">[]</span>
<span class="p">}</span>
</pre></div>


<p>By default if no start and end parameter is given this call will return data from the 30 days. You can specify a range by adding start and end as unix timestamp. If you only specify start it will use the current time as end range.</p>
<p>For label definitions of the single graph items per plugin you can use getNode</p>

<hr>
<h2 id="markdown-header-listbuckets"><a name="h2-listBuckets"></a>listBuckets</h2>
<p>returns a list of all your bucket stats</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=listBuckets</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">[</span>
    <span class="p">{</span>
        <span class="nt">&quot;id&quot;</span><span class="p">:</span> <span class="s2">&quot;3&quot;</span><span class="p">,</span>
        <span class="nt">&quot;user_id&quot;</span><span class="p">:</span> <span class="s2">&quot;1&quot;</span><span class="p">,</span>
        <span class="nt">&quot;statname&quot;</span><span class="p">:</span> <span class="s2">&quot;Service Calls / Hour&quot;</span><span class="p">,</span>
        <span class="nt">&quot;statlabel&quot;</span><span class="p">:</span> <span class="s2">&quot;Calls&quot;</span><span class="p">,</span>
        <span class="nt">&quot;created_at&quot;</span><span class="p">:</span> <span class="s2">&quot;2014-06-10 10:53:12&quot;</span><span class="p">,</span>
        <span class="nt">&quot;groupname&quot;</span><span class="p">:</span> <span class="s2">&quot;&quot;</span><span class="p">,</span>
        <span class="nt">&quot;statid&quot;</span><span class="p">:</span> <span class="s2">&quot;2462fbcdxerw4ffea1bb4b879de5be66dd9&quot;</span><span class="p">,</span>
        <span class="nt">&quot;username&quot;</span><span class="p">:</span> <span class="s2">&quot;apitest&quot;</span>
    <span class="p">}</span>
<span class="p">]</span>
</pre></div>

<hr>
<h2 id="markdown-header-getbucket"><a name="h2-getBucket"></a>getBucket</h2>
<p>receive a single bucket. <strong>Required Parameter: bucketid (numeric)
</strong></p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=getBucket&amp;bucketid=4</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;id&quot;</span><span class="p">:</span><span class="s2">&quot;4&quot;</span><span class="p">,</span><span class="nt">&quot;user_id&quot;</span><span class="p">:</span><span class="s2">&quot;1&quot;</span><span class="p">,</span><span class="nt">&quot;statname&quot;</span><span class="p">:</span><span class="s2">&quot;Tickets created \/ Hour&quot;</span><span class="p">,</span><span class="nt">&quot;statlabel&quot;</span><span class="p">:</span><span class="s2">&quot;Tickets&quot;</span><span class="p">,</span><span class="nt">&quot;created_at&quot;</span><span class="p">:</span><span class="s2">&quot;2014-06-10 13:14:36&quot;</span><span class="p">,</span><span class="nt">&quot;groupname&quot;</span><span class="p">:</span><span class="s2">&quot;&quot;</span><span class="p">,</span><span class="nt">&quot;statid&quot;</span><span class="p">:</span><span class="s2">&quot;22cxxxxx371572ca890f940964&quot;</span><span class="p">}</span>
</pre></div>

<hr>
<h2 id="markdown-header-getbucketdata"><a name="h2-getBucketData"></a>getBucketData</h2>
<p>returns data from the storage backend for that bucket. **Required Parameter: bucketid (numeric)</p>
<p>By default will only return the last 30/31 days. You can add <strong>start</strong> and <strong>end</strong> parameter (numeric) with a unixtimestamp for better range results.</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=getBucketData&amp;bucketid=4&amp;start=1404463401</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">[</span>
    <span class="p">{</span>
        <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;3&quot;</span><span class="p">,</span>
        <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404464401</span>
    <span class="p">},</span>
    <span class="p">{</span>
        <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;5&quot;</span><span class="p">,</span>
        <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404468001</span>
    <span class="p">},</span>
    <span class="p">{</span>
        <span class="nt">&quot;value&quot;</span><span class="p">:</span> <span class="s2">&quot;3&quot;</span><span class="p">,</span>
        <span class="nt">&quot;timestamp&quot;</span><span class="p">:</span> <span class="mi">1404471601</span>
    <span class="p">}</span>
<span class="p">]</span>
</pre></div>

<hr>
<h2 id="markdown-header-listgroups"><a name="h2-listGroups"></a>listGroups</h2>
<p>return all groups from nodes</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=listGroups</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">[{</span><span class="nt">&quot;group&quot;</span><span class="p">:</span><span class="s2">&quot;clavain&quot;</span><span class="p">,</span><span class="nt">&quot;nodes&quot;</span><span class="p">:</span><span class="mi">1</span><span class="p">}]</span>
</pre></div>

<hr>
<h2 id="markdown-header-listnodesbygroup"><a name="h2-listNodesByGroup"></a>listNodesByGroup</h2>
<p>return all nodes from a given group. <strong>group parameter (string) is required</strong></p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=listNodesByGroup&amp;group=clavain</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">[</span>
    <span class="p">{</span>
        <span class="nt">&quot;id&quot;</span><span class="p">:</span> <span class="s2">&quot;1509&quot;</span><span class="p">,</span>
        <span class="nt">&quot;user_id&quot;</span><span class="p">:</span> <span class="s2">&quot;38&quot;</span><span class="p">,</span>
        <span class="nt">&quot;hostname&quot;</span><span class="p">:</span> <span class="s2">&quot;destiny.clavain.com&quot;</span><span class="p">,</span>
        <span class="nt">&quot;port&quot;</span><span class="p">:</span> <span class="s2">&quot;4949&quot;</span><span class="p">,</span>
        <span class="nt">&quot;query_interval&quot;</span><span class="p">:</span> <span class="s2">&quot;5&quot;</span><span class="p">,</span>
        <span class="nt">&quot;groupname&quot;</span><span class="p">:</span> <span class="s2">&quot;clavain&quot;</span><span class="p">,</span>
        <span class="nt">&quot;last_contact&quot;</span><span class="p">:</span> <span class="s2">&quot;2014-07-04 15:19:23&quot;</span><span class="p">,</span>
        <span class="nt">&quot;via_host&quot;</span><span class="p">:</span> <span class="s2">&quot;unset&quot;</span>
    <span class="p">}</span>
<span class="p">]</span>
</pre></div>

<hr>
<h2 id="markdown-header-addbucket"><a name="h2-addBucket"></a>addBucket</h2>
<p>create a new bucketstat</p>
<p>graphname and graphlabel parameters are required. groupname parameter is optional.</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=addBucket&amp;graphname=People%20in%20Room&amp;graphlabel=people</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;statid&quot;</span><span class="p">:</span><span class="s2">&quot;9704988a5bf1e56e437176d150bff3b9083b4e9e&quot;</span><span class="p">,</span><span class="nt">&quot;statlabel&quot;</span><span class="p">:</span><span class="s2">&quot;people&quot;</span><span class="p">,</span><span class="nt">&quot;statname&quot;</span><span class="p">:</span><span class="s2">&quot;People in Room&quot;</span><span class="p">,</span><span class="nt">&quot;groupname&quot;</span><span class="p">:</span><span class="s2">&quot;&quot;</span><span class="p">}</span>
</pre></div>

<hr>
<h2 id="markdown-header-editbucket"><a name="h2-editBucket"></a>editBucket</h2>
<p>edit a buckets name, label.</p>
<p>bucketid, graphname and graphlabel are required. groupname is optional.</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=editBucket&amp;bucketid=11&amp;graphname=humans%20in%20room&amp;graphlabel=humans</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;id&quot;</span><span class="p">:</span><span class="s2">&quot;11&quot;</span><span class="p">,</span><span class="nt">&quot;user_id&quot;</span><span class="p">:</span><span class="s2">&quot;38&quot;</span><span class="p">,</span><span class="nt">&quot;statname&quot;</span><span class="p">:</span><span class="s2">&quot;humans in room&quot;</span><span class="p">,</span><span class="nt">&quot;statlabel&quot;</span><span class="p">:</span><span class="s2">&quot;humans&quot;</span><span class="p">,</span><span class="nt">&quot;created_at&quot;</span><span class="p">:</span><span class="s2">&quot;2014-07-07 12:26:28&quot;</span><span class="p">,</span><span class="nt">&quot;groupname&quot;</span><span class="p">:</span><span class="s2">&quot;&quot;</span><span class="p">,</span><span class="nt">&quot;statid&quot;</span><span class="p">:</span><span class="s2">&quot;9704988a5bf1e56e437176d150bff3b9083b4e9e&quot;</span><span class="p">}</span>
</pre></div>

<hr>
<h2 id="markdown-header-deletebucket"><a name="h2-deleteBucket"></a>deleteBucket</h2>
<p>delete a bucketstat.</p>
<p>bucketid (numeric) is required</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=deleteBucket&amp;bucketid=11</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;status&quot;</span><span class="p">:</span><span class="s2">&quot;ok&quot;</span><span class="p">}</span>
</pre></div>


<h2 id="markdown-header-reloadplugins"><a name="h2-reloadPlugins"></a>reloadPlugins</h2>
<p>will try to reload plugins for the given node. This is useful if you added a new munin plugin. Plugins are only refreshed for the cache once per day if you not refresh yourself.</p>
<p>nodeid parameter (numeric) is reuqired.</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=reloadPlugins&amp;nodeid=1509</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;status&quot;</span><span class="p">:</span><span class="s2">&quot;ok&quot;</span><span class="p">}</span>
</pre></div>

<hr>
<h2 id="markdown-header-addnode"><a name="h2-addNode"></a>addNode</h2>
<p>add a new munin-node for monitoring.</p>
<p>Required Parameters:</p>
<ul>
<li>hostname - string, hostname of the machine</li>
<li>port - integer, port for munin-node connection</li>
<li>interval - integer, 1,5,10 or 15  . The interval in minutes for receiving graphs</li>
</ul>
<p>Optional Parameters:</p>
<ul>
<li>groupname - string, optional groupname</li>
<li>viahost - contact this node via another munin host. (must be hostname) required for some snmp plugins. </li>
<li>authpw - string, required if you use muninmxauth plugin, only allow connection to munin node with proper password </li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=addNode&amp;hostname=clavain.com&amp;port=4949&amp;interval=10</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;id&quot;</span><span class="p">:</span><span class="s2">&quot;1512&quot;</span><span class="p">,</span><span class="nt">&quot;user_id&quot;</span><span class="p">:</span><span class="s2">&quot;38&quot;</span><span class="p">,</span><span class="nt">&quot;hostname&quot;</span><span class="p">:</span><span class="s2">&quot;clavain.com&quot;</span><span class="p">,</span><span class="nt">&quot;port&quot;</span><span class="p">:</span><span class="s2">&quot;4949&quot;</span><span class="p">,</span><span class="nt">&quot;query_interval&quot;</span><span class="p">:</span><span class="s2">&quot;10&quot;</span><span class="p">,</span><span class="nt">&quot;groupname&quot;</span><span class="p">:</span><span class="s2">&quot;&quot;</span><span class="p">,</span><span class="nt">&quot;last_contact&quot;</span><span class="p">:</span><span class="kc">null</span><span class="p">,</span><span class="nt">&quot;via_host&quot;</span><span class="p">:</span><span class="s2">&quot;unset&quot;</span><span class="p">}</span>
</pre></div>


<h2 id="markdown-header-deletenode"><a name="h2-deleteNode"></a>deleteNode</h2>
<p>delete a node from the system. dequeue from collector, remove plugin cache and delete all associated graph data.</p>
<p>nodeid parameter is required.</p>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&amp;method=deleteNode&amp;nodeid=1509</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;status&quot;</span><span class="p">:</span><span class="s2">&quot;ok&quot;</span><span class="p">}</span>
</pre></div>

<hr>
<h2><a name="h2-editNode"></a>editNode</h2>
<p>all parameters optional and required from addNode are required. <strong>You also need to specify the nodeid (numeric) parameter</strong>:</p>
<p>Required Parameters:</p>
<ul>
<li>hostname - string, hostname of the machine</li>
<li>port - integer, port for munin-node connection</li>
<li>interval - integer, 1,5,10 or 15  . The interval in minutes for receiving graphs</li>
</ul>
<p>Optional Parameters:</p>
<ul>
<li>groupname - string, optional groupname</li>
<li>viahost - contact this node via another munin host. required for some snmp plugins. </li>
<li>authpw - string, required if you use muninmxauth plugin, only allow connection to munin node with proper password </li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=editNode&amp;nodeid=1509&amp;hostname=destiny.clavain.com&amp;port=4949&amp;interval=5&amp;groupname=clavain</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre><span class="p">{</span><span class="nt">&quot;status&quot;</span><span class="p">:</span><span class="s2">&quot;ok&quot;</span><span class="p">,</span><span class="nt">&quot;msg&quot;</span><span class="p">:</span><span class="s2">&quot;Node updated and requeued&quot;</span><span class="p">}</span>
</pre></div>

</section>

<hr>
<h2><a name="h2-packageList"></a>packageList</h2>
<p>Returns a list of all tracked packages.</p>

<p>Optional Parameters</p>
<ul>
<li>node - numeric, id of a node for single packagelist of a selected node</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=packageList</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre>
	{
    "packages": [
        {
            "package": {
                "name": "accountsservice 0.6.15-2ubuntu9.7",
                "pcount": 328,
                "affected_nodes": "1,2,3,4,5,6,7,21,22,400,402,403,404,408,439,449,459,470,472,482,491,500,508,510,511,513,520,526,530,534,539,550,551,554,556,566,576,577,583,589,593,599,603,610,613,615,624,630,631,639,644,646,647,648,650,660,661,662,664,665,670,672,673,677,685,689,693,702,703,708,715,718,726,728,733,736,743,751,752,760,761,764,766,769,771,778,783,788,795,797,800,820,825,826,828,829,830,844,845,846,855,856,859,868,869,870,872,873,874,879,890,897,903,904,906,913,915,918,922,925,933,941,942,946,954,956,959,961,968,969,970,971,977,982,985,986,988,991,996,1007,1012,1015,1025,1026,1029,1030,1032,1035,1039,1053,1056,1062,1067,1069,1072,1079,1087,1090,1101,1102,1108,1110,1112,1116,1136,1139,1144,1151,1156,1162,1173,1176,1181,1188,1193,1201,1202,1208,1210,1221,1224,1227,1231,1239,1252,1253,1254,1257,1260,1270,1275,1278,1280,1281,1283,1285,1286,1287,1299,1302,1305,1306,1309,1313,1314,1315,1318,1321,1322,1324,1326,1330,1331,1332,1335,1336,1337,1338,1339,1340,1352,1353,1386,1387,1390,1391,1392,1393,1394,1396,1397,1398,1399,1400,1401,1402,1403,1404,1405,1406,1407,1408,1409,1410,1411,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1426,1427,1428,1429,1430,1431,1432,1433,1434,1435,1436,1437,1438,1439,1440,1441,1442,1443,1444,1445,1446,1447,1448,1449,1450,1451,1452,1453,1454,1455,1457,1458,1459,1460,1461,1462,1463,1464,1465,1466,1515,1517,1523,1524,1529,1530,1531,1532,1533,1534,1562,1563,1564,1565,1566,1567,1568,1576,1577,1578,1579,1580,1590,1598,1599,1600,1603,1604,1606,1610"
            }
        },
        {
            "package": {
                "name": "acl 2.2.51-5ubuntu1",
                "pcount": 27,
                "affected_nodes": "1,2,21,22,400,408,535,662,707,807,816,870,968,1056,1278,1322,1390,1393,1396,1403,1406,1409,1424,1443,1457,1459,1460,1465"
            }
        }
    ]
    }
</pre></div>

<hr>
<h2><a name="h2-addEvent"></a>addEvent</h2>
<p>Add a event to graphs.</p>

<p>Required Parameters</p>
<ul>
<li>node OR group - for node, hostname or id of a node. For group a munin group</li>
<li>event_title - msg for the event</li>
</ul>
</ul>

<p>Optional Parameters</p>
<ul>
<li>event_start - unix timestamp</li>
<li>event_end - unix timestamp</li>
<li>color - orange, green, red, blue etc. red is default</li>
<li>update - true, can be used to execute a graph update on event save. only works with node, not group</li>
</ul>
</ul>

<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=addEvent&event_title=Nagios Alert&color=red&node=app01-myserver.example.com</span>
</pre></div>


<p>Example Response:</p>
<div class="codehilite"><pre>
{
    "id": "5",
    "user_id": "1",
    "event_start": "1412230117",
    "event_end": "1412237317",
    "event_title": "Nagios ACK - WARNING - (WARN - /dev/sda1 - 81 - 9282859008)",
    "group": "",
    "color": "orange",
    "node": "42"
}
</pre></div>


<hr>
<h2><a name="h2-addCheck"></a>addCheck</h2>
<p>Add a check.</p>

<p>Required Parameters</p>
<ul>
<li>checkname - descriptive name (STRING)</li>
<li>interval - check intervall in minutes (NUMBER STRING)</li>
<li>notifydown - notify when down longer than xx minutes (NUMBER STRING)</li>
<li>notifyagain - repeat notification every xx check cycles when still down (NUMBER STRING)</li>
<li>notifyifup - send up notification (NUMBER STRING boolean "1"/"0")</li>
<li>checktype - id of the check to perform (NUMBER STRING)
	<ul>
		<li>PING (1)
			<ul>
			<li>nonearg - hostname/ipaddress (STRING)</li>
			</ul>
		</li>
		<li>HTTP(S) (2)
			<ul>
			<li>uri - hostname/ipaddress (STRING)</li>
			</ul>
		</li>
		<li>TCP (3)/ UDP (4)
			<ul>
			<li>param[H] - hostname/ipaddress (STRING)</li>
			<li>param[p] - port (NUMBER STRING)
			</ul>
		</li>
		<li>SSL CERTIFICATE EXPIRY (5)
			<ul>
			<li>param[c] - [hostname]:[port] (STRING)</li>
			<li>param[D] - alert when certificate expires in xx days (NUMBER STRING)</li>
			</ul>
		</li>
		</ul>
</li>
</ul>

<p>Optional Parameters</p>
<ul>
<li>contacts - select contacts for notifications. Leave empty if you do not want to send notifications. (CSV NUMBER STRING of contactIds)</li>
<li>accessgroup - will give Users with the assigned access group Read-Only access to this service check (STRING group name)</li>
<li>notifyflap - ?? notify if flapping (NUMBER STRING boolean "1"/"0")</li>
<li>checktype specific optional parameters
	<ul>
		<li>HTTP(S) (2)
			<ul>
			<li>user - auth username (STRING)</li>
			<li>pass - auth password (STRING)</li>
			<li>param[e] - expect string (STRING)</li>
			<li>param[r] - string search page (STRING)</li>
			<li>param[A] - user-agent (STRING)</li>
			<li>param[f] - what to do if a redirect happens  (STRING)
				<ul>
				 <li>"ok" = do nothing</li>
				 <li>"critical" = trigger error</li>
				 <li>"follow" = follow redirect</li>
				 <li>"sticky" = follow, but stick to ip)</li>
				 </ul>
			</li>
			<li>param[t] - timeout in seconds (NUMBER STRING)</li>
			<li>param[c] - critical loading time in seconds (NUMBER STRING)</li>
			</ul>
		</li>
		<li>TCP (3)/ UDP (4)
			<ul>
			<li>param[s] - send string (STRING)</li>
			<li>param[e] - expect string (STRING)</li>
			<li>param[S] - use SSL (NUMBER STRING boolean "1"/"0")</li>
			</ul>
		</li>
		<li>SSL CERTIFICATE EXPIRY (5)
			<ul>
			<li>param[c] - [hostname]:[port] (STRING)</li>
			<li>param[D] - alert when certificate expires in xx days (NUMBER STRING)</li>
			</ul>
		</li>
		</ul>
</li>
</ul>

<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=addCheck&checkname=apitest1&checktype=1&interval=10&tags=apitag1,apitag2&notifydown=10&notifyagain=1&notifyifup=1&nonearg=127.0.0.1</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=addCheck' \
\
         -d 'checkname=apitest1' \
         -d 'checktype=1' \
         -d 'interval=10' \
         -d 'tags=apitag1,apitag2' \
         -d 'notifydown=10' \
         -d 'notifyagain=1' \
         -d 'notifyifup=1' \
         -d 'nonearg=127.0.0.1' \
         -d 'contacts=999999' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
{
	"id":27,
	"check_name":"PING",
	"executable":"check_icmp",
	"status":"ok",
	"msg":"Check added."
}
</pre></div>




<hr>
<h2><a name="h2-listChecks"></a>listChecks</h2>
<p>
	Returns a list of all checks that are accessible for the current user.
</p>

<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=listChecks</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=listChecks' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"checks":[
			{
				"id":"2",
				"user_id":"1",
				"check_type":"3",
				"check_name":"test.example.com",
				"cinterval":"5",
				"is_active":"1",
				"locations":"",
				"json":"{\"checkname\":\"test.example.com\",\"interval\":\"5\",\"tags\":\"ldap\",\"accessgroup\":\"Test-Server\",\"checktype\":\"3\",\"param\":[\"-H|##|test.example.com\",\"-p|##|389\"],\"contacts\":[\"3\"],\"notifydown\":\"5\",\"notifyagain\":\"0\",\"notifyflap\":\"0\",\"notifyifup\":\"1\",\"command\":\"check_tcp\",\"user_id\":\"2\"}",
				"luptime":"N\/A",
				"accessgroup":"Test-Server",
				"check_desc_name":"TCP",
				"username":"admin"
			},
			...
		],
		"status":"ok"
	}
</pre></div>





<hr>
<h2><a name="h2-listChecksByName"></a>listChecksByName</h2>
<p>
	Returns a list of all checks starting with the given name fragment.<br/>
	Checks that are not accessible for the current user are not included in the result list.
</p>

<p>Mandatory Parameters</p>
<ul>
<li>name - string, prefix for a check name</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=listChecksByName&name=test</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=listChecksByName' \
\
         -d 'name=test' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"checks":[
			{
				"id":"2",
				"user_id":"1",
				"check_type":"3",
				"check_name":"test.example.com",
				"cinterval":"5",
				"is_active":"1",
				"locations":"",
				"json":"{\"checkname\":\"test.example.com\",\"interval\":\"5\",\"tags\":\"ldap\",\"accessgroup\":\"Test-Server\",\"checktype\":\"3\",\"param\":[\"-H|##|test.example.com\",\"-p|##|389\"],\"contacts\":[\"3\"],\"notifydown\":\"5\",\"notifyagain\":\"0\",\"notifyflap\":\"0\",\"notifyifup\":\"1\",\"command\":\"check_tcp\",\"user_id\":\"2\"}",
				"luptime":"N\/A",
				"accessgroup":"Test-Server",
				"check_desc_name":"TCP",
				"username":"admin"
			}
		],
		"status":"ok"
	}
</pre></div>




<hr>
<h2><a name="h2-deleteCheck"></a>deleteCheck</h2>
<p>
	Deletes the check with the given checkId.<br/>
	Checks that are not accessible for the current user cannot be deleted.
</p>

<p>Mandatory Parameters</p>
<ul>
<li>checkid - numeric, id of a check</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=deleteCheck&checkid=99999</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=deleteCheck' \
\
         -d 'checkid=99999' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"status":"ok"
	}
</pre></div>






<hr>
<h2><a name="h2-addAlert"></a>addAlert</h2>
<p>
	Adds a new alert.<br/>
	You can only add alerts to nodes you are allowed access to.<br/>
	You can only add contacts you are allowed access to.
</p>

<p>Mandatory Parameters</p>
<ul>
<li>nodeid - numeric, id of the node the alert should be added to</li>
<li>pluginname - string, name of the munin plugin</li>
<li>graphname - string, name of the munin graph</li>
<li>raisevalue - numeric, alert if this value is met with the alert condition</li>
<li>condition - string, one of</li>
	<ul>
		<li>eq - equal</li>
		<li>gt - greater than</li>
		<li>lt - less than</li>
		<li>gtavg - greater than average</li>
		<li>ltavg - less than average</li>
	</ul>
<li>samples - numeric, number of samples/munin-runs for average calculation (only when condition is one of gtavg or ltavg)</li>
<li>limit - numeric, a time to wait before resending an alert in case the condition still matches (minutes)</li>
<li>contacts - numeric (csv), a comma-seperated list of contact ids to be notified</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=addAlert&nodeid=99999&pluginname=load&graphname=load&raisevalue=8&condition=gtavg&samples=15&limit=120&contacts=999,888</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=addAlert' \
\
         -d 'nodeid=99999' \
         -d 'pluginname=load' \
         -d 'graphname=load' \
         -d 'raisevalue=8' \
         -d 'condition=gtavg' \
         -d 'samples=15' \
         -d 'limit=120' \
         -d 'contacts=999,888' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"status":"ok",
		"msg":"Alert stored and added to running configuration.",
		"id":520
	}
</pre></div>





<hr>
<h2><a name="h2-deleteAlert"></a>deleteAlert</h2>
<p>
	Deletes an alert.
</p>

<p>Mandatory Parameters</p>
<ul>
<li>alertid - numeric, id of the alert</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=deleteAlert&alertid=999999</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=deleteAlert' \
\
         -d 'alertid=999999' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"status":"ok",
		"msg": "Alert removed and purged from running configuration."
	}
</pre></div>






<hr>
<h2><a name="h2-listAlertsByNode"></a>listAlertsByNode</h2>
<p>
	Returns a list of all configured alerts for the given node.<br/>
	Alerts that are not accessible for the current user are not returned.
</p>

<p>Mandatory Parameters</p>
<ul>
<li>nodeid - numeric, id of the node</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=listAlertsByNode&nodeid=999999</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=listAlertsByNode' \
\
         -d 'nodeid=999999' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	[
		{
			"id":"117",
			"user_id":"2",
			"node_id":"113",
			"pluginname":"df",
			"graphname":"_dev_mapper_system_root",
			"raise_value":"95",
			"condition":"gt",
			"alert_limit":"1440",
			"num_samples":"2",
			"hostname":"test.example.com"
		},
		{
			"id":"353",
			"user_id":"2",
			"node_id":"113",
			"pluginname":"load",
			"graphname":"load",
			"raise_value":"4",
			"condition":"gtavg",
			"alert_limit":"120",
			"num_samples":"15",
			"hostname":"test.example.com"
		},
		...
	]
</pre></div>



<hr>
<h2><a name="h2-getAlert"></a>getAlert</h2>
<p>
	Returns a map for the requested alert data.<br/>
	Alerts that are not accessible for the current user are not returned.
</p>

<p>Mandatory Parameters</p>
<ul>
<li>alertid - numeric, id of the alert</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=getAlert&alertid=999999</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=getAlert' \
\
         -d 'alertid=999999' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"id":"999999",
		"user_id":"2",
		"node_id":"252",
		"pluginname":"load",
		"graphname":"load",
		"raise_value":"12",
		"condition":"gtavg",
		"alert_limit":"120",
		"num_samples":"15",
		"hostname":"test.example.com"
	}
</pre></div>



<hr>
<h2><a name="h2-listContacts"></a>listContacts</h2>
<p>
	Returns a list of contact entries.<br/>
	Contacts that are not accessible for the current user are not returned.
</p>

<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=listContacts</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=listContacts' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	[
		{
			"id":"1",
			"contact_name":"Test User",
			"contact_email":"test.user@example.com",
			"contact_username":"",
			"contact_password":"",
			"contact_mobile_nr":"",
			"contact_code":"",
			"contact_callback":"",
			"contact_type":"basic",
			"user_id":"2",
			"callback_active":"0",
			"email_active":"0",
			"sms_active":"0",
			"tts_active":"0",
			"pushover_active":"1",
			"app_active":"0",
			"pushover_key":"abcdefghijklmnopqrstuvwxyz",
			"s_mon":"08:00;22:00",
			"s_tue":"08:00;22:00",
			"s_wed":"08:00;22:00",
			"s_thu":"08:00;22:00",
			"s_fri":"08:00;22:00",
			"s_sat":"10:00;20:00",
			"s_sun":"10:00;20:00",
			"timezone":"Europe\/Berlin"
		},
		...
	]
</pre></div>



<hr>
<h2><a name="h2-addContact"></a>addContact</h2>
<p>
	Adds a contact entry.
</p>

<p>Mandatory Parameters</p>
<ul>
	<li>contact_name - string, full name for the contact</li>
	<li>contact_email - string, email address for the contact</li>
</ul>

<p>Optional Parameters</p>
<ul>
	<li>contact_mobile_nr - string, mobile phone number</li>
	<ul>
		<li>sms_active - boolean, activate sms notification (needs contact_mobile_nr)</li>
		<li>tts_active - boolean, activate text-to-speech notification (needs contact_mobile_nr)</li>
	</ul>
	<li>contact_callback - string, callback URL</li>
	<ul>
		<li>callback_active - boolean, activate callback notification (needs contact_callback)</li>
	</ul>
	<li>pushover_key - string, pushover key</li>
		<ul>
		<li>pushover_active - boolean, activate pushover notification (needs pushover_key)</li>
	</ul>
	<li>timezone - string, defaults to 'Europe/Berlin'</li>
</ul>


<p>Optional Notification Schedule</p>
<p>
	For every weekday notifications can be disabled completely or configured for a specific time-period.<br/>
	If omitted the schedule defaults to notifications 24/7.
</p>
<ul>
	<li>monday - default: enabled at 00:00-24:00</li>
	<ul>
		<li>s_mon_none - boolean, 'true' means 'no notifications'</li>
		<li>s_mon_from - string, start time for notifications in 24 hour format</li>
		<li>s_mon_to - string, end time for notifications in 24 hour format</li>
	</ul>
	<li>tuesday - default: enabled at 00:00-24:00</li>
	<ul>
		<li>s_tue_none - boolean, 'true' means 'no notifications'</li>
		<li>s_tue_from - string, start time for notifications in 24 hour format</li>
		<li>s_tue_to - string, end time for notifications in 24 hour format</li>
	</ul>
	<li>wednesday - default: enabled at 00:00-24:00</li>
	<ul>
		<li>s_wed_none - boolean, 'true' means 'no notifications'</li>
		<li>s_wed_from - string, start time for notifications in 24 hour format</li>
		<li>s_wed_to - string, end time for notifications in 24 hour format</li>
	</ul>
	<li>thursday - default: enabled at 00:00-24:00</li>
	<ul>
		<li>s_thu_none - boolean, 'true' means 'no notifications'</li>
		<li>s_thu_from - string, start time for notifications in 24 hour format</li>
		<li>s_thu_to - string, end time for notifications in 24 hour format</li>
	</ul>
	<li>friday - default: enabled at 00:00-24:00</li>
	<ul>
		<li>s_fri_none - boolean, 'true' means 'no notifications'</li>
		<li>s_fri_from - string, start time for notifications in 24 hour format</li>
		<li>s_fri_to - string, end time for notifications in 24 hour format</li>
	</ul>
	<li>saturday - default: enabled at 00:00-24:00</li>
	<ul>
		<li>s_sat_none - boolean, 'true' means 'no notifications'</li>
		<li>s_sat_from - string, start time for notifications in 24 hour format</li>
		<li>s_sat_to - string, end time for notifications in 24 hour format</li>
	</ul>
	<li>sunday - default: enabled at 00:00-24:00</li>
	<ul>
		<li>s_sun_none - boolean, 'true' means 'no notifications'</li>
		<li>s_sun_from - string, start time for notifications in 24 hour format</li>
		<li>s_sun_to - string, end time for notifications in 24 hour format</li>
	</ul>
</ul>

<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=addContact&contact_name=Test User&contact_email=test@example.com</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=addContact' \
\
         -d 'contact_name=Test User' \
         -d 'contact_email=test@example.com' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"status":"ok",
		"message":"Contact 'Test User' created.",
		"id":8
	}
</pre></div>






<hr>
<h2><a name="h2-deleteContact"></a>deleteContact</h2>
<p>
	Deletes the contact with the given contactid.<br/>
	Contacts that are not accessible for the current user are not deleted.
</p>

<p>Mandatory Parameters</p>
<ul>
<li>contactid - numeric, id of the contact</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=deleteContact&contactid=999999</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=deleteContact' \
\
         -d 'contactid=999999' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"status":"ok",
		"message":"Contact with contactid 999999 deleted."
	}
</pre></div>







<hr>
<h2><a name="h2-addAlertContact"></a>addAlertContact</h2>
<p>
	Adds a notification contact to an alert.<br/>
	Only alerts/contacts that are accessible by the current user are accessable.
</p>

<p>Mandatory Parameters</p>
<ul>
<li>alertid - numeric, id of the alert</li>
<li>contactid - numeric, id of the contact</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=addAlertContact&alertid=999999&contactid=999999</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=addAlertContact' \
\
         -d 'alertid=999999' \
         -d 'contactid=999999' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"status":"ok",
		"message":"Alert notification contact added and running configuration updated."
	}
</pre></div>
   
   
   
   
   
<hr>
<h2><a name="h2-deleteAlertContact"></a>deleteAlertContact</h2>
<p>
	Deletes a notification contact from an alert.<br/>
	Only alerts/contacts that are accessible by the current user are accessable.
</p>

<p>Mandatory Parameters</p>
<ul>
<li>alertid - numeric, id of the alert</li>
<li>contactid - numeric, id of the contact</li>
</ul>
<p>Example Request:</p>
<div class="codehilite"><pre><span class="x"><?php echo BASEURL; ?>/api.php?key=<?php echo $user->apikey?>&method=deleteAlertContact&alertid=999999&contactid=999999</span>
</pre></div>

<div class="codehilite"><pre><span class="x">curl -i -X POST \
         -d 'key=<?php echo $user->apikey?>' \
         -d 'method=deleteAlertContact' \
\
         -d 'alertid=999999' \
         -d 'contactid=999999' \
<?php echo BASEURL; ?>/api.php ; echo</span>
</pre></div>

<p>Example Response:</p>
<div class="codehilite"><pre>
	{
		"status":"ok",
		"message":"Alert notification contact removed and running configuration updated."
	}
</pre></div>
   
   
    </section>
   

									
									</div>
								</div>
							</div>
						</article>
				</div>
				<!-- end row -->


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
	</body>

</html>