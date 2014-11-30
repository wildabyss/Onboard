<script type="text/javascript" src="js/jquery-1.11.1.js"></script>

<div id="global_wrapper">
	<div id="header">
		<a href="/mylist" id="header_logo"></a>
		<input class="search" id="global_search" placeholder="search" />
		<a id="header_options"></a>
	</div>
	
	<div id="content_wrapper">
		<!-- navigation -->
		<div class="content_column" id="column_left">
			<div class="content_column_wrapper" id="column_wrapper_left">
				<ul>
					<li class="button_middle"><a id="button_list" href="/mylist"
						class="<?php if ($_PAGE_TITLE==Utilities::PAGE_MY_ACTIVITIES) echo "selected"?>">My Activities</a></li>
					<li class="button_middle"><a id="button_recent" href="/recent"
						class="<?php if ($_PAGE_TITLE==Utilities::PAGE_RECENT) echo "selected"?>">News Feed</a></li>
					<li class="button_middle"><a id="button_community" href="/community"
						class="<?php if ($_PAGE_TITLE==Utilities::PAGE_COMMUNITY) echo "selected"?>">Community</a></li>
					<li><a id="button_browse" href="/browse"
						class="<?php if ($_PAGE_TITLE==Utilities::PAGE_BROWSE) echo "selected"?>">Browse</a></li>
				</ul>
			</div>
		</div>