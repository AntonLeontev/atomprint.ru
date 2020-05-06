<div class="clients">
			<div class="clients_cont">
				<h3>Нам доверяют</h3>
				<ul id="flexiselDemo2">
    				<li><img src="assets/img/clients/image001.png" /></li>
    				<li><img src="assets/img/clients/image002.png" /></li>
    				<li><img src="assets/img/clients/image003.png" /></li>
    				<li><img src="assets/img/clients/image004.png" /></li>
    				<li><img src="assets/img/clients/image005.png" /></li>
    				<li><img src="assets/img/clients/image007.png" /></li>
    				<li><img src="assets/img/clients/image008.png" /></li>
    				<li><img src="assets/img/clients/image009.png" /></li>
    				<li><img src="assets/img/clients/image010.png" /></li>
    				<li><img src="assets/img/clients/image011.png" /></li>
    				<li><img src="assets/img/clients/image012.png" /></li>
    				<li><img src="assets/img/clients/image013.jpg" /></li>
				</ul>

				<div class="clearout"></div>
			</div>
		</div>

		<script type="text/javascript">

$(window).load(function() {
    $("#flexiselDemo1").flexisel();

    $("#flexiselDemo2").flexisel({
        visibleItems: 4,
        itemsToScroll: 4,
        animationSpeed: 200,
        infinite: true,
        navigationTargetSelector: null,
        autoPlay: {
            enable: true,
            interval: 5000,
            pauseOnHover: true
        },
        responsiveBreakpoints: {
            portrait: {
                changePoint:480,
                visibleItems: 2,
                itemsToScroll: 2
            },
            landscape: {
                changePoint:640,
                visibleItems: 2,
                itemsToScroll: 2
            },
            tablet: {
                changePoint:768,
                visibleItems: 3,
                itemsToScroll: 3
            }
        },
      /*  loaded: function(object) {
            console.log('Slider loaded...');
        },
        before: function(object){
            console.log('Before transition...');
        },
        after: function(object) {
            console.log('After transition...');
        },
        resize: function(object){
            console.log('After resize...');
        }*/
    });

    $("#flexiselDemo3").flexisel({
        visibleItems: 3,
        itemsToScroll: 1,
        autoPlay: {
            enable: true,
            interval: 5000,
            pauseOnHover: true
        }
    });

    $("#flexiselDemo4").flexisel({
        infinite: false
    });

});
</script>
