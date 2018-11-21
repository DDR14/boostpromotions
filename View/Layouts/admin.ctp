<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0, minimal-ui" name="viewport">
	<?= $this->Html->meta("description", $this->fetch("description")); ?>
	<?= $this->Html->meta("keywords", $this->fetch("keywords")); ?>

	<title>
		<?= $this->fetch("title") ?> &mdash; Erskin Tech
	</title>

	 <!-- Google Analytics -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-68272714-38', 'auto');
		  ga('send', 'pageview');
	</script>

	<?= $this->Html->css("bootstrap-3.3.7/css/bootstrap.min.css"); ?>
	<?= $this->Html->css("font-awesome/css/font-awesome.min.css") ?>
	<?= $this->Html->css("site.css"); ?>
</head>
<body>

	<!-- Header -->
	<?= $this->element("headings"); ?>
	<?= $this->fetch("adminHeader"); ?>

	<!-- Boody  -->
	<div class="container">
		<div class="row">
			<?= $this->element("sidebars"); ?>
			<?= $this->fetch("dashBoardSidebarAdmin"); ?>

			<div class="col-md-9">
				<?= $this->fetch("content"); ?>
			</div>
		</div>
	</div>

	<!-- Footer -->

	<?= $this->Html->script("jquery-3.1.1/jquery-3.1.1.js"); ?>
	<?= $this->Html->script("bootstrap-3.3.7/js/bootstrap.min.js"); ?>
	<?= $this->Html->script("site.js"); ?>
</body>
</html>
