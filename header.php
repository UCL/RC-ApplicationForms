<!DOCTYPE html>
<?php if (! isset($page_title)) { $page_title = ""; }; ?>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $page_title;?></title>

<link rel="shortcut icon" href="resources/favicon2.ico" />
<link rel="stylesheet" href="resources/default.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="resources/isd.css" media="screen" />
<link rel="stylesheet" type="text/css" href="resources/isd-silva-doc-styles.css" />
<link rel="stylesheet" type="text/css" href="resources/isd-staff.css" />
<link rel="stylesheet" type="text/css" href="resources/local-styles.css" />

<style type="text/css">

p.note {
  color:#555;
  font-style:italic;
}

p.secttitle {
  font-weight:bold;
}

p.draftnote {
  color:#f0f;
  border-color:#f0f;
  border-style:dashed;
  border-width:2px;
}

div.subsection {
}

</style>

</head>
<body>
    <div id="isd-container">
		<p id="skip">
			<a href="#leftcontent">
				<img alt="Skip to site navigation"
         src="resources/skip.gif" />
			</a>
		</p>
		<!-- Start banner and breadcrumbs --> 
		
			<!--Start Top banner-->
<div id="banner"> 
	<div id="tb-black" style="height: 110px;">  
		<div id="section_heading"> 
			<div id="section_head">
				<span id="section_header_white"
          style="text-transform:uppercase;">Research IT Services</span>
				<br /> 
				<span id="section_subheader_white"
          style="text-transform:capitalize;"></span>
			</div> 
		</div> 
		<div id="logo_holder"> 
			<div id="logo">
				<a href="/"><img src="resources/ucl0112.gif"
                     alt="UCL Home" /></a>
			</div> 
		</div> 
	</div> 
</div>
<!--End Top banner-->

			<!-- Begin bradcrumbs row -->
<div id="ucl0112"> 
	<!--Start Search box-->
	<div id="search"> 
        <form action="http://search2.ucl.ac.uk/search/search.cgi" method="get" id="googlesearch">
            <input placeholder="Search UCL" name="query" id="query" type="text" value="" accesskey="q" value="Search UCL">
            <input type="hidden" name="collection" value="ucl-public-meta">
            <!--input type="submit" value="GO" class="submit" name="Submit"-->
        </form>
		<!--form name="googlesearch" id="googlesearch" method="get" action="http://search.ucl.ac.uk/search">
                <label for="q"></label>
                <input type="text" name="q" id="q" value="Search UCL" tabindex="1" accesskey="q" onclick="document.forms[0].q.value=''" />
                <label for="sa"></label>
                <input type="hidden" name="entqr" value="1" />
                <input type="hidden" name="output" value="xml_no_dtd" />
                <input type="hidden" name="sort" value="date:D:L:d1" />
                <input type="hidden" name="entsp" value="a" />
                <input type="hidden" name="client" value="default_frontend" />
                <input type="hidden" name="ud" value="1" />
                <input type="hidden" name="oe" value="UTF-8" /><input type="hidden" name="ie" value="UTF-8" />
                <input type="hidden" name="proxystylesheet" value="default_frontend" />
                <input type="hidden" name="site" value="default_collection" />
         </form-->
	</div>
	<!--End search Box-->
	<!-- Start Breadcrumbs-->
	<div class="breadcrumb">

			

<ul>
  <li>
    <a href="http://www.ucl.ac.uk">UCL Home</a>
    <!--[if gte IE 5]>&raquo;<![endif]-->
  </li>
  <li>
    <a href="http://www.ucl.ac.uk/isd">ISD</a>
    <!--[if gte IE 5]>&raquo;<![endif]-->
  </li>
  <li>
    <a href="http://www.ucl.ac.uk/isd/staff">Staff</a>
    <!--[if gte IE 5]>&raquo;<![endif]-->
  </li>
  <li>
    <a href="http://www.ucl.ac.uk/isd/staff/research_services">RITS</a>
    <!--[if gte IE 5]>&raquo;<![endif]-->
  </li>
  <li>
    <a href="http://www.ucl.ac.uk/isd/staff/research_services/research-computing">Research Computing</a>
    <!--[if gte IE 5]>&raquo;<![endif]-->
  </li>
  <li>
  <a href="http://www.ucl.ac.uk/isd/staff/research_services/research-computing/account"><?php echo $page_title;?></a>
    
  </li>
</ul>

		

	</div>
</div> 
<!--End Breadcrumbs-->

			
		<div class="clearing"></div>
		  
		<!-- End banner and breadcrumbs --> 
		<!-- Start Main body area-->  
		<div id="content">
			<div id="centercontent" class="container"> 
				<div id="centercontentrhborder" class="container">
					<!--tal:block metal:define-slot="resizer" content="structure context/@@ucl_text_resizer"/-->
                    <div id="rendered-doc-area">
					<!--Start center content area-->
<div class="heading">
<h2 class="heading"><?php echo $page_title;?></h2>
</div>

