<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script type="text/javascript" src="validacion/js/formValidation.js"></script>
<script type="text/javascript" src="validacion/js/framework/bootstrap.js"></script>
<link rel="stylesheet" href="validacion/css/formValidation.css"/>
<script src="assets/js/fancybox/jquery.fancybox.js"></script>
	


<div class="container pie-sitio">
	<!-- Footer -->
	<footer>
		<div class="row">
			<div class="col-lg-12">
				<a data-fancybox-type="iframe" class="various" href="bases_condiciones.html"><?php echo _('Bases y Condiciones');?></a>
				
				<p>©2017</p>
			</div>
		</div>
	</footer>
</div>
<!-- /.container -->

<script>
	$(document).ready(function() {
		
		$('.various').fancybox({
			maxWidth	: 615,
			maxHeight	: 600,
			fitToView	: false,
			width		: '100%',
			height		: '90%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
	});	
</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-97492828-1', 'auto');
  ga('send', 'pageview');
</script>