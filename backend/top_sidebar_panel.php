<header id="header">
<hgroup>
	<h1 class="site_title"><a href="index_page.php"><?php echo SITE_NAME; ?></a></h1>
	<h2 class="section_title">&nbsp;</h2><div class="section_title_right"><a href="javascript:location.reload();" class="btn_black">Refresh</a><a href="<?php echo ROOT_URL; ?>" target="_blank" class="btn_black">View Site</a></div>
</hgroup>
</header>
<section id="secondary_bar">
	<div class="user">
		<p><?php echo $_SESSION['sessName']; ?> (<a href="signout.php"><?php echo SIGN_OUT; ?></a>)</p>
		<a class="logout_user" href="#" title="Logout">Logout</a>
	</div>
	<div class="breadcrumbs_container">
		<article class="breadcrumbs"><a href="index_page.php"><?php echo SITE_NAME; ?></a> <div class="breadcrumb_divider"></div> <a class="current"><?php echo $menuInfo['curmenu']; ?></a></article>
	</div>
</section>
<aside id="sidebar" class="column">
    <h3><?php echo COME; ?></h3>
	<ul class="toggle">
		<li class="icn_categories"><a href="test_page.php"><?php echo COM; ?></a></li>
		<li class="icn_new_article"><a href="test_add_page.php"><?php echo COM,' - ',ADD; ?></a></li>
	</ul>
<footer>
	<hr />
	<p><strong>Copyright &copy;  <?php echo date('Y'); ?></strong></p><br /></div>
</footer>
</aside><!-- end of sidebar -->