<body>
	<script type="text/javascript" src="js/main_page.js"></script>
	<div id="global_wrapper">
		<div id="header">
			<a href="index.php" id="header_logo"></a>
			<input class="search" id="global_search" />
			<a id="header_options"></a>
		</div>
		
		<div id="content_wrapper">
			<!-- navigation -->
			<div class="content_column" id="column_left">
				<div class="content_column_wrapper" id="column_wrapper_left">
					<ul>
						<li class="button_middle"><a id="button_home" href="index.php"
							class="<?php if ($_PAGE_TITLE=="Home") echo "selected"?>">Home</a></li>
						<li class="button_middle"><a id="button_community" href="community.php"
							class="<?php if ($_PAGE_TITLE=="Community") echo "selected"?>">Community</a></li>
						<li><a id="button_list" href="browse.php"
							class="<?php if ($_PAGE_TITLE=="Browse Activities") echo "selected"?>">Browse Activities</a></li>
					</ul>
				</div>
			</div>